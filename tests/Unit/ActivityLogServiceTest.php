<?php

use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new ActivityLogService();
    $this->user = User::factory()->create();
});

test('logCreate method creates activity log with correct data', function () {
    $student = Student::create([
        'student_id' => 'STU12345',
        'full_name' => 'John Doe',
        'course' => 'Computer Science',
        'year_level' => 2,
        'contact_number' => '1234567890',
        'address' => '123 Main Street',
    ]);

    $log = $this->service->logCreate($this->user, $student);

    expect($log)->toBeInstanceOf(ActivityLog::class)
        ->and($log->user_id)->toBe($this->user->id)
        ->and($log->action_type)->toBe('create')
        ->and($log->student_id)->toBe($student->id)
        ->and($log->record_details)->toBeArray()
        ->and($log->record_details['student_id'])->toBe('STU12345')
        ->and($log->record_details['full_name'])->toBe('John Doe')
        ->and($log->record_details['course'])->toBe('Computer Science')
        ->and($log->record_details['year_level'])->toBe(2)
        ->and($log->changed_fields)->toBeNull();

    // Verify it was persisted to database
    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $this->user->id,
        'action_type' => 'create',
        'student_id' => $student->id,
    ]);
});

test('logUpdate method creates activity log with changed fields', function () {
    $student = Student::create([
        'student_id' => 'STU12345',
        'full_name' => 'John Doe',
        'course' => 'Computer Science',
        'year_level' => 2,
    ]);

    $changes = [
        'course' => ['old' => 'Computer Science', 'new' => 'Information Technology'],
        'year_level' => ['old' => 2, 'new' => 3],
    ];

    $log = $this->service->logUpdate($this->user, $student, $changes);

    expect($log)->toBeInstanceOf(ActivityLog::class)
        ->and($log->user_id)->toBe($this->user->id)
        ->and($log->action_type)->toBe('update')
        ->and($log->student_id)->toBe($student->id)
        ->and($log->record_details)->toBeArray()
        ->and($log->changed_fields)->toBeArray()
        ->and($log->changed_fields)->toBe($changes);

    // Verify it was persisted to database
    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $this->user->id,
        'action_type' => 'update',
        'student_id' => $student->id,
    ]);
});

test('logDelete method creates activity log without student_id reference', function () {
    $student = Student::create([
        'student_id' => 'STU12345',
        'full_name' => 'John Doe',
        'course' => 'Computer Science',
        'year_level' => 2,
    ]);

    $log = $this->service->logDelete($this->user, $student);

    expect($log)->toBeInstanceOf(ActivityLog::class)
        ->and($log->user_id)->toBe($this->user->id)
        ->and($log->action_type)->toBe('delete')
        ->and($log->student_id)->toBeNull() // Should be null since student will be deleted
        ->and($log->record_details)->toBeArray()
        ->and($log->record_details['student_id'])->toBe('STU12345')
        ->and($log->record_details['full_name'])->toBe('John Doe')
        ->and($log->changed_fields)->toBeNull();

    // Verify it was persisted to database
    $this->assertDatabaseHas('activity_logs', [
        'user_id' => $this->user->id,
        'action_type' => 'delete',
        'student_id' => null,
    ]);
});
