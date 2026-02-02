<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 12: Form data preservation on validation failure
// For any invalid form submission, the form should be re-rendered with the previously submitted data preserved in the input fields.
// Validates: Requirements 6.2

beforeEach(function () {
    // Create and authenticate a user for all tests
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('property: form data preservation on create validation failure - invalid student_id', function () {
    $invalidData = [
        'student_id' => generateInvalidStudentId(), // Invalid student ID
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->numerify('##########'),
        'address' => fake()->address(),
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should redirect back with errors
    $response->assertSessionHasErrors('student_id');
    
    // Old input should be preserved
    expect(session()->getOldInput('full_name'))->toBe($invalidData['full_name']);
    expect(session()->getOldInput('course'))->toBe($invalidData['course']);
    expect(session()->getOldInput('year_level'))->toBe($invalidData['year_level']);
    expect(session()->getOldInput('contact_number'))->toBe($invalidData['contact_number']);
    expect(session()->getOldInput('address'))->toBe($invalidData['address']);
})->repeat(100);

test('property: form data preservation on create validation failure - invalid full_name', function () {
    $invalidData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateInvalidFullName(), // Invalid full name
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->numerify('##########'),
        'address' => fake()->address(),
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should redirect back with errors
    $response->assertSessionHasErrors('full_name');
    
    // Old input should be preserved
    expect(session()->getOldInput('student_id'))->toBe($invalidData['student_id']);
    expect(session()->getOldInput('course'))->toBe($invalidData['course']);
    expect(session()->getOldInput('year_level'))->toBe($invalidData['year_level']);
    expect(session()->getOldInput('contact_number'))->toBe($invalidData['contact_number']);
    expect(session()->getOldInput('address'))->toBe($invalidData['address']);
})->repeat(100);

test('property: form data preservation on create validation failure - invalid course', function () {
    $invalidData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateInvalidCourse(), // Invalid course
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->numerify('##########'),
        'address' => fake()->address(),
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should redirect back with errors
    $response->assertSessionHasErrors('course');
    
    // Old input should be preserved
    expect(session()->getOldInput('student_id'))->toBe($invalidData['student_id']);
    expect(session()->getOldInput('full_name'))->toBe($invalidData['full_name']);
    expect(session()->getOldInput('year_level'))->toBe($invalidData['year_level']);
    expect(session()->getOldInput('contact_number'))->toBe($invalidData['contact_number']);
    expect(session()->getOldInput('address'))->toBe($invalidData['address']);
})->repeat(100);

test('property: form data preservation on create validation failure - invalid year_level', function () {
    $invalidData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => generateInvalidYearLevel(), // Invalid year level
        'contact_number' => fake()->numerify('##########'),
        'address' => fake()->address(),
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should redirect back with errors
    $response->assertSessionHasErrors('year_level');
    
    // Old input should be preserved
    expect(session()->getOldInput('student_id'))->toBe($invalidData['student_id']);
    expect(session()->getOldInput('full_name'))->toBe($invalidData['full_name']);
    expect(session()->getOldInput('course'))->toBe($invalidData['course']);
    expect(session()->getOldInput('contact_number'))->toBe($invalidData['contact_number']);
    expect(session()->getOldInput('address'))->toBe($invalidData['address']);
})->repeat(100);

test('property: form data preservation on update validation failure - invalid student_id', function () {
    // Create a student first
    $student = \App\Models\Student::factory()->create();
    
    $invalidData = [
        'student_id' => generateInvalidStudentId(), // Invalid student ID
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->numerify('##########'),
        'address' => fake()->address(),
    ];
    
    $response = $this->put(route('students.update', $student), $invalidData);
    
    // Should redirect back with errors
    $response->assertSessionHasErrors('student_id');
    
    // Old input should be preserved
    expect(session()->getOldInput('full_name'))->toBe($invalidData['full_name']);
    expect(session()->getOldInput('course'))->toBe($invalidData['course']);
    expect(session()->getOldInput('year_level'))->toBe($invalidData['year_level']);
    expect(session()->getOldInput('contact_number'))->toBe($invalidData['contact_number']);
    expect(session()->getOldInput('address'))->toBe($invalidData['address']);
})->repeat(100);

test('property: form data preservation on update validation failure - invalid contact_number', function () {
    // Create a student first
    $student = \App\Models\Student::factory()->create();
    
    $invalidData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => generateInvalidContactNumber(), // Invalid contact number
        'address' => fake()->address(),
    ];
    
    $response = $this->put(route('students.update', $student), $invalidData);
    
    // Should redirect back with errors
    $response->assertSessionHasErrors('contact_number');
    
    // Old input should be preserved
    expect(session()->getOldInput('student_id'))->toBe($invalidData['student_id']);
    expect(session()->getOldInput('full_name'))->toBe($invalidData['full_name']);
    expect(session()->getOldInput('course'))->toBe($invalidData['course']);
    expect(session()->getOldInput('year_level'))->toBe($invalidData['year_level']);
    expect(session()->getOldInput('address'))->toBe($invalidData['address']);
})->repeat(100);
