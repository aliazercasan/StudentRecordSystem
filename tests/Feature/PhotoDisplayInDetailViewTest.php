<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 33: Photo display in detail view
// For any student with a photo_path, the detail view should contain an img element with the photo URL.
// Validates: Requirements 12.4

test('property: photo display in detail view', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Create a student with a photo path
    $photoPath = 'photos/' . fake()->uuid() . '.jpg';
    $student = Student::create([
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'photo_path' => $photoPath,
    ]);
    
    // Get the student detail page
    $response = $this->actingAs($user)->get(route('students.show', $student));
    $response->assertStatus(200);
    
    $content = $response->getContent();
    
    // Assert img element is present
    expect(str_contains($content, '<img'))->toBeTrue();
    
    // Assert the photo path or URL is referenced in the HTML
    expect(str_contains($content, $photoPath) || 
           str_contains($content, 'storage/' . $photoPath))->toBeTrue();
})->repeat(100);
