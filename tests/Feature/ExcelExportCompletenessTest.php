<?php

use App\Models\Student;
use App\Models\User;
use App\Services\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 35: Excel export completeness
// For any set of visible students, the exported Excel file should contain rows for all students with all six data fields.
// Validates: Requirements 13.1

test('property: excel export completeness', function () {
    // Create authenticated user
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Generate random number of students (1-10)
    $studentCount = fake()->numberBetween(1, 10);
    $students = [];
    
    for ($i = 0; $i < $studentCount; $i++) {
        $students[] = Student::create([
            'student_id' => generateValidStudentId(),
            'full_name' => generateValidFullName(),
            'course' => generateValidCourse(),
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => fake()->numerify('##########'),
            'address' => fake()->address(),
        ]);
    }
    
    // Get all students from database
    $allStudents = Student::all();
    
    // Export to Excel using the service
    $exportService = new ExportService();
    $export = $exportService->exportToExcel($allStudents);
    
    // Store the export temporarily to read it
    Excel::store($export, 'test-export.xlsx', 'local');
    
    // Read the exported file
    $data = Excel::toArray($export, storage_path('app/test-export.xlsx'));
    $rows = $data[0]; // First sheet
    
    // Verify header row exists (should be first row)
    expect($rows)->toHaveCount($studentCount + 1); // +1 for header
    
    // Verify all students are present in the export
    foreach ($students as $index => $student) {
        $row = $rows[$index + 1]; // Skip header row
        
        // Verify all six fields are present
        expect($row)->toContain($student->full_name);
        expect($row)->toContain($student->student_id);
        expect($row)->toContain($student->course);
        expect($row)->toContain($student->year_level);
        expect($row)->toContain($student->contact_number ?? '');
        expect($row)->toContain($student->address ?? '');
    }
    
    // Clean up
    unlink(storage_path('app/test-export.xlsx'));
})->repeat(100);
