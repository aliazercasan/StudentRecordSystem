<?php

use App\Http\Requests\StoreStudentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 13: Full name validation enforcement
// For any full_name value that doesn't match the pattern (alphabetic characters and spaces, 2-100 characters),
// validation should reject it.
// Validates: Requirements 6.3

test('property: full name validation enforcement', function () {
    $invalidFullName = generateInvalidFullName();
    
    $data = [
        'student_id' => generateValidStudentId(),
        'full_name' => $invalidFullName,
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('full_name'))->toBeTrue();
})->repeat(100);

// Feature: student-record-system, Property 14: Student ID validation enforcement
// For any student_id value that doesn't match the pattern (alphanumeric, 5-20 characters),
// validation should reject it.
// Validates: Requirements 6.4

test('property: student ID validation enforcement', function () {
    $invalidStudentId = generateInvalidStudentId();
    
    $data = [
        'student_id' => $invalidStudentId,
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('student_id'))->toBeTrue();
})->repeat(100);

// Feature: student-record-system, Property 15: Course validation enforcement
// For any course value that doesn't match the pattern (alphabetic characters and spaces, 2-100 characters),
// validation should reject it.
// Validates: Requirements 6.5

test('property: course validation enforcement', function () {
    $invalidCourse = generateInvalidCourse();
    
    $data = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => $invalidCourse,
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('course'))->toBeTrue();
})->repeat(100);

// Feature: student-record-system, Property 16: Year level validation enforcement
// For any year_level value outside the range 1-6, validation should reject it.
// Validates: Requirements 6.6

test('property: year level validation enforcement', function () {
    $invalidYearLevel = generateInvalidYearLevel();
    
    $data = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => $invalidYearLevel,
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('year_level'))->toBeTrue();
})->repeat(100);

// Feature: student-record-system, Property 17: Contact number validation enforcement
// For any provided contact_number that doesn't match the pattern (numeric, 10-15 digits),
// validation should reject it.
// Validates: Requirements 6.7

test('property: contact number validation enforcement', function () {
    $invalidContactNumber = generateInvalidContactNumber();
    
    $data = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => $invalidContactNumber,
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('contact_number'))->toBeTrue();
})->repeat(100);

// Feature: student-record-system, Property 18: Address validation enforcement
// For any provided address that doesn't meet the requirements (alphanumeric, 5-255 characters),
// validation should reject it.
// Validates: Requirements 6.8

test('property: address validation enforcement', function () {
    $invalidAddress = generateInvalidAddress();
    
    $data = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'address' => $invalidAddress,
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('address'))->toBeTrue();
})->repeat(100);
