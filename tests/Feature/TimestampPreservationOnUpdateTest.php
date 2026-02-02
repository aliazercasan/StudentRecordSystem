<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 10: Timestamp preservation on update
// For any student update, the created_at timestamp should remain unchanged while the updated_at timestamp should be modified.
// Validates: Requirements 4.4

test('timestamp preservation on update property', function () {
    // Run 100 iterations
    for ($i = 0; $i < 100; $i++) {
        // Create a user for authentication
        $user = User::factory()->create();
        
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis'];
        $courses = ['Computer Science', 'Engineering', 'Business Administration', 'Mathematics', 'Physics', 'Chemistry'];
        
        // Create a student
        $student = Student::create([
            'student_id' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT),
            'full_name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
            'course' => fake()->randomElement($courses),
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => (string) fake()->numberBetween(1000000000, 9999999999999),
            'address' => fake()->numberBetween(100, 9999) . ' ' . fake()->randomElement(['Main', 'Oak', 'Elm', 'Pine']) . ' Street',
        ]);
        
        // Store the original created_at timestamp
        $originalCreatedAt = $student->created_at;
        $originalUpdatedAt = $student->updated_at;
        
        // Wait a moment to ensure timestamps would differ
        sleep(1);
        
        // Generate update data
        $updateData = [
            'student_id' => 'UPD' . str_pad($i, 5, '0', STR_PAD_LEFT),
            'full_name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
            'course' => fake()->randomElement($courses),
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => (string) fake()->numberBetween(1000000000, 9999999999999),
            'address' => fake()->numberBetween(100, 9999) . ' ' . fake()->randomElement(['First', 'Second', 'Third', 'Fourth']) . ' Avenue',
        ];
        
        // Update the student
        $response = $this->actingAs($user)->put(route('students.update', $student), $updateData);
        
        // Refresh the student
        $student->refresh();
        
        // Assert that created_at is unchanged
        expect($student->created_at->timestamp)->toBe($originalCreatedAt->timestamp);
        
        // Assert that updated_at has changed
        expect($student->updated_at->timestamp)->toBeGreaterThan($originalUpdatedAt->timestamp);
        
        // Clean up for next iteration
        $student->delete();
        $user->delete();
    }
});
