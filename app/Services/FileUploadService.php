<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    /**
     * Upload a photo file and optionally delete the old one.
     *
     * @param UploadedFile $file The uploaded file
     * @param string|null $oldPath The path to the old file to delete (optional)
     * @return string The path to the uploaded file
     */
    public function uploadPhoto(UploadedFile $file, ?string $oldPath = null): string
    {
        // Delete old photo if it exists
        if ($oldPath) {
            $this->deletePhoto($oldPath);
        }

        // Store the new photo in the 'photos' directory within the public disk
        $path = $file->store('photos', 'public');

        return $path;
    }

    /**
     * Delete a photo file from storage.
     *
     * @param string $path The path to the file to delete
     * @return bool True if the file was deleted, false otherwise
     */
    public function deletePhoto(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Generate a public URL for a photo.
     *
     * @param string $path The path to the file
     * @return string The public URL to the file
     */
    public function getPhotoUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}
