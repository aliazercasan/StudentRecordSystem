<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 31: Photo file validation
// For any uploaded file that is not an image (JPEG, PNG, GIF) or exceeds 2MB, validation should reject it.
// Validates: Requirements 12.2

test('property: photo file validation rejects non-image files', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Create a non-image file (text file)
    $file = UploadedFile::fake()->create('document.txt', 100);
    
    // Generate valid student data with invalid photo
    $studentData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'photo' => $file,
    ];
    
    // Submit the form
    $response = $this->actingAs($user)->post(route('students.store'), $studentData);
    
    // Assert validation failed for photo field
    $response->assertSessionHasErrors('photo');
})->repeat(100);

test('property: photo file validation rejects oversized files', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Create an image file larger than 2MB (2048 KB)
    // Using create() instead of image() to avoid GD extension requirement
    $file = UploadedFile::fake()->create('photo.jpg', 2049, 'image/jpeg');
    
    // Generate valid student data with oversized photo
    $studentData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'photo' => $file,
    ];
    
    // Submit the form
    $response = $this->actingAs($user)->post(route('students.store'), $studentData);
    
    // Assert validation failed for photo field
    $response->assertSessionHasErrors('photo');
})->repeat(100);
