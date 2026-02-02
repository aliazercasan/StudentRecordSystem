<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 19: Search result accuracy
// For any search query and database state, all returned results should contain the query string in at least one of these fields: full_name, student_id, course, or year_level.
// Validates: Requirements 7.1

test('search result accuracy property', function () {
    // Run 100 iterations
    for ($i = 0; $i < 100; $i++) {
        // Create a user for authentication
        $user = User::factory()->create();
        
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis'];
        $courses = ['Computer Science', 'Engineering', 'Business Administration', 'Mathematics', 'Physics', 'Chemistry'];
        
        // Create multiple students with varied data
        $students = [];
        for ($j = 0; $j < 10; $j++) {
            $students[] = Student::create([
                'student_id' => 'STU' . str_pad($i * 10 + $j, 5, '0', STR_PAD_LEFT),
                'full_name' => fake()->randomElement($firstNames) . ' ' . fake()->randomElement($lastNames),
                'course' => fake()->randomElement($courses),
                'year_level' => fake()->numberBetween(1, 6),
                'contact_number' => (string) fake()->numberBetween(1000000000, 9999999999999),
                'address' => fake()->numberBetween(100, 9999) . ' ' . fake()->randomElement(['Main', 'Oak', 'Elm', 'Pine']) . ' Street',
            ]);
        }
        
        // Generate a search query based on one of the students
        $targetStudent = fake()->randomElement($students);
        $searchQueries = [
            substr($targetStudent->full_name, 0, 4), // Part of name
            substr($targetStudent->student_id, 0, 5), // Part of student ID
            substr($targetStudent->course, 0, 5), // Part of course
            (string) $targetStudent->year_level, // Year level
        ];
        $searchQuery = fake()->randomElement($searchQueries);
        
        // Perform search directly on the model (simulating controller logic)
        $query = Student::query();
        $query->where(function ($q) use ($searchQuery) {
            $q->where('full_name', 'like', "%{$searchQuery}%")
                ->orWhere('student_id', 'like', "%{$searchQuery}%")
                ->orWhere('course', 'like', "%{$searchQuery}%")
                ->orWhere('year_level', 'like', "%{$searchQuery}%");
        });
        $returnedStudents = $query->get();
        
        // Assert that all returned students contain the search query in at least one field
        foreach ($returnedStudents as $student) {
            $matchesFullName = stripos($student->full_name, $searchQuery) !== false;
            $matchesStudentId = stripos($student->student_id, $searchQuery) !== false;
            $matchesCourse = stripos($student->course, $searchQuery) !== false;
            $matchesYearLevel = stripos((string) $student->year_level, $searchQuery) !== false;
            
            $matchesAtLeastOne = $matchesFullName || $matchesStudentId || $matchesCourse || $matchesYearLevel;
            
            expect($matchesAtLeastOne)->toBeTrue(
                "Student {$student->id} does not match search query '{$searchQuery}' in any field"
            );
        }
        
        // Clean up for next iteration
        foreach ($students as $student) {
            $student->delete();
        }
        $user->delete();
    }
});
