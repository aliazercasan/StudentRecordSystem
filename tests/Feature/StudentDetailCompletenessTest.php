<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 6: Student detail completeness
// For any student record, the detail view should display all six fields: full_name, student_id, course, year_level, contact_number, and address.
// Validates: Requirements 3.1, 3.3

test('property: student detail completeness', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Create a student with all fields populated
    $student = Student::create([
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->numerify('##########'),
        'address' => fake()->address(),
    ]);
    
    // Get the student detail page
    $response = $this->actingAs($user)->get(route('students.show', $student));
    $response->assertStatus(200);
    
    // Assert all six fields are displayed in the HTML
    $response->assertSee($student->student_id);
    $response->assertSee($student->full_name);
    $response->assertSee($student->course);
    $response->assertSee((string)$student->year_level);
    $response->assertSee($student->contact_number);
    $response->assertSee($student->address);
})->repeat(100);
