<?php

use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 46: Activity log field completeness
// For any activity log entry, the display should include action_type, timestamp, administrator name, and student identifier.
// Validates: Requirements 15.5

test('activity log field completeness property', function () {
    // Run 100 iterations
    for ($i = 0; $i < 100; $i++) {
        // Create a user (administrator)
        $user = User::factory()->create([
            'name' => 'Admin ' . $i,
        ]);
        
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
        
        // Create an activity log entry
        $actionTypes = ['create', 'update', 'delete'];
        $actionType = fake()->randomElement($actionTypes);
        
        $activityLog = ActivityLog::create([
            'user_id' => $user->id,
            'action_type' => $actionType,
            'student_id' => $student->id,
            'record_details' => [
                'student_id' => $student->student_id,
                'full_name' => $student->full_name,
            ],
            'changed_fields' => $actionType === 'update' ? ['course' => 'New Course'] : null,
        ]);
        
        // Access the activity log page
        $response = $this->actingAs($user)->get(route('activity-logs.index'));
        
        $response->assertStatus(200);
        
        // Get the response content
        $content = $response->getContent();
        
        // Assert that the display includes all required fields:
        // 1. Action type
        expect($content)->toContain($activityLog->action_type);
        
        // 2. Timestamp (formatted)
        $timestamp = $activityLog->created_at->format('Y-m-d H:i:s');
        expect($content)->toContain($timestamp);
        
        // 3. Administrator name
        expect($content)->toContain($user->name);
        
        // 4. Student identifier
        expect($content)->toContain($student->student_id);
        
        // Clean up for next iteration
        $activityLog->delete();
        $student->delete();
        $user->delete();
    }
});
