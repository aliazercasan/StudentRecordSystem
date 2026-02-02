<?php

use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 28: Navigation consistency
// For any page in the application, the rendered HTML should contain the same navigation structure.

test('navigation structure is consistent across all pages', function () {
    $user = User::factory()->create();
    
    // Define all protected routes to test
    $routes = [
        ['method' => 'get', 'url' => route('dashboard')],
        ['method' => 'get', 'url' => route('students.index')],
        ['method' => 'get', 'url' => route('students.create')],
        ['method' => 'get', 'url' => route('activity-logs.index')],
    ];
    
    // Create a student for detail and edit pages
    $student = Student::factory()->create();
    $routes[] = ['method' => 'get', 'url' => route('students.show', $student)];
    $routes[] = ['method' => 'get', 'url' => route('students.edit', $student)];
    
    $navigationElements = [];
    
    foreach ($routes as $route) {
        $response = $this->actingAs($user)->{$route['method']}($route['url']);
        $response->assertStatus(200);
        
        $html = $response->getContent();
        
        // Extract navigation structure
        $hasNavbar = str_contains($html, '<nav') && str_contains($html, 'navbar');
        $hasDashboardLink = str_contains($html, route('dashboard'));
        $hasStudentsLink = str_contains($html, route('students.index'));
        $hasActivityLogsLink = str_contains($html, route('activity-logs.index'));
        $hasLogoutButton = str_contains($html, route('logout'));
        $hasThemeToggle = str_contains($html, 'theme-toggle');
        
        $navStructure = [
            'navbar' => $hasNavbar,
            'dashboard_link' => $hasDashboardLink,
            'students_link' => $hasStudentsLink,
            'activity_logs_link' => $hasActivityLogsLink,
            'logout_button' => $hasLogoutButton,
            'theme_toggle' => $hasThemeToggle,
        ];
        
        $navigationElements[] = $navStructure;
    }
    
    // Verify all pages have the same navigation structure
    $firstNav = $navigationElements[0];
    foreach ($navigationElements as $nav) {
        expect($nav)->toBe($firstNav);
    }
    
    // Verify all required navigation elements are present
    expect($firstNav['navbar'])->toBeTrue();
    expect($firstNav['dashboard_link'])->toBeTrue();
    expect($firstNav['students_link'])->toBeTrue();
    expect($firstNav['activity_logs_link'])->toBeTrue();
    expect($firstNav['logout_button'])->toBeTrue();
    expect($firstNav['theme_toggle'])->toBeTrue();
});
