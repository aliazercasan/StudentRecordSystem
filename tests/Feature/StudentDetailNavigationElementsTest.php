<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 7: Student detail navigation elements
// For any student detail page, the rendered HTML should contain edit and delete action buttons.
// Validates: Requirements 3.4

test('property: student detail navigation elements', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Create a student with random valid data
    $student = Student::create([
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->optional()->numerify('##########'),
        'address' => fake()->optional()->address(),
    ]);
    
    // Get the student detail page
    $response = $this->actingAs($user)->get(route('students.show', $student));
    $response->assertStatus(200);
    
    // Assert edit button/link is present
    $response->assertSee(route('students.edit', $student), false);
    $response->assertSee('Edit');
    
    // Assert delete button/form is present
    $response->assertSee(route('students.destroy', $student), false);
    $response->assertSee('Delete');
    
    // Verify the HTML contains the expected navigation elements
    $html = $response->getContent();
    
    // Check for edit link
    expect($html)->toContain('href="' . route('students.edit', $student) . '"');
    
    // Check for delete form with DELETE method
    expect($html)->toContain('action="' . route('students.destroy', $student) . '"');
    expect($html)->toContain('_method');
})->repeat(100);
