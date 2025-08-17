<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class S3FileService
{
    /**
     * Upload a song file to S3
     */
    public function uploadSong(UploadedFile $file, int $songRequestId): string
    {
        // Generate a unique filename
        $filename = $this->generateSongFilename($file, $songRequestId);
        
        // Store the file in S3
        $path = Storage::disk('s3')->putFileAs(
            'songs',
            $file,
            $filename,
            'private' // Private visibility for security
        );

        // Check if upload was successful
        if ($path === false) {
            throw new \Exception('Failed to upload file to S3. Please check AWS credentials and bucket configuration.');
        }

        return $path;
    }

    /**
     * Generate a secure download URL for a song
     */
    public function getDownloadUrl(string $filePath, int $expirationMinutes = 60, ?string $downloadFilename = null): string
    {
        $filename = $downloadFilename ?: basename($filePath);
        
        return Storage::disk('s3')->temporaryUrl(
            $filePath,
            now()->addMinutes($expirationMinutes),
            [
                'ResponseContentDisposition' => 'attachment; filename="' . $filename . '"',
                'ResponseContentType' => 'application/octet-stream'
            ]
        );
    }

    /**
     * Delete a song file from S3
     */
    public function deleteSong(string $filePath): bool
    {
        return Storage::disk('s3')->delete($filePath);
    }

    /**
     * Check if a song file exists in S3
     */
    public function songExists(string $filePath): bool
    {
        return Storage::disk('s3')->exists($filePath);
    }

    /**
     * Get file size in bytes
     */
    public function getFileSize(string $filePath): int
    {
        return Storage::disk('s3')->size($filePath);
    }

    /**
     * Generate a unique filename for the song
     */
    private function generateSongFilename(UploadedFile $file, int $songRequestId): string
    {
        $extension = $file->getClientOriginalExtension();
        $sanitizedOriginalName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $timestamp = now()->format('Y-m-d_H-i-s');
        $uniqueId = Str::random(8);
        
        return "song-{$songRequestId}_{$timestamp}_{$uniqueId}_{$sanitizedOriginalName}.{$extension}";
    }

    /**
     * Get allowed file types for song uploads
     */
    public function getAllowedMimeTypes(): array
    {
        return [
            'audio/mpeg',     // .mp3
            'audio/wav',      // .wav
            'audio/x-wav',    // .wav (alternative)
            'audio/mp4',      // .m4a
            'audio/aac',      // .aac
            'audio/ogg',      // .ogg
            'audio/flac',     // .flac
        ];
    }

    /**
     * Get maximum file size in bytes (50MB)
     */
    public function getMaxFileSize(): int
    {
        return 50 * 1024 * 1024; // 50MB
    }

    /**
     * Get allowed file extensions for validation
     */
    public function getAllowedExtensions(): array
    {
        return ['mp3', 'wav', 'm4a', 'aac', 'ogg', 'flac'];
    }
}