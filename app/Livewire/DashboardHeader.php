<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\UserStatsService;
use App\Traits\LocalTimeAware;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardHeader extends Component
{
    use LocalTimeAware;

    public array $quickStats = [];

    public string $timeBasedMessage = '';

    public function mount(): void
    {
        $this->loadQuickStats();
        $this->timeBasedMessage = $this->getTimeBasedMessage();
    }

    /**
     * Hook called when local time is updated.
     */
    protected function onLocalTimeUpdated(): void
    {
        $this->timeBasedMessage = $this->getTimeBasedMessage();
    }

    /**
     * Load quick stats for header display.
     */
    public function loadQuickStats(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->quickStats = UserStatsService::getQuickStats($user);
    }

    /**
     * Get the user's creative streak.
     */
    public function getCreativeStreakProperty(): string
    {
        /** @var User $user */
        $user = Auth::user();

        $lastSongTime = UserStatsService::getLastSongTime($user);

        if (! $lastSongTime) {
            return 'Start your streak! 🔥';
        }

        $daysSince = $lastSongTime->diffInDays(now());

        return match (true) {
            $daysSince === 0 => 'Hot streak! 🔥',
            $daysSince === 1 => 'Yesterday\'s creator! ⭐',
            $daysSince <= 3 => 'Active creator! 🎵',
            $daysSince <= 7 => 'Time for new music! 🎤',
            default => 'Ready for comeback! 🚀',
        };
    }

    /**
     * Refresh header data.
     */
    public function refresh(): void
    {
        // Clear cache for fresh stats
        UserStatsService::clearCache(Auth::user());

        $this->loadQuickStats();
        $this->timeBasedMessage = $this->getTimeBasedMessage();

        // Request fresh local time from JavaScript
        $this->initLocalTime();
    }

    public function render()
    {
        return view('livewire.dashboard-header', [
            'creativeStreak' => $this->creativeStreak,
        ]);
    }
}
