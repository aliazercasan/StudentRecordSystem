<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 11: Student deletion completeness
// For any existing student, after deletion, querying the database for that student should return no results.
// Validates: Requirements 5.1

test('property: student deletion completeness', function () {
    // Generate random valid student data
    $studentId = generateValidStudentId();
    $fullName = generateValidFullName();
    $course = generateValidCourse();
    $yearLevel = fake()->numberBetween(1, 6);
    
    // Create student
    $student = Student::create([
        'student_id' => $studentId,
        'full_name' => $fullName,
        'course' => $course,
        'year_level' => $yearLevel,
    ]);
    
    // Verify student exists before deletion
    $beforeDeletion = Student::where('student_id', $studentId)->first();
    expect($beforeDeletion)->not->toBeNull();
    
    // Delete the student
    $student->delete();
    
    // Query the database for the deleted student
    $afterDeletion = Student::where('student_id', $studentId)->first();
    
    // Assert the student no longer exists
    expect($afterDeletion)->toBeNull();
    
    // Also verify by ID
    $byId = Student::find($student->id);
    expect($byId)->toBeNull();
})->repeat(100);
