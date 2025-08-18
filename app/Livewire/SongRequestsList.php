<?php

namespace App\Livewire;

use App\Models\SongRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SongRequestsList extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = '';

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount(): void
    {
        // Initialize any default values if needed
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getSongRequestsProperty()
    {
        return SongRequest::query()
            ->where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('recipient_name', 'like', '%'.$this->search.'%')
                        ->orWhere('style', 'like', '%'.$this->search.'%')
                        ->orWhere('mood', 'like', '%'.$this->search.'%')
                        ->orWhere('lyrics_idea', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getStats(): array
    {
        $userId = Auth::id();

        return [
            'total' => SongRequest::where('user_id', $userId)->count(),
            'pending' => SongRequest::where('user_id', $userId)->where('status', 'pending')->count(),
            'in_progress' => SongRequest::where('user_id', $userId)->where('status', 'in_progress')->count(),
            'completed' => SongRequest::where('user_id', $userId)->where('status', 'completed')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.song-requests-list', [
            'songRequests' => $this->songRequests,
            'stats' => $this->getStats(),
        ]);
    }
}
