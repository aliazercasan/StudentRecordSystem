<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

/**
 * Feature: student-record-system, Property 27: Logout session termination
 * 
 * For any authenticated administrator, after logging out, the session should 
 * be terminated and subsequent requests should require re-authentication.
 * 
 * Validates: Requirements 10.4
 */
test('property: logout terminates session and requires re-authentication', function () {
    // Run property test 20 times with random data
    for ($i = 0; $i < 20; $i++) {
        // Generate random user credentials
        $password = fake()->password(8, 20);
        $user = User::factory()->create([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make($password),
        ]);
        
        // Login the user
        $loginResponse = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);
        
        // Verify user is authenticated
        $this->assertAuthenticatedAs($user);
        
        // Access a protected route to verify session is active
        $dashboardResponse = $this->get(route('dashboard'));
        $dashboardResponse->assertOk();
        
        // Logout the user
        $logoutResponse = $this->post(route('logout'));
        
        // Should redirect to login page
        $logoutResponse->assertRedirect(route('login'));
        
        // Verify user is no longer authenticated
        $this->assertGuest();
        
        // Attempt to access protected route after logout
        $protectedResponse = $this->get(route('dashboard'));
        
        // Should redirect to login page (requires re-authentication)
        $protectedResponse->assertRedirect(route('login'));
        
        // Verify still not authenticated
        $this->assertGuest();
    }
});

test('property: logout invalidates session token', function () {
    // Run property test 20 times with random data
    for ($i = 0; $i < 20; $i++) {
        // Generate random user credentials
        $password = fake()->password(8, 20);
        $user = User::factory()->create([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make($password),
        ]);
        
        // Login the user
        $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);
        
        // Verify user is authenticated
        $this->assertAuthenticatedAs($user);
        
        // Get the session token before logout
        $sessionTokenBefore = session()->token();
        
        // Logout the user
        $this->post(route('logout'));
        
        // Get the session token after logout
        $sessionTokenAfter = session()->token();
        
        // Session token should be regenerated (different from before)
        expect($sessionTokenBefore)->not->toBe($sessionTokenAfter);
        
        // Verify user is no longer authenticated
        $this->assertGuest();
    }
});
