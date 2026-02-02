<?php

use App\Http\Requests\StoreStudentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 2: Invalid student rejection
// For any student data missing one or more required fields (student_id, full_name, course, year_level),
// attempting to create a student record should be rejected with validation errors.
// Validates: Requirements 1.2

test('property: invalid student rejection - missing student_id', function () {
    $data = [
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('student_id'))->toBeTrue();
})->repeat(100);

test('property: invalid student rejection - missing full_name', function () {
    $data = [
        'student_id' => generateValidStudentId(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('full_name'))->toBeTrue();
})->repeat(100);

test('property: invalid student rejection - missing course', function () {
    $data = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'year_level' => fake()->numberBetween(1, 6),
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('course'))->toBeTrue();
})->repeat(100);

test('property: invalid student rejection - missing year_level', function () {
    $data = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
    ];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('year_level'))->toBeTrue();
})->repeat(100);

test('property: invalid student rejection - missing all required fields', function () {
    $data = [];
    
    $request = new StoreStudentRequest();
    $validator = Validator::make($data, $request->rules());
    
    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('student_id'))->toBeTrue();
    expect($validator->errors()->has('full_name'))->toBeTrue();
    expect($validator->errors()->has('course'))->toBeTrue();
    expect($validator->errors()->has('year_level'))->toBeTrue();
})->repeat(100);
