<?php

use App\Models\Student;
use App\Models\User;
use App\Services\ExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 38: Export column headers
// For any export (Excel or PDF), the file should include column headers for all six fields.
// Validates: Requirements 13.5

test('property: excel export includes all column headers', function () {
    // Create authenticated user
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Generate random number of students (1-5)
    $studentCount = fake()->numberBetween(1, 5);
    
    for ($i = 0; $i < $studentCount; $i++) {
        Student::create([
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
    Excel::store($export, 'test-headers.xlsx', 'local');
    
    // Read the exported file
    $data = Excel::toArray($export, storage_path('app/test-headers.xlsx'));
    $rows = $data[0]; // First sheet
    
    // Get the header row (first row)
    $headerRow = $rows[0];
    
    // Define expected headers for all six fields
    $expectedHeaders = [
        'Full Name',
        'Student ID',
        'Course',
        'Year Level',
        'Contact Number',
        'Address'
    ];
    
    // Verify all six column headers are present
    foreach ($expectedHeaders as $expectedHeader) {
        expect($headerRow)->toContain($expectedHeader);
    }
    
    // Verify we have exactly 6 columns
    expect($headerRow)->toHaveCount(6);
    
    // Clean up
    unlink(storage_path('app/test-headers.xlsx'));
})->repeat(100);

test('property: pdf export includes all column headers', function () {
    // Create authenticated user
    $user = User::factory()->create();
    $this->actingAs($user);
    
    // Generate random number of students (1-5)
    $studentCount = fake()->numberBetween(1, 5);
    
    for ($i = 0; $i < $studentCount; $i++) {
        Student::create([
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
    
    // Define expected headers for all six fields
    $expectedHeaders = [
        'Full Name',
        'Student ID',
        'Course',
        'Year Level',
        'Contact Number',
        'Address'
    ];
    
    // Verify all six column headers are present in the PDF content
    foreach ($expectedHeaders as $expectedHeader) {
        expect($pdfOutput)->toContain($expectedHeader);
    }
})->repeat(100);
