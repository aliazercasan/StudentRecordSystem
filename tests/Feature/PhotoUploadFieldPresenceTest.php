<?php

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 30: Photo upload field presence
// For any student create or edit form, the rendered HTML should contain a file input field for photo upload.
// Validates: Requirements 12.1

beforeEach(function () {
    // Create and authenticate a user for all tests
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('property: create form contains photo upload field', function () {
    $response = $this->get(route('students.create'));
    
    $response->assertStatus(200);
    
    // Check for file input field with name="photo"
    $response->assertSee('type="file"', false);
    $response->assertSee('name="photo"', false);
    $response->assertSee('id="photo"', false);
    
    // Check for label
    $response->assertSee('Photo');
    
    // Check for accept attribute (image formats)
    $response->assertSee('accept="image/jpeg,image/png,image/gif"', false);
})->repeat(100);

test('property: edit form contains photo upload field', function () {
    // Create a random student
    $student = Student::factory()->create();
    
    $response = $this->get(route('students.edit', $student));
    
    $response->assertStatus(200);
    
    // Check for file input field with name="photo"
    $response->assertSee('type="file"', false);
    $response->assertSee('name="photo"', false);
    $response->assertSee('id="photo"', false);
    
    // Check for label
    $response->assertSee('Photo');
    
    // Check for accept attribute (image formats)
    $response->assertSee('accept="image/jpeg,image/png,image/gif"', false);
})->repeat(100);

test('property: create form has proper enctype for file upload', function () {
    $response = $this->get(route('students.create'));
    
    $response->assertStatus(200);
    
    // Check that form has enctype="multipart/form-data"
    $response->assertSee('enctype="multipart/form-data"', false);
})->repeat(100);

test('property: edit form has proper enctype for file upload', function () {
    // Create a random student
    $student = Student::factory()->create();
    
    $response = $this->get(route('students.edit', $student));
    
    $response->assertStatus(200);
    
    // Check that form has enctype="multipart/form-data"
    $response->assertSee('enctype="multipart/form-data"', false);
})->repeat(100);
