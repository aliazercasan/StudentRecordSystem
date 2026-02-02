<?php

use App\Models\User;
use Eris\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

/**
 * Feature: student-record-system, Property 25: Valid login success
 * 
 * For any valid administrator credentials, submitting them to the login 
 * form should authenticate the user and create a session.
 * 
 * Validates: Requirements 10.2
 */
test('property: valid credentials authenticate user and create session', function () {
    // Run property test 20 times with random data
    for ($i = 0; $i < 20; $i++) {
        // Generate random credentials
        $name = fake()->name();
        $password = fake()->password(8, 20);
        
        // Create a user with the generated credentials
        $email = fake()->unique()->safeEmail();
        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        
        // Ensure we're not authenticated before login
        $this->assertGuest();
        
        // Attempt to login with valid credentials
        $response = $this->post(route('login'), [
            'email' => $email,
            'password' => $password,
        ]);
        
        // Should redirect to dashboard (intended route)
        $response->assertRedirect(route('dashboard'));
        
        // Should be authenticated
        $this->assertAuthenticatedAs($user);
        
        // Logout for next iteration
        $this->post(route('logout'));
    }
});
