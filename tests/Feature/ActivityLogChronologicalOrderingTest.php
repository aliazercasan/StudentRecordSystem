<?php

use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 45: Activity log chronological ordering
// For any set of activity logs, the activity log page should display them in reverse chronological order (newest first).
// Validates: Requirements 15.4

test('activity log chronological ordering property', function () {
    // Run 100 iterations
    for ($i = 0; $i < 100; $i++) {
        // Create a user (administrator)
        $user = User::factory()->create();
        
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis'];
        $courses = ['Computer Science', 'Engineering', 'Business Administration', 'Mathematics', 'Physics', 'Chemistry'];
        
        // Create multiple students and activity logs with different timestamps
        $numLogs = fake()->numberBetween(3, 10);
        $createdLogs = [];
        
        for ($j = 0; $j < $numLogs; $j++) {
            // Create a student
            $student = Student::create([
                'student_id' => 'STU' . str_pad($i * 100 + $j, 5, '0', STR_PAD_LEFT),
                'full_name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
                'course' => fake()->randomElement($courses),
                'year_level' => fake()->numberBetween(1, 6),
                'contact_number' => (string) fake()->numberBetween(1000000000, 9999999999999),
                'address' => fake()->numberBetween(100, 9999) . ' ' . fake()->randomElement(['Main', 'Oak', 'Elm', 'Pine']) . ' Street',
            ]);
            
            // Create an activity log with a specific timestamp
            // Use different timestamps to ensure ordering
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
            
            // Manually set created_at to ensure different timestamps
            // Add seconds to make them distinct
            $activityLog->created_at = now()->subSeconds($numLogs - $j);
            $activityLog->save();
            
            $createdLogs[] = [
                'log' => $activityLog,
                'student' => $student,
            ];
        }
        
        // Access the activity log page
        $response = $this->actingAs($user)->get(route('activity-logs.index'));
        
        $response->assertStatus(200);
        
        // Get the activity logs from the response
        $activityLogs = $response->viewData('activityLogs');
        
        // Assert that logs are in reverse chronological order (newest first)
        $previousTimestamp = null;
        foreach ($activityLogs as $log) {
            if ($previousTimestamp !== null) {
                // Each log should have a created_at that is less than or equal to the previous one
                expect($log->created_at->timestamp)->toBeLessThanOrEqual($previousTimestamp);
            }
            $previousTimestamp = $log->created_at->timestamp;
        }
        
        // Clean up for next iteration
        foreach ($createdLogs as $item) {
            $item['log']->delete();
            $item['student']->delete();
        }
        $user->delete();
    }
});
