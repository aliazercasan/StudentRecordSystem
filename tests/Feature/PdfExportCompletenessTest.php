<?php

use App\Models\Student;
use App\Models\User;
use App\Services\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 36: PDF export completeness
// For any set of visible students, the exported PDF should contain all students with all six data fields.
// Validates: Requirements 13.2

test('property: pdf export completeness', function () {
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
    
    // Export to PDF using the service
    $exportService = new ExportService();
    $pdf = $exportService->exportToPdf($allStudents);
    
    // Get the PDF output as string
    $pdfOutput = $pdf->output();
    
    // Verify all students are present in the PDF
    foreach ($students as $student) {
        // Verify all six fields are present in the PDF content
        expect($pdfOutput)->toContain($student->full_name);
        expect($pdfOutput)->toContain($student->student_id);
        expect($pdfOutput)->toContain($student->course);
        expect($pdfOutput)->toContain((string)$student->year_level);
        
        // Contact number and address might be null, so check if they exist
        if ($student->contact_number) {
            expect($pdfOutput)->toContain($student->contact_number);
        }
        if ($student->address) {
            expect($pdfOutput)->toContain($student->address);
        }
    }
})->repeat(100);
