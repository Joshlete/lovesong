<?php

namespace App\Livewire\Admin;

use App\Models\SongRequest;
use Livewire\Component;

class EditSongRequest extends Component
{
    public SongRequest $songRequest;

    public $status;

    public $payment_reference;

    public $delivered_at;

    protected $rules = [
        'status' => 'required|in:pending,in_progress,completed,cancelled',
        'payment_reference' => 'nullable|string|max:255',
        'delivered_at' => 'nullable|date',
    ];

    public function mount(SongRequest $songRequest): void
    {
        $this->songRequest = $songRequest;
        $this->status = $songRequest->status;
        $this->payment_reference = $songRequest->payment_reference;
        $this->delivered_at = $songRequest->delivered_at?->format('Y-m-d\TH:i');
    }

    public function updatedStatus(): void
    {
        // Auto-fill delivered_at when status changes to completed
        if ($this->status === 'completed' && ! $this->delivered_at) {
            $this->delivered_at = now()->format('Y-m-d\TH:i');
        }
    }

    public function handleFileUploaded(): void
    {
        // This will be called when a file is uploaded via the FileUpload component
        $this->status = 'completed';
        if (! $this->delivered_at) {
            $this->delivered_at = now()->format('Y-m-d\TH:i');
        }

        // Refresh the song request data
        $this->songRequest->refresh();
    }

        public function save(): void
    {
        $this->validate();

        $updates = [
            'status' => $this->status,
            'payment_reference' => $this->payment_reference,
        ];

        // Handle delivered_at logic
        if ($this->status === 'completed' && ! $this->delivered_at) {
            $updates['delivered_at'] = now();
        } elseif ($this->status !== 'completed') {
            $updates['delivered_at'] = null;
        } else {
            $updates['delivered_at'] = $this->delivered_at;
        }

        $this->songRequest->update($updates);

        session()->flash('success', 'Song request updated successfully!');
        
        $this->redirect(route('admin.song-requests.show', $this->songRequest));
    }

    public function render()
    {
        return view('livewire.admin.edit-song-request');
    }
}
