<?php

use App\Services\FileUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new FileUploadService();
    Storage::fake('public');
});

test('uploadPhoto stores file and returns path', function () {
    $file = UploadedFile::fake()->create('student.jpg', 100);

    $path = $this->service->uploadPhoto($file);

    expect($path)->toBeString()
        ->and($path)->toContain('photos/');

    // Verify file was stored
    Storage::disk('public')->assertExists($path);
});

test('uploadPhoto deletes old photo when provided', function () {
    // Create and store an old photo
    $oldFile = UploadedFile::fake()->create('old-student.jpg', 100);
    $oldPath = $oldFile->store('photos', 'public');
    
    // Verify old file exists
    Storage::disk('public')->assertExists($oldPath);

    // Upload new photo with old path
    $newFile = UploadedFile::fake()->create('new-student.jpg', 100);
    $newPath = $this->service->uploadPhoto($newFile, $oldPath);

    // Verify old file was deleted
    Storage::disk('public')->assertMissing($oldPath);
    
    // Verify new file exists
    Storage::disk('public')->assertExists($newPath);
});

test('deletePhoto removes file from storage', function () {
    // Create and store a photo
    $file = UploadedFile::fake()->create('student.jpg', 100);
    $path = $file->store('photos', 'public');
    
    // Verify file exists
    Storage::disk('public')->assertExists($path);

    // Delete the photo
    $result = $this->service->deletePhoto($path);

    expect($result)->toBeTrue();
    
    // Verify file was deleted
    Storage::disk('public')->assertMissing($path);
});

test('deletePhoto returns false when file does not exist', function () {
    $result = $this->service->deletePhoto('photos/nonexistent.jpg');

    expect($result)->toBeFalse();
});

test('getPhotoUrl returns valid URL', function () {
    // Create and store a photo
    $file = UploadedFile::fake()->create('student.jpg', 100);
    $path = $file->store('photos', 'public');

    $url = $this->service->getPhotoUrl($path);

    expect($url)->toBeString()
        ->and($url)->toContain('storage')
        ->and($url)->toContain('photos');
});
