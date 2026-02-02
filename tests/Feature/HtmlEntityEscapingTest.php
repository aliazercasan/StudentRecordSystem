<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 23: HTML entity escaping
// For any user-provided input containing HTML tags, when displayed in a view, the HTML should be escaped (not rendered as HTML).
// Validates: Requirements 9.3

test('property: html entity escaping', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Generate random HTML/XSS payloads to test escaping
    $xssPayloads = [
        '<script>alert("XSS")</script>',
        '<img src=x onerror=alert(1)>',
        '<svg onload=alert(1)>',
        '<iframe src="javascript:alert(1)">',
        '<body onload=alert(1)>',
        '"><script>alert(String.fromCharCode(88,83,83))</script>',
        '<div onclick="alert(1)">Click</div>',
        '<a href="javascript:alert(1)">Link</a>',
    ];
    
    $htmlTag = fake()->randomElement($xssPayloads);
    
    // Create a student with HTML tags in various fields
    $student = Student::create([
        'student_id' => generateValidStudentId(),
        'full_name' => 'John ' . $htmlTag . ' Doe',
        'course' => 'Computer Science ' . $htmlTag,
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->numerify('##########'),
        'address' => '123 Main St ' . $htmlTag,
    ]);
    
    // Test 1: Check student detail page
    $detailResponse = $this->actingAs($user)->get(route('students.show', $student));
    $detailResponse->assertStatus(200);
    $detailContent = $detailResponse->getContent();
    
    // Assert HTML is escaped in detail view
    $escapedTag = htmlspecialchars($htmlTag, ENT_QUOTES, 'UTF-8');
    expect(str_contains($detailContent, $escapedTag))->toBeTrue(
        "Expected escaped HTML in detail view: {$escapedTag}"
    );
    
    // Test 2: Check student list page
    $listResponse = $this->actingAs($user)->get(route('students.index'));
    $listResponse->assertStatus(200);
    $listContent = $listResponse->getContent();
    
    // Assert HTML is escaped in list view
    expect(str_contains($listContent, $escapedTag))->toBeTrue(
        "Expected escaped HTML in list view: {$escapedTag}"
    );
    
    // Test 3: Verify dangerous tags are NOT executable
    // Check for common XSS patterns that should be escaped
    $dangerousPatterns = ['<script', '<iframe', 'onerror=', 'onload=', 'onclick=', 'javascript:'];
    
    foreach ($dangerousPatterns as $pattern) {
        if (stripos($htmlTag, $pattern) !== false) {
            // If the original tag contains this pattern, verify it's escaped in output
            $escapedPattern = htmlspecialchars($pattern, ENT_QUOTES, 'UTF-8');
            
            // The dangerous pattern should appear in escaped form
            $hasEscapedForm = str_contains($detailContent, $escapedPattern) || 
                             str_contains($detailContent, strtolower($escapedPattern));
            
            expect($hasEscapedForm)->toBeTrue(
                "Expected dangerous pattern '{$pattern}' to be escaped"
            );
        }
    }
})->repeat(100);
