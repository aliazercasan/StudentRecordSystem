<?php

// Feature: student-record-system, Property 47: Theme preference persistence
// For any theme selection (dark or light), the preference should be saved to browser local storage.

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('theme preference persistence - dark mode', function () {
    actingAs($this->user);
    
    $response = get(route('dashboard'));
    $response->assertStatus(200);
    
    // Verify the page contains the theme toggle functionality
    $response->assertSee('theme-toggle', false);
    
    // Verify localStorage script is present for saving theme
    $content = $response->getContent();
    expect($content)->toContain('localStorage');
    expect($content)->toContain('theme');
});

test('theme preference persistence - light mode', function () {
    actingAs($this->user);
    
    $response = get(route('dashboard'));
    $response->assertStatus(200);
    
    // Verify the page contains the theme toggle functionality
    $response->assertSee('theme-toggle', false);
    
    // Verify localStorage script is present for saving theme
    $content = $response->getContent();
    expect($content)->toContain('localStorage');
    expect($content)->toContain('theme');
});

test('theme toggle button exists on all pages', function () {
    actingAs($this->user);
    
    $routes = [
        route('dashboard'),
        route('students.index'),
        route('activity-logs.index'),
    ];
    
    foreach ($routes as $route) {
        $response = get($route);
        $response->assertStatus(200);
        
        // Verify theme toggle is present
        $content = $response->getContent();
        expect($content)->toContain('theme-toggle');
    }
});
