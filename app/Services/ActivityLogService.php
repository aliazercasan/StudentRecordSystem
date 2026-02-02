<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;

class ActivityLogService
{
    /**
     * Log a student creation action.
     *
     * @param User $user The user who performed the action
     * @param Student $student The student that was created
     * @return ActivityLog
     */
    public function logCreate(User $user, Student $student): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $user->id,
            'action_type' => 'create',
            'student_id' => $student->id,
            'record_details' => [
                'student_id' => $student->student_id,
                'full_name' => $student->full_name,
                'course' => $student->course,
                'year_level' => $student->year_level,
                'contact_number' => $student->contact_number,
                'address' => $student->address,
                'photo_path' => $student->photo_path,
            ],
            'changed_fields' => null,
        ]);
    }

    /**
     * Log a student update action.
     *
     * @param User $user The user who performed the action
     * @param Student $student The student that was updated
     * @param array $changes The fields that were changed
     * @return ActivityLog
     */
    public function logUpdate(User $user, Student $student, array $changes): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $user->id,
            'action_type' => 'update',
            'student_id' => $student->id,
            'record_details' => [
                'student_id' => $student->student_id,
                'full_name' => $student->full_name,
                'course' => $student->course,
                'year_level' => $student->year_level,
                'contact_number' => $student->contact_number,
                'address' => $student->address,
                'photo_path' => $student->photo_path,
            ],
            'changed_fields' => $changes,
        ]);
    }

    /**
     * Log a student deletion action.
     *
     * @param User $user The user who performed the action
     * @param Student $student The student that was deleted
     * @return ActivityLog
     */
    public function logDelete(User $user, Student $student): ActivityLog
    {
        return ActivityLog::create([
            'user_id' => $user->id,
            'action_type' => 'delete',
            'student_id' => null, // Student will be deleted, so we don't reference it
            'record_details' => [
                'student_id' => $student->student_id,
                'full_name' => $student->full_name,
                'course' => $student->course,
                'year_level' => $student->year_level,
                'contact_number' => $student->contact_number,
                'address' => $student->address,
                'photo_path' => $student->photo_path,
            ],
            'changed_fields' => null,
        ]);
    }
}
