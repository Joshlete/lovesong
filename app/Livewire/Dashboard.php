<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public bool $showEmailVerifiedSuccess = false;

    public int $recentRequestsLimit = 5;

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();
        
        // Check if user just verified their email
        $this->showEmailVerifiedSuccess = request('verified') == '1' && $user->hasVerifiedEmail();
    }

    /**
     * Get recent song requests for the authenticated user.
     */
    public function getRecentRequestsProperty()
    {
        /** @var User $user */
        $user = Auth::user();
        
        return $user
            ->songRequests()
            ->latest()
            ->take($this->recentRequestsLimit)
            ->get();
    }

    /**
     * Get the user's email verification status.
     */
    public function getUserEmailVerifiedProperty(): bool
    {
        /** @var User $user */
        $user = Auth::user();
        
        return $user->hasVerifiedEmail();
    }

    /**
     * Get dashboard statistics.
     */
    public function getStatsProperty(): array
    {
        /** @var User $user */
        $user = Auth::user();

        return [
            'total_requests' => $user->songRequests()->count(),
            'pending_requests' => $user->songRequests()->where('status', 'pending')->count(),
            'completed_requests' => $user->songRequests()->where('status', 'completed')->count(),
            'in_progress_requests' => $user->songRequests()->where('status', 'in_progress')->count(),
        ];
    }

    /**
     * Handle refresh of recent requests.
     */
    public function refreshRecentRequests(): void
    {
        // This will trigger a re-render of the component
        $this->dispatch('recent-requests-refreshed');
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'recentRequests' => $this->recentRequests,
            'userEmailVerified' => $this->userEmailVerified,
            'stats' => $this->stats,
        ]);
    }
}
