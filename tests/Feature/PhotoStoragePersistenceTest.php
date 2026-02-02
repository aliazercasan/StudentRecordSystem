<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

// Feature: student-record-system, Property 32: Photo storage persistence
// For any valid photo upload, the file should exist in the storage directory and the file path should be saved in the database.
// Validates: Requirements 12.3

beforeEach(function () {
    // Ensure the public disk is using fake storage for testing
    Storage::fake('public');
});

test('property: photo storage persistence on student creation', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Create a fake image file (using create() to avoid GD extension requirement)
    $photo = UploadedFile::fake()->create('student_photo.jpg', 100, 'image/jpeg');
    
    // Generate valid student data with photo
    $address = fake()->address();
    // Ensure address is within validation limits (5-255 chars)
    if (strlen($address) > 255) {
        $address = substr($address, 0, 255);
    }
    
    $studentData = [
        'student_id' => generateValidStudentId(),
        'full_name' => generateValidFullName(),
        'course' => generateValidCourse(),
        'year_level' => fake()->numberBetween(1, 6),
        'contact_number' => fake()->numerify('##########'),
        'address' => $address,
        'photo' => $photo,
    ];
    
    // Submit the form to create student
    $response = $this->actingAs($user)->post(route('students.store'), $studentData);
    
    // Assert the student was created successfully (should redirect)
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
    
    // Get the created student from database
    $student = Student::where('student_id', $studentData['student_id'])->first();
    
    // Assert student exists and has photo_path
    expect($student)->not->toBeNull();
    expect($student->photo_path)->not->toBeNull();
    expect($student->photo_path)->not->toBe('');
    
    // Assert the file exists in storage
    Storage::disk('public')->assertExists($student->photo_path);
})->repeat(100);

test('property: photo storage persistence on student update', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Create a student without photo first
    $student = Student::factory()->create([
        'photo_path' => null,
    ]);
    
    // Create a fake image file (using create() to avoid GD extension requirement)
    $photo = UploadedFile::fake()->create('updated_photo.jpg', 100, 'image/jpeg');
    
    // Generate update data with photo
    $updateData = [
        'student_id' => $student->student_id,
        'full_name' => $student->full_name,
        'course' => $student->course,
        'year_level' => $student->year_level,
        'contact_number' => $student->contact_number,
        'address' => $student->address,
        'photo' => $photo,
    ];
    
    // Submit the update form
    $response = $this->actingAs($user)->put(route('students.update', $student), $updateData);
    
    // Assert the update was successful
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
    
    // Refresh the student from database
    $student->refresh();
    
    // Assert student has photo_path
    expect($student->photo_path)->not->toBeNull();
    expect($student->photo_path)->not->toBe('');
    
    // Assert the file exists in storage
    Storage::disk('public')->assertExists($student->photo_path);
})->repeat(100);

test('property: photo storage persistence replaces old photo on update', function () {
    // Create an authenticated user
    $user = User::factory()->create();
    
    // Create a student with an existing photo
    // First, manually store a photo file in fake storage
    Storage::disk('public')->put('photos/old_photo.jpg', 'fake photo content');
    $oldPhotoPath = 'photos/old_photo.jpg';
    
    $student = Student::factory()->create([
        'photo_path' => $oldPhotoPath,
    ]);
    
    // Verify old photo exists before update
    Storage::disk('public')->assertExists($oldPhotoPath);
    
    // Create a new fake image file
    $newPhoto = UploadedFile::fake()->create('new_photo.jpg', 100, 'image/jpeg');
    
    // Generate update data with new photo
    $updateData = [
        'student_id' => $student->student_id,
        'full_name' => $student->full_name,
        'course' => $student->course,
        'year_level' => $student->year_level,
        'contact_number' => $student->contact_number,
        'address' => $student->address,
        'photo' => $newPhoto,
    ];
    
    // Submit the update form
    $response = $this->actingAs($user)->put(route('students.update', $student), $updateData);
    
    // Assert the update was successful
    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
    
    // Refresh the student from database
    $student->refresh();
    
    // Assert student has a new photo_path (different from old)
    expect($student->photo_path)->not->toBeNull();
    expect($student->photo_path)->not->toBe($oldPhotoPath);
    
    // Assert the new file exists in storage
    Storage::disk('public')->assertExists($student->photo_path);
    
    // Assert the old file was deleted from storage
    Storage::disk('public')->assertMissing($oldPhotoPath);
})->repeat(100);
