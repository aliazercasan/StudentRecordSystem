<?php

use App\Models\User;
use Eris\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

/**
 * Feature: student-record-system, Property 26: Invalid login rejection
 * 
 * For any invalid administrator credentials, submitting them to the login 
 * form should reject the login and display an error.
 * 
 * Validates: Requirements 10.3
 */
test('property: invalid credentials reject login with error', function () {
    // Run property test 20 times with random data
    for ($i = 0; $i < 20; $i++) {
        // Create a user with known credentials
        $correctPassword = 'correct_password_123';
        $wrongPassword = fake()->password(8, 20);
        
        // Ensure the wrong password is different from the correct one
        if ($wrongPassword === $correctPassword) {
            $wrongPassword = $correctPassword . '_wrong';
        }
        
        $user = User::factory()->create([
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make($correctPassword),
        ]);
        
        // Ensure we're not authenticated before login attempt
        $this->assertGuest();
        
        // Attempt to login with invalid credentials
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $wrongPassword,
        ]);
        
        // Should redirect back to login form
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        
        // Should remain unauthenticated
        $this->assertGuest();
    }
});

test('property: non-existent email rejects login with error', function () {
    // Run property test 20 times with random data
    for ($i = 0; $i < 20; $i++) {
        // Generate a random email that doesn't exist in the database
        $nonExistentEmail = 'nonexistent_' . fake()->unique()->safeEmail();
        $password = fake()->password(8, 20);
        
        // Ensure we're not authenticated before login attempt
        $this->assertGuest();
        
        // Attempt to login with non-existent email
        $response = $this->post(route('login'), [
            'email' => $nonExistentEmail,
            'password' => $password,
        ]);
        
        // Should redirect back to login form
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        
        // Should remain unauthenticated
        $this->assertGuest();
    }
});
