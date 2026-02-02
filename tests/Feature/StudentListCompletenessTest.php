<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 4: Student list completeness
// For any set of students in the database, the student list view should display all students across paginated pages.
// Validates: Requirements 2.1

test('property: student list completeness', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Generate a random number of students (between 1 and 50)
    $studentCount = fake()->numberBetween(1, 50);
    $createdStudents = [];
    
    // Create students with unique student IDs
    for ($i = 0; $i < $studentCount; $i++) {
        $createdStudents[] = Student::create([
            'student_id' => generateValidStudentId() . '_' . $i, // Ensure uniqueness
            'full_name' => generateValidFullName(),
            'course' => generateValidCourse(),
            'year_level' => fake()->numberBetween(1, 6),
        ]);
    }
    
    // Collect all student IDs from all paginated pages
    $displayedStudentIds = [];
    $page = 1;
    $hasMorePages = true;
    
    while ($hasMorePages) {
        $response = $this->actingAs($user)->get(route('students.index', ['page' => $page]));
        $response->assertStatus(200);
        
        // Extract student IDs from the response
        $content = $response->getContent();
        foreach ($createdStudents as $student) {
            if (str_contains($content, $student->student_id)) {
                $displayedStudentIds[$student->student_id] = true;
            }
        }
        
        // Check if there are more pages
        $students = Student::paginate(20, ['*'], 'page', $page);
        $hasMorePages = $students->hasMorePages();
        $page++;
        
        // Safety check to prevent infinite loops
        if ($page > 10) {
            break;
        }
    }
    
    // Assert all created students are displayed
    expect(count($displayedStudentIds))->toBe($studentCount);
    
    // Verify each student ID was found
    foreach ($createdStudents as $student) {
        expect($displayedStudentIds)->toHaveKey($student->student_id);
    }
})->repeat(100);
