<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 5: Student list field display
// For any student in the list view, the rendered HTML should contain the student's full_name, student_id, course, and year_level.
// Validates: Requirements 2.3

test('property: student list field display', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Generate a random number of students (between 1 and 25)
    $studentCount = fake()->numberBetween(1, 25);
    $createdStudents = [];
    
    // Create students with unique student IDs and random valid data
    for ($i = 0; $i < $studentCount; $i++) {
        $createdStudents[] = Student::create([
            'student_id' => generateValidStudentId() . '_' . uniqid(),
            'full_name' => generateValidFullName(),
            'course' => generateValidCourse(),
            'year_level' => fake()->numberBetween(1, 6),
        ]);
    }
    
    // Get the student list page
    $response = $this->actingAs($user)->get(route('students.index'));
    $response->assertStatus(200);
    
    // Get the HTML content
    $content = $response->getContent();
    
    // For each student on the first page (up to 20 students), verify all four fields are displayed
    $studentsOnFirstPage = array_slice($createdStudents, 0, min(20, $studentCount));
    
    foreach ($studentsOnFirstPage as $student) {
        // Assert that the student_id is present in the HTML
        expect($content)->toContain($student->student_id);
        
        // Assert that the full_name is present in the HTML (accounting for HTML entity encoding)
        $escapedFullName = htmlspecialchars($student->full_name, ENT_QUOTES, 'UTF-8');
        expect($content)->toContain($escapedFullName);
        
        // Assert that the course is present in the HTML
        expect($content)->toContain($student->course);
        
        // Assert that the year_level is present in the HTML
        expect($content)->toContain((string)$student->year_level);
    }
})->repeat(100);
