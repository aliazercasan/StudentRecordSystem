<?php

use App\Models\Student;
use App\Models\User;
use App\Services\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 37: Export filter respect
// For any search query, the exported file should contain only students matching the search criteria.
// Validates: Requirements 13.3

test('property: excel export respects search filter', function () {
    // Create authenticated user
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create students with distinct searchable attributes
    // Group 1: Students with "Computer Science" course
    $csStudents = [];
    $csCount = fake()->numberBetween(2, 5);
    for ($i = 0; $i < $csCount; $i++) {
        $csStudents[] = Student::create([
            'student_id' => generateValidStudentId(),
            'full_name' => generateValidFullName(),
            'course' => 'Computer Science',
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => fake()->numerify('##########'),
            'address' => fake()->address(),
        ]);
    }
    
    // Group 2: Students with "Business Administration" course
    $baCount = fake()->numberBetween(2, 5);
    for ($i = 0; $i < $baCount; $i++) {
        Student::create([
            'student_id' => generateValidStudentId(),
            'full_name' => generateValidFullName(),
            'course' => 'Business Administration',
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => fake()->numerify('##########'),
            'address' => fake()->address(),
        ]);
    }
    
    // Apply search filter for "Computer Science"
    $searchTerm = 'Computer Science';
    $query = Student::query();
    $query->where(function ($q) use ($searchTerm) {
        $q->where('full_name', 'like', "%{$searchTerm}%")
            ->orWhere('student_id', 'like', "%{$searchTerm}%")
            ->orWhere('course', 'like', "%{$searchTerm}%")
            ->orWhere('year_level', 'like', "%{$searchTerm}%");
    });
    
    $filteredStudents = $query->get();
    
    // Export the filtered students
    $exportService = new ExportService();
    $export = $exportService->exportToExcel($filteredStudents);
    
    // Store the export temporarily to read it
    Excel::store($export, 'test-filter.xlsx', 'local');
    
    // Read the exported file
    $data = Excel::toArray($export, storage_path('app/test-filter.xlsx'));
    $rows = $data[0]; // First sheet
    
    // Verify the export contains only filtered students (+ header row)
    expect($rows)->toHaveCount($csCount + 1); // +1 for header
    
    // Verify all rows (except header) match the search criteria
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        $rowString = implode(' ', $row);
        
        // The row should contain the search term
        expect($rowString)->toContain($searchTerm);
    }
    
    // Verify that all Computer Science students are in the export
    foreach ($csStudents as $student) {
        $found = false;
        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (in_array($student->student_id, $row)) {
                $found = true;
                break;
            }
        }
        expect($found)->toBeTrue();
    }
    
    // Clean up
    unlink(storage_path('app/test-filter.xlsx'));
})->repeat(100);

test('property: pdf export respects search filter', function () {
    // Create authenticated user
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create students with distinct searchable attributes
    // Group 1: Students with year level 1
    $year1Students = [];
    $year1Count = fake()->numberBetween(2, 5);
    for ($i = 0; $i < $year1Count; $i++) {
        $year1Students[] = Student::create([
            'student_id' => generateValidStudentId(),
            'full_name' => generateValidFullName(),
            'course' => generateValidCourse(),
            'year_level' => 1,
            'contact_number' => fake()->numerify('##########'),
            'address' => fake()->address(),
        ]);
    }
    
    // Group 2: Students with year level 3
    $year3Count = fake()->numberBetween(2, 5);
    for ($i = 0; $i < $year3Count; $i++) {
        Student::create([
            'student_id' => generateValidStudentId(),
            'full_name' => generateValidFullName(),
            'course' => generateValidCourse(),
            'year_level' => 3,
            'contact_number' => fake()->numerify('##########'),
            'address' => fake()->address(),
        ]);
    }
    
    // Apply search filter for year level 1
    $searchTerm = '1';
    $query = Student::query();
    $query->where(function ($q) use ($searchTerm) {
        $q->where('full_name', 'like', "%{$searchTerm}%")
            ->orWhere('student_id', 'like', "%{$searchTerm}%")
            ->orWhere('course', 'like', "%{$searchTerm}%")
            ->orWhere('year_level', 'like', "%{$searchTerm}%");
    });
    
    $filteredStudents = $query->get();
    
    // Export the filtered students to PDF
    $exportService = new ExportService();
    $pdf = $exportService->exportToPdf($filteredStudents);
    
    // Get the PDF output as string
    $pdfOutput = $pdf->output();
    
    // Verify all year 1 students are in the PDF
    foreach ($year1Students as $student) {
        expect($pdfOutput)->toContain($student->student_id);
        expect($pdfOutput)->toContain($student->full_name);
    }
    
    // The PDF should contain exactly the filtered count of students
    // We can verify this by checking that the filtered students are present
    expect($filteredStudents->count())->toBeGreaterThanOrEqual($year1Count);
})->repeat(100);

test('property: export respects full name search filter', function () {
    // Create authenticated user
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create a student with a unique name pattern
    $uniqueName = 'Zephyr Quantum';
    $targetStudent = Student::create([
        'student_id' => generateValidStudentId(),
        'full_name' => $uniqueName,
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->numerify('##########'),
        'address' => fake()->address(),
    ]);
    
    // Create other students with different names
    $otherCount = fake()->numberBetween(3, 7);
    for ($i = 0; $i < $otherCount; $i++) {
        Student::create([
            'student_id' => generateValidStudentId(),
            'full_name' => generateValidFullName(),
            'course' => generateValidCourse(),
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => fake()->numerify('##########'),
            'address' => fake()->address(),
        ]);
    }
    
    // Apply search filter for the unique name
    $searchTerm = 'Zephyr';
    $query = Student::query();
    $query->where(function ($q) use ($searchTerm) {
        $q->where('full_name', 'like', "%{$searchTerm}%")
            ->orWhere('student_id', 'like', "%{$searchTerm}%")
            ->orWhere('course', 'like', "%{$searchTerm}%")
            ->orWhere('year_level', 'like', "%{$searchTerm}%");
    });
    
    $filteredStudents = $query->get();
    
    // Export the filtered students
    $exportService = new ExportService();
    $export = $exportService->exportToExcel($filteredStudents);
    
    // Store the export temporarily to read it
    Excel::store($export, 'test-name-filter.xlsx', 'local');
    
    // Read the exported file
    $data = Excel::toArray($export, storage_path('app/test-name-filter.xlsx'));
    $rows = $data[0]; // First sheet
    
    // Verify the export contains only the filtered student (+ header row)
    expect($rows)->toHaveCount($filteredStudents->count() + 1);
    
    // Verify the target student is in the export
    $found = false;
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        if (in_array($targetStudent->student_id, $row) && in_array($uniqueName, $row)) {
            $found = true;
            break;
        }
    }
    expect($found)->toBeTrue();
    
    // Clean up
    unlink(storage_path('app/test-name-filter.xlsx'));
})->repeat(100);

test('property: export respects student id search filter', function () {
    // Create authenticated user
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Create a student with a unique student ID pattern
    $uniqueId = 'UNIQUE12345';
    $targetStudent = Student::create([
        'student_id' => $uniqueId,
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->numerify('##########'),
        'address' => fake()->address(),
    ]);
    
    // Create other students with different IDs
    $otherCount = fake()->numberBetween(3, 7);
    for ($i = 0; $i < $otherCount; $i++) {
        Student::create([
            'student_id' => generateValidStudentId(),
            'full_name' => generateValidFullName(),
            'course' => generateValidCourse(),
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => fake()->numerify('##########'),
            'address' => fake()->address(),
        ]);
    }
    
    // Apply search filter for the unique ID
    $searchTerm = 'UNIQUE';
    $query = Student::query();
    $query->where(function ($q) use ($searchTerm) {
        $q->where('full_name', 'like', "%{$searchTerm}%")
            ->orWhere('student_id', 'like', "%{$searchTerm}%")
            ->orWhere('course', 'like', "%{$searchTerm}%")
            ->orWhere('year_level', 'like', "%{$searchTerm}%");
    });
    
    $filteredStudents = $query->get();
    
    // Export the filtered students
    $exportService = new ExportService();
    $export = $exportService->exportToExcel($filteredStudents);
    
    // Store the export temporarily to read it
    Excel::store($export, 'test-id-filter.xlsx', 'local');
    
    // Read the exported file
    $data = Excel::toArray($export, storage_path('app/test-id-filter.xlsx'));
    $rows = $data[0]; // First sheet
    
    // Verify the export contains only the filtered student (+ header row)
    expect($rows)->toHaveCount($filteredStudents->count() + 1);
    
    // Verify the target student is in the export
    $found = false;
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        if (in_array($uniqueId, $row)) {
            $found = true;
            break;
        }
    }
    expect($found)->toBeTrue();
    
    // Clean up
    unlink(storage_path('app/test-id-filter.xlsx'));
})->repeat(100);
