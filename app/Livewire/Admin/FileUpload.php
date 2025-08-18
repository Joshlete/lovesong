<?php

namespace App\Livewire\Admin;

use App\Models\SongRequest;
use App\Services\S3FileService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class FileUpload extends Component
{
    use WithFileUploads;

    public SongRequest $songRequest;

    public $file;

    public $uploading = false;

    public $uploadSuccess = false;

    public $uploadError = null;

    public $uploadProgress = 0;

    protected $rules = [
        'file' => [
            'required',
            'file',
            'max:51200', // 50MB in KB
            'mimes:mp3,wav,m4a,aac,ogg,flac',
        ],
    ];

    protected $messages = [
        'file.required' => 'Please select a file to upload.',
        'file.file' => 'The uploaded file is not valid.',
        'file.max' => 'The file size cannot exceed 50MB.',
        'file.mimes' => 'Only audio files are allowed (MP3, WAV, M4A, AAC, OGG, FLAC).',
    ];

    public function mount(SongRequest $songRequest): void
    {
        $this->songRequest = $songRequest;
    }

    public function updatedFile(): void
    {
        $this->uploadError = null;
        $this->uploadSuccess = false;

        if ($this->file) {
            $this->validate();
            $this->uploadFile();
        }
    }

    public function uploadFile(): void
    {
        try {
            $this->uploading = true;
            $this->uploadProgress = 0;

            // Ensure we have a file to upload
            if (! $this->file || ! is_object($this->file) || ! method_exists($this->file, 'store')) {
                throw new \Exception('No valid file provided for upload.');
            }

            // Check if S3 is properly configured
            if (empty(config('filesystems.disks.s3.key')) || empty(config('filesystems.disks.s3.secret'))) {
                throw new \Exception('File storage is not properly configured. Please contact an administrator.');
            }

            $s3Service = app(S3FileService::class);

            // Delete old S3 file if exists
            if ($this->songRequest->file_path) {
                $s3Service->deleteSong($this->songRequest->file_path);
            }

            // Store the file temporarily
            $tempPath = $this->file->store('temp');
            $this->uploadProgress = 30;

            // Upload to S3
            $filePath = $s3Service->uploadSong($this->file, $this->songRequest->id);
            $this->uploadProgress = 80;

            // Check if upload returned a valid path
            if (empty($filePath)) {
                throw new \Exception('S3 upload failed - no file path returned. Please check AWS configuration.');
            }

            // Verify the file actually uploaded
            if (! $s3Service->songExists($filePath)) {
                throw new \Exception('File upload verification failed - file not found in storage at: ' . $filePath);
            }

            $this->uploadProgress = 90;

            // Update song request
            $this->songRequest->update([
                'file_path' => $filePath,
                'file_size' => $this->file->getSize(),
                'original_filename' => $this->file->getClientOriginalName(),
                'status' => 'completed',
                'delivered_at' => now(),
            ]);

            $this->uploadProgress = 100;
            $this->uploadSuccess = true;
            $this->uploading = false;

            // Clean up temp file
            \Storage::delete($tempPath);

            // Refresh the song request to get updated data
            $this->songRequest->refresh();

            // Emit event to update parent component if needed
            $this->dispatch('fileUploaded');

            // Reset file input
            $this->file = null;

        } catch (\Exception $e) {
            Log::error('Livewire file upload failed', [
                'song_request_id' => $this->songRequest->id,
                'file_name' => $this->file?->getClientOriginalName(),
                'file_size' => $this->file?->getSize(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->uploadError = $e->getMessage();
            $this->uploading = false;
            $this->uploadProgress = 0;
        }
    }

    public function clearMessages(): void
    {
        $this->uploadError = null;
        $this->uploadSuccess = false;
    }

    public function getMaxAllowedUploadSize(): int
    {
        $s3Service = app(S3FileService::class);
        $appMaxSize = $s3Service->getMaxFileSize(); // Our application limit (50MB)
        $phpMaxSize = $this->getPhpUploadLimit();   // Server's PHP limit

        // Use whichever is smaller to prevent upload failures
        return min($appMaxSize, $phpMaxSize);
    }

    private function getPhpUploadLimit(): int
    {
        $uploadMax = $this->parseIniValue(ini_get('upload_max_filesize'));
        $postMax = $this->parseIniValue(ini_get('post_max_size'));

        // Return the most restrictive limit
        return min($uploadMax, $postMax);
    }

    private function parseIniValue(string $value): int
    {
        $value = trim($value);
        $last = strtolower(substr($value, -1));
        $number = (int) substr($value, 0, -1);

        switch ($last) {
            case 'g':
                return $number * 1024 * 1024 * 1024;
            case 'm':
                return $number * 1024 * 1024;
            case 'k':
                return $number * 1024;
            default:
                return (int) $value;
        }
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2).' KB';
        } else {
            return $bytes.' bytes';
        }
    }

    public function render()
    {
        return view('livewire.admin.file-upload');
    }
}
