<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSongRequestRequest;
use App\Models\SongRequest;
use App\Services\S3FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SongRequestController extends Controller
{
    public function __construct(
        private S3FileService $s3Service
    ) {}

    /**
     * Display a listing of all song requests for admin.
     */
    public function index()
    {
        return view('admin.song-requests.index');
    }

    /**
     * Display the specified song request for admin.
     */
    public function show(SongRequest $songRequest)
    {
        return view('admin.song-requests.show', compact('songRequest'));
    }

    /**
     * Show the form for editing the specified song request.
     */
    public function edit(SongRequest $songRequest)
    {
        $songRequest->load('user');

        return view('admin.song-requests.edit', compact('songRequest'));
    }

    /**
     * Update the specified song request in storage.
     */
    public function update(UpdateSongRequestRequest $request, SongRequest $songRequest)
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('song_file')) {
            try {
                // Delete old S3 file if exists
                if ($songRequest->file_path) {
                    $this->s3Service->deleteSong($songRequest->file_path);
                }

                // Upload new file to S3
                $file = $request->file('song_file');
                $filePath = $this->s3Service->uploadSong($file, $songRequest->id);

                $validated['file_path'] = $filePath;
                $validated['file_size'] = $file->getSize();
                $validated['original_filename'] = $file->getClientOriginalName();

                // Automatically mark as completed when file is uploaded
                $validated['status'] = 'completed';

            } catch (\Exception $e) {
                Log::error('S3 file upload failed', [
                    'song_request_id' => $songRequest->id,
                    'file_name' => $request->file('song_file')->getClientOriginalName(),
                    'file_size' => $request->file('song_file')->getSize(),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->withErrors(['song_file' => 'The song file failed to upload: '.$e->getMessage()]);
            }
        }

        // Handle delivered_at logic
        $statusUpdate = $this->prepareStatusUpdate($validated['status']);
        $validated = array_merge($validated, $statusUpdate);

        // Override delivered_at if explicitly provided
        if ($request->filled('delivered_at')) {
            $validated['delivered_at'] = $request->input('delivered_at');
        }

        $songRequest->update($validated);

        $message = 'Song request updated successfully!';
        if ($request->hasFile('song_file')) {
            $message = 'Song uploaded and request marked as completed! The customer will be notified.';
        }

        return redirect()->route('admin.song-requests.show', $songRequest)
            ->with('success', $message);
    }

    /**
     * Remove the specified song request from storage.
     */
    public function destroy(SongRequest $songRequest)
    {
        // Delete S3 file if exists (handled automatically by model's booted method)
        $songRequest->delete();

        return redirect()->route('admin.song-requests.index')
            ->with('success', 'Song request deleted successfully!');
    }

    /**
     * Update the status of a song request via AJAX.
     */
    public function updateStatus(Request $request, SongRequest $songRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $updates = $this->prepareStatusUpdate($validated['status']);
        $songRequest->update($updates);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status' => $songRequest->status,
            'delivered_at' => $songRequest->delivered_at?->format('M j, Y g:i A'),
        ]);
    }

    /**
     * Prepare status update data with delivered_at logic.
     */
    protected function prepareStatusUpdate(string $status): array
    {
        $updates = ['status' => $status];

        // Auto-set delivered_at when status changes to completed
        if ($status === 'completed') {
            $updates['delivered_at'] = now();
        } elseif ($status !== 'completed') {
            $updates['delivered_at'] = null;
        }

        return $updates;
    }

    /**
     * Download song file for admin
     */
    public function download(SongRequest $songRequest)
    {
        if (! $songRequest->hasS3File()) {
            abort(404, 'No file available for download');
        }

        if (! $this->s3Service->songExists($songRequest->file_path)) {
            abort(404, 'File not found in storage');
        }

        // Generate a fresh download URL on demand
        $freshUrl = $songRequest->generateFreshDownloadUrl();

        return redirect($freshUrl);
    }

    /**
     * Get the maximum allowed upload size for this application.
     * Uses the smaller of: our app's limit vs PHP's server limit.
     * This prevents validation from allowing files that PHP will reject.
     */
    private function getMaxAllowedUploadSize(): int
    {
        $appMaxSize = $this->s3Service->getMaxFileSize(); // Our application limit (50MB)
        $phpMaxSize = $this->getPhpUploadLimit();         // Server's PHP limit

        // Use whichever is smaller to prevent upload failures
        return min($appMaxSize, $phpMaxSize);
    }

    /**
     * Get the current PHP server upload limit in bytes
     */
    private function getPhpUploadLimit(): int
    {
        $uploadMax = $this->parseIniValue(ini_get('upload_max_filesize'));
        $postMax = $this->parseIniValue(ini_get('post_max_size'));

        // Return the most restrictive limit
        return min($uploadMax, $postMax);
    }

    /**
     * Parse PHP ini value to bytes
     */
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
}
