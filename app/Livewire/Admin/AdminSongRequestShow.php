<?php

namespace App\Livewire\Admin;

use App\Models\SongRequest;
use App\Services\S3FileService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminSongRequestShow extends Component
{
    use WithFileUploads;

    public SongRequest $songRequest;

    public string $status = '';

    public string $payment_reference = '';

    public ?string $delivered_at = null;

    public $song_file;

    public bool $editMode = false;

    public bool $isUpdating = false;

    protected $listeners = ['triggerEditMode' => 'enableEditMode'];

    public function mount(SongRequest $songRequest): void
    {
        $this->songRequest = $songRequest->load('user');
        $this->initializeFormData();
    }

    /**
     * Enable edit mode (called from edit page).
     */
    public function enableEditMode(): void
    {
        $this->editMode = true;
    }

    /**
     * Initialize form data from the song request model.
     */
    protected function initializeFormData(): void
    {
        $this->status = $this->songRequest->status;
        $this->payment_reference = $this->songRequest->payment_reference ?? '';
        $this->delivered_at = $this->songRequest->delivered_at?->format('Y-m-d\TH:i');
    }

    /**
     * Toggle edit mode.
     */
    public function toggleEditMode(): void
    {
        $this->editMode = ! $this->editMode;

        if (! $this->editMode) {
            // Reset form data when canceling edit
            $this->initializeFormData();
            $this->song_file = null;
        }
    }

    /**
     * Auto-update delivered_at when status changes.
     */
    public function updatedStatus(): void
    {
        if ($this->status === 'completed' && ! $this->delivered_at) {
            $this->delivered_at = now()->format('Y-m-d\TH:i');
        }
    }

    /**
     * Update the song request.
     */
    public function updateSongRequest(): void
    {
        $this->isUpdating = true;

        try {
            $this->validateSongRequestData();

            $updates = $this->prepareUpdateData();

            // Handle file upload if present
            if ($this->song_file) {
                $this->handleFileUpload($updates);
            }

            $this->songRequest->update($updates);
            $this->songRequest->refresh();
            $this->initializeFormData();
            $this->editMode = false;
            $this->song_file = null;

            session()->flash('success', 'Song request updated successfully!');
            $this->dispatch('song-request-updated');

        } catch (\Exception $e) {
            Log::error('Failed to update song request', [
                'song_request_id' => $this->songRequest->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to update song request: '.$e->getMessage());
        } finally {
            $this->isUpdating = false;
        }
    }

    /**
     * Validate song request data.
     */
    protected function validateSongRequestData(): void
    {
        $this->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'payment_reference' => 'nullable|string|max:255',
            'delivered_at' => 'nullable|date',
            'song_file' => [
                'nullable',
                'file',
                'max:51200', // 50MB in KB
                'mimes:mp3,wav,flac,m4a,aac,ogg',
            ],
        ], [
            'status.required' => 'Status is required.',
            'status.in' => 'Selected status is invalid.',
            'payment_reference.max' => 'Payment reference cannot exceed 255 characters.',
            'delivered_at.date' => 'Delivery date must be a valid date.',
            'song_file.max' => 'Song file cannot exceed 50MB.',
            'song_file.mimes' => 'Song file must be an audio file (mp3, wav, flac, m4a, aac, ogg).',
        ]);
    }

    /**
     * Prepare update data.
     */
    protected function prepareUpdateData(): array
    {
        $updates = [
            'status' => $this->status,
            'payment_reference' => $this->payment_reference ?: null,
        ];

        // Handle delivered_at logic
        if ($this->status === 'completed') {
            $updates['delivered_at'] = $this->delivered_at ? \Carbon\Carbon::parse($this->delivered_at) : now();
        } elseif ($this->status !== 'completed') {
            $updates['delivered_at'] = null;
        }

        return $updates;
    }

    /**
     * Handle file upload.
     */
    protected function handleFileUpload(array &$updates): void
    {
        $s3Service = app(S3FileService::class);

        // Delete old file if exists
        if ($this->songRequest->file_path) {
            $s3Service->deleteSong($this->songRequest->file_path);
        }

        // Upload new file
        $filePath = $s3Service->uploadSong($this->song_file, $this->songRequest->id);

        $updates['file_path'] = $filePath;
        $updates['file_size'] = $this->song_file->getSize();
        $updates['original_filename'] = $this->song_file->getClientOriginalName();
        $updates['status'] = 'completed'; // Auto-mark as completed when file uploaded
    }

    /**
     * Quick status update without full edit.
     */
    public function quickStatusUpdate(string $status): void
    {
        $updates = ['status' => $status];

        // Auto-set delivered_at when status changes to completed
        if ($status === 'completed') {
            $updates['delivered_at'] = now();
        } elseif ($status !== 'completed') {
            $updates['delivered_at'] = null;
        }

        $this->songRequest->update($updates);
        $this->songRequest->refresh();
        $this->initializeFormData();

        session()->flash('success', 'Song request status updated to '.ucfirst(str_replace('_', ' ', $status)).' successfully.');
        $this->dispatch('song-request-updated');
    }

    /**
     * Delete the song request.
     */
    public function deleteSongRequest(): void
    {
        try {
            // S3 file deletion is handled by the model's boot method
            $this->songRequest->delete();

            session()->flash('success', 'Song request deleted successfully.');
            $this->redirect(route('admin.song-requests.index'));

        } catch (\Exception $e) {
            Log::error('Failed to delete song request', [
                'song_request_id' => $this->songRequest->id,
                'error' => $e->getMessage(),
            ]);

            session()->flash('error', 'Failed to delete song request: '.$e->getMessage());
        }
    }

    /**
     * Mark as delivered.
     */
    public function markAsDelivered(): void
    {
        $this->quickStatusUpdate('completed');
    }

    /**
     * Get user statistics.
     */
    public function getUserStatsProperty(): array
    {
        return [
            'total_requests' => $this->songRequest->user->songRequests()->count(),
            'completed_requests' => $this->songRequest->user->songRequests()->where('status', 'completed')->count(),
            'total_spent' => $this->songRequest->user->songRequests()->where('payment_status', 'succeeded')->sum('price_usd'),
        ];
    }

    /**
     * Get all enhanced song details.
     */
    public function getSongDetailsProperty(): array
    {
        return [
            'song_title' => $this->songRequest->recipient_name,
            'style' => $this->songRequest->style,
            'custom_style' => $this->songRequest->custom_style,
            'mood' => $this->songRequest->mood,
            'custom_mood' => $this->songRequest->custom_mood,
            'lyrics_idea' => $this->songRequest->lyrics_idea,
            'song_description' => $this->songRequest->song_description,
            'genre_details' => $this->songRequest->genre_details,
            'tempo' => $this->songRequest->tempo,
            'vocals' => $this->songRequest->vocals,
            'instruments' => $this->songRequest->instruments,
            'song_structure' => $this->songRequest->song_structure,
            'inspiration' => $this->songRequest->inspiration,
            'special_instructions' => $this->songRequest->special_instructions,
        ];
    }

    public function render()
    {
        return view('livewire.admin.admin-song-request-show', [
            'userStats' => $this->userStats,
            'songDetails' => $this->songDetails,
        ]);
    }
}
