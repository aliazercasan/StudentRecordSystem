<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 8: Student update persistence
// For any existing student and any valid modified data, after updating the student, querying the database should return the updated data.
// Validates: Requirements 4.1, 4.3

test('student update persistence property', function () {
    // Run 100 iterations
    for ($i = 0; $i < 100; $i++) {
        // Create a user for authentication
        $user = User::factory()->create();
        
        // Generate valid names (only letters and spaces)
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis'];
        $courses = ['Computer Science', 'Engineering', 'Business Administration', 'Mathematics', 'Physics', 'Chemistry'];
        
        // Create a student with random valid data
        $student = Student::create([
            'student_id' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT),
            'full_name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
            'course' => fake()->randomElement($courses),
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => (string) fake()->numberBetween(1000000000, 9999999999999),
            'address' => fake()->numberBetween(100, 9999) . ' ' . fake()->randomElement(['Main', 'Oak', 'Elm', 'Pine']) . ' Street',
        ]);
        
        // Generate random valid update data
        $updateData = [
            'student_id' => 'UPD' . str_pad($i, 5, '0', STR_PAD_LEFT),
            'full_name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
            'course' => fake()->randomElement($courses),
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => (string) fake()->numberBetween(1000000000, 9999999999999),
            'address' => fake()->numberBetween(100, 9999) . ' ' . fake()->randomElement(['First', 'Second', 'Third', 'Fourth']) . ' Avenue',
        ];
        
        // Update the student via the controller
        $response = $this->actingAs($user)->put(route('students.update', $student), $updateData);
        
        // Refresh the student model from database
        $student->refresh();
        
        // Assert that the database contains the updated data
        expect($student->student_id)->toBe($updateData['student_id']);
        expect($student->full_name)->toBe($updateData['full_name']);
        expect($student->course)->toBe($updateData['course']);
        expect($student->year_level)->toBe($updateData['year_level']);
        expect($student->contact_number)->toBe($updateData['contact_number']);
        expect($student->address)->toBe($updateData['address']);
        
        // Clean up for next iteration
        $student->delete();
        $user->delete();
    }
});
