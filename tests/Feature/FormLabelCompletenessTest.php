<?php

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 29: Form label completeness
// For any form in the application, all input fields should have associated label elements.

test('all forms have labels for all input fields', function () {
    $user = User::factory()->create();
    
    // Test student create form
    $response = $this->actingAs($user)->get(route('students.create'));
    $response->assertStatus(200);
    $html = $response->getContent();
    
    // Check that all input fields have associated labels
    assertFormHasLabelsForInputs($html, [
        'student_id',
        'full_name',
        'course',
        'year_level',
        'contact_number',
        'address',
        'photo',
    ]);
    
    // Test student edit form
    $student = Student::factory()->create();
    $response = $this->actingAs($user)->get(route('students.edit', $student));
    $response->assertStatus(200);
    $html = $response->getContent();
    
    assertFormHasLabelsForInputs($html, [
        'student_id',
        'full_name',
        'course',
        'year_level',
        'contact_number',
        'address',
        'photo',
    ]);
    
    // Test login form
    $response = $this->get(route('login'));
    $response->assertStatus(200);
    $html = $response->getContent();
    
    // Login form should have labels for email and password
    assertFormHasLabelsForInputs($html, [
        'email',
        'password',
    ]);
});

/**
 * Helper function to assert that a form has labels for all specified input fields
 */
function assertFormHasLabelsForInputs(string $html, array $fieldNames): void
{
    foreach ($fieldNames as $fieldName) {
        // Check for label with for attribute matching the field name
        $hasLabel = preg_match('/<label[^>]+for=["\']' . preg_quote($fieldName, '/') . '["\'][^>]*>/', $html);
        
        expect($hasLabel)->toBeGreaterThan(0, "Form should have a label for field: {$fieldName}");
        
        // Check that the input/select/textarea field exists
        $hasInput = preg_match('/<(input|select|textarea)[^>]+(?:name|id)=["\']' . preg_quote($fieldName, '/') . '["\']/', $html);
        
        expect($hasInput)->toBeGreaterThan(0, "Form should have an input field: {$fieldName}");
    }
}
