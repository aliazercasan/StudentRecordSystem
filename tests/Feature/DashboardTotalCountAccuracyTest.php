<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 39: Dashboard total count accuracy
// For any database state, the dashboard should display the correct total count of students.
// Validates: Requirements 14.1

test('property: dashboard total count accuracy', function () {
    // Create a user for authentication
    $user = User::factory()->create();
    
    // Generate random number of students (0 to 50)
    $studentCount = fake()->numberBetween(0, 50);
    
    // Create students
    for ($i = 0; $i < $studentCount; $i++) {
        Student::create([
            'student_id' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT),
            'full_name' => generateValidFullName(),
            'course' => generateValidCourse(),
            'year_level' => fake()->numberBetween(1, 6),
        ]);
    }
    
    // Access the dashboard
    $response = $this->actingAs($user)->get(route('dashboard'));
    
    // Assert the response is successful
    $response->assertStatus(200);
    
    // Assert the total count is displayed correctly
    $response->assertSee((string) $studentCount);
    
    // Verify the actual count in the database
    $actualCount = Student::count();
    expect($actualCount)->toBe($studentCount);
})->repeat(100);
