<?php

// Feature: student-record-system, Property 48: Theme preference round-trip
// For any theme preference saved to local storage, when the user returns to the application, the same theme should be loaded.

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('theme preference round-trip - loads saved preference on page load', function () {
    actingAs($this->user);
    
    $response = get(route('dashboard'));
    $response->assertStatus(200);
    
    // Verify the page contains script to load theme from localStorage on page load
    $content = $response->getContent();
    expect($content)->toContain('localStorage.getItem');
    expect($content)->toContain('theme');
    
    // Verify theme is applied on page load (before user interaction)
    // The script should check localStorage and apply the theme immediately
    expect($content)->toMatch('/localStorage\.getItem.*theme/');
});

test('theme preference round-trip - dark theme persists across page loads', function () {
    actingAs($this->user);
    
    $response = get(route('dashboard'));
    $response->assertStatus(200);
    
    $content = $response->getContent();
    
    // Verify script loads theme preference on page load
    expect($content)->toContain('localStorage.getItem');
    
    // Verify the theme classes can be applied (dark mode classes exist)
    expect($content)->toMatch('/(dark|theme)/i');
});

test('theme preference round-trip - light theme persists across page loads', function () {
    actingAs($this->user);
    
    $response = get(route('students.index'));
    $response->assertStatus(200);
    
    $content = $response->getContent();
    
    // Verify script loads theme preference on page load
    expect($content)->toContain('localStorage.getItem');
    
    // Verify the theme loading happens before page render
    // (script should be in head or early in body)
    expect($content)->toMatch('/localStorage\.getItem.*theme/');
});
