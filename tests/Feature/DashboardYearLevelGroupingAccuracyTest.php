<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 40: Dashboard year level grouping accuracy
// For any database state, the dashboard should display accurate counts of students grouped by year_level.
// Validates: Requirements 14.2

test('property: dashboard year level grouping accuracy', function () {
    // Create a user for authentication
    $user = User::factory()->create();
    
    // Track expected counts per year level
    $expectedCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0];
    
    // Generate random number of students (5 to 30)
    $studentCount = fake()->numberBetween(5, 30);
    
    // Create students with random year levels
    for ($i = 0; $i < $studentCount; $i++) {
        $yearLevel = fake()->numberBetween(1, 6);
        $expectedCounts[$yearLevel]++;
        
        Student::create([
            'student_id' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT),
            'full_name' => generateValidFullName(),
            'course' => generateValidCourse(),
            'year_level' => $yearLevel,
        ]);
    }
    
    // Access the dashboard
    $response = $this->actingAs($user)->get(route('dashboard'));
    
    // Assert the response is successful
    $response->assertStatus(200);
    
    // Verify the counts in the database match expectations
    foreach ($expectedCounts as $yearLevel => $expectedCount) {
        $actualCount = Student::where('year_level', $yearLevel)->count();
        expect($actualCount)->toBe($expectedCount);
        
        // Assert the count is displayed in the response
        if ($expectedCount > 0) {
            $response->assertSee("Year $yearLevel");
            $response->assertSee((string) $expectedCount);
        }
    }
})->repeat(100);
