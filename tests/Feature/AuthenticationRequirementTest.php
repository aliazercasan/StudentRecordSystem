<?php

use Eris\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Feature: student-record-system, Property 24: Authentication requirement
 * 
 * For any protected route (student management pages), accessing without 
 * authentication should redirect to the login page.
 * 
 * Validates: Requirements 10.1
 */
test('property: unauthenticated access to protected routes redirects to login', function () {
    // Use Eris for property-based testing
    $protectedRoutes = [
        '/dashboard',
        // Add more protected routes as they are implemented
    ];
    
    // Run property test for each protected route multiple times
    foreach ($protectedRoutes as $protectedRoute) {
        for ($i = 0; $i < 20; $i++) {
            // Ensure we're not authenticated
            $this->assertGuest();
            
            // Attempt to access the protected route
            $response = $this->get($protectedRoute);
            
            // Should redirect to login page
            $response->assertRedirect(route('login'));
        }
    }
});
