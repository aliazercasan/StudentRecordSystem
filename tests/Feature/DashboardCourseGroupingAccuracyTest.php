<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 41: Dashboard course grouping accuracy
// For any database state, the dashboard should display accurate counts of students grouped by course.
// Validates: Requirements 14.3

test('property: dashboard course grouping accuracy', function () {
    // Create a user for authentication
    $user = User::factory()->create();
    
    // Define possible courses
    $courses = [
        'Computer Science',
        'Information Technology',
        'Business Administration',
        'Engineering',
        'Nursing',
    ];
    
    // Track expected counts per course
    $expectedCounts = [];
    
    // Generate random number of students (5 to 30)
    $studentCount = fake()->numberBetween(5, 30);
    
    // Create students with random courses
    for ($i = 0; $i < $studentCount; $i++) {
        $course = fake()->randomElement($courses);
        
        if (!isset($expectedCounts[$course])) {
            $expectedCounts[$course] = 0;
        }
        $expectedCounts[$course]++;
        
        Student::create([
            'student_id' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT),
            'full_name' => generateValidFullName(),
            'course' => $course,
            'year_level' => fake()->numberBetween(1, 6),
        ]);
    }
    
    // Access the dashboard
    $response = $this->actingAs($user)->get(route('dashboard'));
    
    // Assert the response is successful
    $response->assertStatus(200);
    
    // Verify the counts in the database match expectations
    foreach ($expectedCounts as $course => $expectedCount) {
        $actualCount = Student::where('course', $course)->count();
        expect($actualCount)->toBe($expectedCount);
        
        // Assert the course and count are displayed in the response
        $response->assertSee($course);
        $response->assertSee((string) $expectedCount);
    }
})->repeat(100);
