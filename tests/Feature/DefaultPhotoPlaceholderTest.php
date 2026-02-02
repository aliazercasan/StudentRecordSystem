<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 34: Default photo placeholder
// For any student without a photo_path, the detail view should display a default placeholder image.
// Validates: Requirements 12.5

test('property: default photo placeholder', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Create a student without a photo path
    $student = Student::create([
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'photo_path' => null, // No photo
    ]);
    
    // Get the student detail page
    $response = $this->actingAs($user)->get(route('students.show', $student));
    $response->assertStatus(200);
    
    $content = $response->getContent();
    
    // Assert img element is present (for placeholder)
    expect(str_contains($content, '<img'))->toBeTrue();
    
    // Assert placeholder image is referenced (common patterns)
    expect(str_contains($content, 'placeholder') || 
           str_contains($content, 'default') ||
           str_contains($content, 'avatar') ||
           str_contains($content, 'no-photo'))->toBeTrue();
})->repeat(100);
