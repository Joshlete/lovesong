<?php

namespace App\Livewire\Admin;

use App\Models\SongRequest;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class AdminSongRequestsList extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public string $userFilter = '';

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'userFilter' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 15],
    ];

    /**
     * Reset pagination when search is updated.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when status filter is updated.
     */
    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when user filter is updated.
     */
    public function updatingUserFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Sort by a specific column.
     */
    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Clear all filters.
     */
    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->userFilter = '';
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    /**
     * Delete a song request.
     */
    public function deleteSongRequest(int $songRequestId): void
    {
        $songRequest = SongRequest::findOrFail($songRequestId);

        // Delete associated files if any
        if ($songRequest->file_path) {
            // Add file deletion logic here if needed
        }

        $songRequest->delete();

        session()->flash('success', 'Song request deleted successfully.');

        $this->dispatch('song-request-deleted');
    }

    /**
     * Quick status update.
     */
    public function updateStatus(int $songRequestId, string $status): void
    {
        $songRequest = SongRequest::findOrFail($songRequestId);
        $songRequest->update(['status' => $status]);

        session()->flash('success', 'Song request status updated successfully.');

        $this->dispatch('song-request-updated');
    }

    /**
     * Get filtered and sorted song requests.
     */
    public function getSongRequestsProperty()
    {
        return SongRequest::with('user')
            ->when($this->search, function (Builder $query) {
                $query->where('recipient_name', 'like', '%'.$this->search.'%')
                    ->orWhere('lyrics_idea', 'like', '%'.$this->search.'%')
                    ->orWhere('song_description', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function (Builder $query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->userFilter, function (Builder $query) {
                $query->whereHas('user', function (Builder $q) {
                    $q->where('name', 'like', '%'.$this->userFilter.'%')
                        ->orWhere('email', 'like', '%'.$this->userFilter.'%');
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    /**
     * Get statistics for the current filtered results.
     */
    public function getStatsProperty(): array
    {
        $baseQuery = SongRequest::query()
            ->when($this->search, function (Builder $query) {
                $query->where('recipient_name', 'like', '%'.$this->search.'%')
                    ->orWhere('lyrics_idea', 'like', '%'.$this->search.'%')
                    ->orWhere('song_description', 'like', '%'.$this->search.'%');
            })
            ->when($this->userFilter, function (Builder $query) {
                $query->whereHas('user', function (Builder $q) {
                    $q->where('name', 'like', '%'.$this->userFilter.'%')
                        ->orWhere('email', 'like', '%'.$this->userFilter.'%');
                });
            });

        return [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $baseQuery)->where('status', 'in_progress')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.admin-song-requests-list', [
            'songRequests' => $this->songRequests,
            'stats' => $this->stats,
        ]);
    }
}
