<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 1: Student creation persistence
// For any valid student data (with required fields: student_id, full_name, course, year_level),
// after creating a student record, querying the database should return a record with the same data.
// Validates: Requirements 1.1, 1.5

test('property: student creation persistence', function () {
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
    
    // Query the database
    $retrieved = Student::where('student_id', $studentId)->first();
    
    // Assert the data matches
    expect($retrieved)->not->toBeNull();
    expect($retrieved->student_id)->toBe($studentId);
    expect($retrieved->full_name)->toBe($fullName);
    expect($retrieved->course)->toBe($course);
    expect($retrieved->year_level)->toBe($yearLevel);
})->repeat(100);
