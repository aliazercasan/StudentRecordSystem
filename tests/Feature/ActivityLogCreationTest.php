<?php

use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 42: Activity log creation on student create
// For any student creation, an activity log entry should be created with action_type='create', the administrator's ID, and the student's details.
// Validates: Requirements 15.1

test('activity log creation on student create property', function () {
    // Run 100 iterations
    for ($i = 0; $i < 100; $i++) {
        // Create a user for authentication
        $user = User::factory()->create();
        
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis'];
        $courses = ['Computer Science', 'Engineering', 'Business Administration', 'Mathematics', 'Physics', 'Chemistry'];
        
        // Create student data
        $studentData = [
            'student_id' => 'STU' . str_pad($i, 5, '0', STR_PAD_LEFT),
            'full_name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
            'course' => fake()->randomElement($courses),
            'year_level' => fake()->numberBetween(1, 6),
            'contact_number' => (string) fake()->numberBetween(1000000000, 9999999999999),
            'address' => fake()->numberBetween(100, 9999) . ' ' . fake()->randomElement(['Main', 'Oak', 'Elm', 'Pine']) . ' Street',
        ];
        
        // Create the student via the controller
        $response = $this->actingAs($user)->post(route('students.store'), $studentData);
        
        // Get the created student
        $student = Student::where('student_id', $studentData['student_id'])->first();
        
        // Assert that an activity log was created
        $activityLog = ActivityLog::where('student_id', $student->id)
            ->where('action_type', 'create')
            ->where('user_id', $user->id)
            ->first();
        
        expect($activityLog)->not->toBeNull();
        expect($activityLog->action_type)->toBe('create');
        expect($activityLog->user_id)->toBe($user->id);
        expect($activityLog->student_id)->toBe($student->id);
        expect($activityLog->record_details)->toBeArray();
        expect($activityLog->record_details['student_id'])->toBe($studentData['student_id']);
        expect($activityLog->record_details['full_name'])->toBe($studentData['full_name']);
        
        // Clean up for next iteration
        $activityLog->delete();
        $student->delete();
        $user->delete();
    }
});

// Feature: student-record-system, Property 43: Activity log creation on student update
// For any student update, an activity log entry should be created with action_type='update', the administrator's ID, the student ID, and the changed fields.
// Validates: Requirements 15.2

test('activity log creation on student update property', function () {
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
        
        // Generate update data
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
        
        // Assert that an activity log was created
        $activityLog = ActivityLog::where('student_id', $student->id)
            ->where('action_type', 'update')
            ->where('user_id', $user->id)
            ->first();
        
        expect($activityLog)->not->toBeNull();
        expect($activityLog->action_type)->toBe('update');
        expect($activityLog->user_id)->toBe($user->id);
        expect($activityLog->student_id)->toBe($student->id);
        expect($activityLog->changed_fields)->toBeArray();
        expect($activityLog->changed_fields)->not->toBeEmpty();
        
        // Clean up for next iteration
        $activityLog->delete();
        $student->delete();
        $user->delete();
    }
});

// Feature: student-record-system, Property 44: Activity log creation on student delete
// For any student deletion, an activity log entry should be created with action_type='delete', the administrator's ID, and the deleted student's details.
// Validates: Requirements 15.3

test('activity log creation on student delete property', function () {
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
        
        $studentId = $student->id;
        $studentIdValue = $student->student_id;
        
        // Delete the student via the controller
        $response = $this->actingAs($user)->delete(route('students.destroy', $student));
        
        // Assert that an activity log was created
        $activityLog = ActivityLog::where('action_type', 'delete')
            ->where('user_id', $user->id)
            ->whereJsonContains('record_details->student_id', $studentIdValue)
            ->first();
        
        expect($activityLog)->not->toBeNull();
        expect($activityLog->action_type)->toBe('delete');
        expect($activityLog->user_id)->toBe($user->id);
        expect($activityLog->student_id)->toBeNull(); // Student is deleted, so reference is null
        expect($activityLog->record_details)->toBeArray();
        expect($activityLog->record_details['student_id'])->toBe($studentIdValue);
        
        // Clean up for next iteration
        $activityLog->delete();
        $user->delete();
    }
});
