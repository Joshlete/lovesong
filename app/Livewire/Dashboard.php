<?php

namespace App\Livewire;

use App\Constants\SongRequestStatus;
use App\Models\User;
use App\Services\UserStatsService;
use App\Traits\LocalTimeAware;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    use LocalTimeAware;

    public bool $showEmailVerifiedSuccess = false;

    public bool $showCelebration = false;

    public int $recentRequestsLimit = 5;

    public array $motivationalMessages = [
        'You\'re on fire! 🔥',
        'Keep the momentum going! 🚀',
        'Amazing progress! ⭐',
        'You\'re a song creation superstar! 🌟',
        'Your musical journey is inspiring! 🎵',
    ];

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        // Check if user just verified their email
        $this->showEmailVerifiedSuccess = request('verified') == '1' && $user->hasVerifiedEmail();
    }

    /**
     * Hook called when local time is updated.
     */
    protected function onLocalTimeUpdated(): void
    {
        // Dashboard doesn't need extra actions on time update
        // The greeting will automatically update via computed property
    }

    /**
     * Get recent song requests for the authenticated user.
     */
    public function getRecentRequestsProperty()
    {
        /** @var User $user */
        $user = Auth::user();

        return UserStatsService::getRecentRequests($user, $this->recentRequestsLimit)
            ->map(function ($request) {
                // Add a human-readable time ago
                $request->time_ago = $request->created_at->diffForHumans();

                // Add formatted date
                $request->formatted_date = $request->created_at->format('M d, Y');

                // Add status emoji
                $request->status_emoji = SongRequestStatus::emojis()[$request->status] ?? '📝';

                return $request;
            });
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

        $stats = UserStatsService::getStats($user);

        return [
            'total_requests' => $stats['total'],
            'pending_requests' => $stats['pending'],
            'completed_requests' => $stats['completed'],
            'in_progress_requests' => $stats['in_progress'],
        ];
    }

    /**
     * Get user achievements based on their song request activity.
     */
    public function getAchievementsProperty(): array
    {
        /** @var User $user */
        $user = Auth::user();
        $stats = $this->stats;

        return [
            [
                'achievement' => 'First Song Creator',
                'level' => 'bronze',
                'emoji' => '🎵',
                'unlocked' => $stats['total_requests'] >= 1,
                'description' => 'Create your first song request',
                'progress' => min($stats['total_requests'], 1),
                'total' => 1,
            ],
            [
                'achievement' => 'Song Enthusiast',
                'level' => 'silver',
                'emoji' => '🎤',
                'unlocked' => $stats['total_requests'] >= 3,
                'description' => 'Create 3 song requests',
                'progress' => min($stats['total_requests'], 3),
                'total' => 3,
            ],
            [
                'achievement' => 'Music Lover',
                'level' => 'gold',
                'emoji' => '🎶',
                'unlocked' => $stats['total_requests'] >= 5,
                'description' => 'Create 5 song requests',
                'progress' => min($stats['total_requests'], 5),
                'total' => 5,
            ],
            [
                'achievement' => 'Song Collector',
                'level' => 'platinum',
                'emoji' => '💿',
                'unlocked' => $stats['completed_requests'] >= 3,
                'description' => 'Complete 3 songs',
                'progress' => min($stats['completed_requests'], 3),
                'total' => 3,
            ],
            [
                'achievement' => 'Musical Legend',
                'level' => 'diamond',
                'emoji' => '👑',
                'unlocked' => $stats['total_requests'] >= 10 && $stats['completed_requests'] >= 5,
                'description' => 'Create 10+ requests with 5+ completed',
                'progress' => $stats['total_requests'] >= 10 && $stats['completed_requests'] >= 5 ? 1 : 0,
                'total' => 1,
            ],
        ];
    }

    /**
     * Get the next milestone for the user.
     */
    public function getNextMilestoneProperty(): ?array
    {
        $stats = $this->stats;
        $milestones = [
            1 => ['message' => 'Create your first song! 🎵', 'emoji' => '🎯'],
            3 => ['message' => 'You\'re getting the hang of this! 🚀', 'emoji' => '⭐'],
            5 => ['message' => 'Halfway to Music Lover status! 🎶', 'emoji' => '🔥'],
            10 => ['message' => 'Almost a Musical Legend! 👑', 'emoji' => '💎'],
        ];

        foreach ($milestones as $target => $milestone) {
            if ($stats['total_requests'] < $target) {
                return [
                    'target' => $target,
                    'current' => $stats['total_requests'],
                    'remaining' => $target - $stats['total_requests'],
                    'message' => $milestone['message'],
                    'emoji' => $milestone['emoji'],
                    'progress' => ($stats['total_requests'] / $target) * 100,
                ];
            }
        }

        return null; // User has achieved all milestones
    }

    /**
     * Get a random motivational message.
     */
    public function getMotivationalMessageProperty(): string
    {
        return $this->motivationalMessages[array_rand($this->motivationalMessages)];
    }

    /**
     * Get time-based greeting with energy.
     */
    public function getGreetingProperty(): string
    {
        /** @var User $user */
        $user = Auth::user();
        $firstName = explode(' ', $user->name)[0];

        return $this->getTimeBasedGreeting($firstName);
    }

    /**
     * Get live activity count (simulated).
     */
    public function getLiveActivityProperty(): array
    {
        // Simulate live activity for psychological effect
        $baseCount = 8;
        $variation = rand(-3, 7);
        $activeUsers = max(1, $baseCount + $variation);

        $activities = [
            'creating songs',
            'making music',
            'crafting beats',
            'writing lyrics',
            'recording vocals',
        ];

        return [
            'count' => $activeUsers,
            'activity' => $activities[array_rand($activities)],
        ];
    }

    /**
     * Trigger celebration animation.
     */
    public function celebrate(): void
    {
        $this->showCelebration = true;

        // Auto-hide celebration after 3 seconds
        $this->dispatch('hide-celebration');
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
            'achievements' => $this->achievements,
            'nextMilestone' => $this->nextMilestone,
            'greeting' => $this->greeting,
            'motivationalMessage' => $this->motivationalMessage,
            'liveActivity' => $this->liveActivity,
            'creativeStreak' => UserStatsService::getCreativeStreak(Auth::user()),
        ]);
    }
}
