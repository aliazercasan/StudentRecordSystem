<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 3: Duplicate student ID prevention
// For any existing student record, attempting to create another student with the same student_id
// should be rejected with a uniqueness error.
// Validates: Requirements 1.3

test('property: duplicate student ID prevention', function () {
    // Generate random valid student data
    $studentId = generateValidStudentId();
    $fullName1 = generateValidFullName();
    $fullName2 = generateValidFullName();
    $course1 = generateValidCourse();
    $course2 = generateValidCourse();
    $yearLevel1 = fake()->numberBetween(1, 6);
    $yearLevel2 = fake()->numberBetween(1, 6);
    
    // Create first student with this ID
    $student1 = Student::create([
        'student_id' => $studentId,
        'full_name' => $fullName1,
        'course' => $course1,
        'year_level' => $yearLevel1,
    ]);
    
    // Verify first student was created
    expect($student1)->not->toBeNull();
    expect($student1->student_id)->toBe($studentId);
    
    // Attempt to create second student with same student_id
    // This should throw a QueryException due to unique constraint
    try {
        Student::create([
            'student_id' => $studentId,
            'full_name' => $fullName2,
            'course' => $course2,
            'year_level' => $yearLevel2,
        ]);
        
        // If we reach here, the test should fail
        expect(false)->toBeTrue('Expected QueryException was not thrown');
    } catch (QueryException $e) {
        // This is expected - the unique constraint should prevent the duplicate
        expect($e)->toBeInstanceOf(QueryException::class);
        
        // Verify only one student with this ID exists
        $count = Student::where('student_id', $studentId)->count();
        expect($count)->toBe(1);
    }
})->repeat(100);
