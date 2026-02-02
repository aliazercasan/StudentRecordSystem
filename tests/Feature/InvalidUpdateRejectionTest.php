<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 9: Invalid update rejection
// For any student update with invalid data (violating validation rules), the update should be rejected with validation errors.
// Validates: Requirements 4.2

test('invalid update rejection property', function () {
    // Run 100 iterations
    for ($i = 0; $i < 100; $i++) {
        // Create a user for authentication
        $user = User::factory()->create();
        
        // Create a valid student
        $student = Student::create([
            'student_id' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT),
            'full_name' => 'John Doe',
            'course' => 'Computer Science',
            'year_level' => 1,
            'contact_number' => '1234567890',
            'address' => '123 Main Street',
        ]);
        
        // Generate invalid update data (randomly violate one validation rule)
        $invalidDataSets = [
            // Missing required field
            ['student_id' => '', 'full_name' => 'Jane Smith', 'course' => 'Mathematics', 'year_level' => 2],
            ['student_id' => 'UPD00001', 'full_name' => '', 'course' => 'Mathematics', 'year_level' => 2],
            ['student_id' => 'UPD00001', 'full_name' => 'Jane Smith', 'course' => '', 'year_level' => 2],
            ['student_id' => 'UPD00001', 'full_name' => 'Jane Smith', 'course' => 'Mathematics', 'year_level' => ''],
            
            // Invalid format
            ['student_id' => 'AB', 'full_name' => 'Jane Smith', 'course' => 'Mathematics', 'year_level' => 2], // Too short
            ['student_id' => 'UPD00001', 'full_name' => 'J', 'course' => 'Mathematics', 'year_level' => 2], // Too short
            ['student_id' => 'UPD00001', 'full_name' => 'Jane123', 'course' => 'Mathematics', 'year_level' => 2], // Contains numbers
            ['student_id' => 'UPD00001', 'full_name' => 'Jane Smith', 'course' => 'M', 'year_level' => 2], // Too short
            ['student_id' => 'UPD00001', 'full_name' => 'Jane Smith', 'course' => 'Math123', 'year_level' => 2], // Contains numbers
            ['student_id' => 'UPD00001', 'full_name' => 'Jane Smith', 'course' => 'Mathematics', 'year_level' => 0], // Out of range
            ['student_id' => 'UPD00001', 'full_name' => 'Jane Smith', 'course' => 'Mathematics', 'year_level' => 7], // Out of range
            ['student_id' => 'UPD00001', 'full_name' => 'Jane Smith', 'course' => 'Mathematics', 'year_level' => 2, 'contact_number' => '123'], // Too short
            ['student_id' => 'UPD00001', 'full_name' => 'Jane Smith', 'course' => 'Mathematics', 'year_level' => 2, 'address' => 'Abc'], // Too short
        ];
        
        $invalidData = fake()->randomElement($invalidDataSets);
        
        // Attempt to update with invalid data
        $response = $this->actingAs($user)->put(route('students.update', $student), $invalidData);
        
        // Assert that validation errors occurred (redirect back with errors)
        $response->assertSessionHasErrors();
        
        // Refresh the student and verify it was NOT updated
        $student->refresh();
        expect($student->student_id)->toBe('STU' . str_pad($i, 5, '0', STR_PAD_LEFT));
        expect($student->full_name)->toBe('John Doe');
        expect($student->course)->toBe('Computer Science');
        expect($student->year_level)->toBe(1);
        
        // Clean up for next iteration
        $student->delete();
        $user->delete();
    }
});
