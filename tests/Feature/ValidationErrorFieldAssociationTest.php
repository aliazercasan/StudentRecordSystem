<?php

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 21: Validation error field association
// For any form submission with validation errors, each error message should be associated with the specific field that failed validation.
// Validates: Requirements 8.3

beforeEach(function () {
    // Create and authenticate a user for all tests
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('property: validation error field association - student_id errors are associated with student_id field', function () {
    $invalidData = [
        'student_id' => generateInvalidStudentId(), // Invalid student ID
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should have validation errors
    $response->assertSessionHasErrors();
    
    // Error should be specifically associated with student_id field
    $response->assertSessionHasErrors('student_id');
    
    // Verify the error is in the errors bag under the correct key
    $errors = session('errors');
    expect($errors->has('student_id'))->toBeTrue();
    expect($errors->get('student_id'))->toBeArray();
    expect(count($errors->get('student_id')))->toBeGreaterThan(0);
})->repeat(100);

test('property: validation error field association - full_name errors are associated with full_name field', function () {
    $invalidData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateInvalidFullName(), // Invalid full name
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should have validation errors
    $response->assertSessionHasErrors();
    
    // Error should be specifically associated with full_name field
    $response->assertSessionHasErrors('full_name');
    
    // Verify the error is in the errors bag under the correct key
    $errors = session('errors');
    expect($errors->has('full_name'))->toBeTrue();
    expect($errors->get('full_name'))->toBeArray();
    expect(count($errors->get('full_name')))->toBeGreaterThan(0);
})->repeat(100);

test('property: validation error field association - course errors are associated with course field', function () {
    $invalidData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateInvalidCourse(), // Invalid course
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should have validation errors
    $response->assertSessionHasErrors();
    
    // Error should be specifically associated with course field
    $response->assertSessionHasErrors('course');
    
    // Verify the error is in the errors bag under the correct key
    $errors = session('errors');
    expect($errors->has('course'))->toBeTrue();
    expect($errors->get('course'))->toBeArray();
    expect(count($errors->get('course')))->toBeGreaterThan(0);
})->repeat(100);

test('property: validation error field association - year_level errors are associated with year_level field', function () {
    $invalidData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => generateInvalidYearLevel(), // Invalid year level
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should have validation errors
    $response->assertSessionHasErrors();
    
    // Error should be specifically associated with year_level field
    $response->assertSessionHasErrors('year_level');
    
    // Verify the error is in the errors bag under the correct key
    $errors = session('errors');
    expect($errors->has('year_level'))->toBeTrue();
    expect($errors->get('year_level'))->toBeArray();
    expect(count($errors->get('year_level')))->toBeGreaterThan(0);
})->repeat(100);

test('property: validation error field association - contact_number errors are associated with contact_number field', function () {
    $invalidData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => generateInvalidContactNumber(), // Invalid contact number
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should have validation errors
    $response->assertSessionHasErrors();
    
    // Error should be specifically associated with contact_number field
    $response->assertSessionHasErrors('contact_number');
    
    // Verify the error is in the errors bag under the correct key
    $errors = session('errors');
    expect($errors->has('contact_number'))->toBeTrue();
    expect($errors->get('contact_number'))->toBeArray();
    expect(count($errors->get('contact_number')))->toBeGreaterThan(0);
})->repeat(100);

test('property: validation error field association - address errors are associated with address field', function () {
    $invalidData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'address' => generateInvalidAddress(), // Invalid address
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should have validation errors
    $response->assertSessionHasErrors();
    
    // Error should be specifically associated with address field
    $response->assertSessionHasErrors('address');
    
    // Verify the error is in the errors bag under the correct key
    $errors = session('errors');
    expect($errors->has('address'))->toBeTrue();
    expect($errors->get('address'))->toBeArray();
    expect(count($errors->get('address')))->toBeGreaterThan(0);
})->repeat(100);

test('property: validation error field association - multiple field errors are each associated with their respective fields', function () {
    $invalidData = [
        'student_id' => generateInvalidStudentId(), // Invalid
        'full_name' => generateInvalidFullName(), // Invalid
        'course' => generateInvalidCourse(), // Invalid
        'year_level' => generateInvalidYearLevel(), // Invalid
    ];
    
    $response = $this->post(route('students.store'), $invalidData);
    
    // Should have validation errors
    $response->assertSessionHasErrors();
    
    // Each error should be associated with its specific field
    $response->assertSessionHasErrors(['student_id', 'full_name', 'course', 'year_level']);
    
    // Verify each error is in the errors bag under the correct key
    $errors = session('errors');
    expect($errors->has('student_id'))->toBeTrue();
    expect($errors->has('full_name'))->toBeTrue();
    expect($errors->has('course'))->toBeTrue();
    expect($errors->has('year_level'))->toBeTrue();
})->repeat(100);

test('property: validation error field association - update form errors are field-specific', function () {
    // Create a student first
    $student = Student::factory()->create();
    
    $invalidData = [
        'student_id' => generateInvalidStudentId(), // Invalid student ID
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $response = $this->put(route('students.update', $student), $invalidData);
    
    // Should have validation errors
    $response->assertSessionHasErrors();
    
    // Error should be specifically associated with student_id field
    $response->assertSessionHasErrors('student_id');
    
    // Verify the error is in the errors bag under the correct key
    $errors = session('errors');
    expect($errors->has('student_id'))->toBeTrue();
    expect($errors->get('student_id'))->toBeArray();
    expect(count($errors->get('student_id')))->toBeGreaterThan(0);
})->repeat(100);
