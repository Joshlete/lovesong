<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\UserStatsService;
use App\Traits\LocalTimeAware;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileStats extends Component
{
    use LocalTimeAware;

    /**
     * Get user profile stats for display.
     */
    public function getProfileStatsProperty(): array
    {
        /** @var User $user */
        $user = Auth::user();

        $stats = UserStatsService::getStats($user);
        $creativeStreak = UserStatsService::getCreativeStreak($user);

        return [
            'member_since' => $user->created_at->format('M Y'),
            'days_active' => $user->created_at->diffInDays(now()),
            'total_songs' => $stats['total'],
            'completed_songs' => $stats['completed'],
            'creative_streak' => $creativeStreak,
            'completion_rate' => $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0,
        ];
    }

    /**
     * Get user achievements for profile display.
     */
    public function getAchievementsProperty(): array
    {
        /** @var User $user */
        $user = Auth::user();
        $stats = UserStatsService::getStats($user);

        $achievements = [
            [
                'title' => 'Music Pioneer',
                'description' => 'Joined the musical revolution',
                'emoji' => '🎵',
                'unlocked' => true,
                'date' => $user->created_at->format('M d, Y'),
            ],
            [
                'title' => 'Song Creator',
                'description' => 'Created your first song request',
                'emoji' => '🎤',
                'unlocked' => $stats['total'] >= 1,
                'date' => $stats['total'] >= 1 ? 'Unlocked' : 'Locked',
            ],
            [
                'title' => 'Music Enthusiast',
                'description' => 'Completed 3 song requests',
                'emoji' => '🎶',
                'unlocked' => $stats['completed'] >= 3,
                'date' => $stats['completed'] >= 3 ? 'Unlocked' : 'Locked',
            ],
            [
                'title' => 'Creative Streak',
                'description' => 'Maintained a 7-day creative streak',
                'emoji' => '🔥',
                'unlocked' => UserStatsService::getCreativeStreak($user) >= 7,
                'date' => UserStatsService::getCreativeStreak($user) >= 7 ? 'Unlocked' : 'Locked',
            ],
            [
                'title' => 'Music Legend',
                'description' => 'Created 10+ songs with 80%+ completion rate',
                'emoji' => '👑',
                'unlocked' => $stats['total'] >= 10 && ($stats['total'] > 0 ? ($stats['completed'] / $stats['total']) >= 0.8 : false),
                'date' => ($stats['total'] >= 10 && ($stats['total'] > 0 ? ($stats['completed'] / $stats['total']) >= 0.8 : false)) ? 'Unlocked' : 'Locked',
            ],
        ];

        return $achievements;
    }

    /**
     * Get profile insights for motivation.
     */
    public function getInsightsProperty(): array
    {
        /** @var User $user */
        $user = Auth::user();
        $stats = UserStatsService::getStats($user);

        $insights = [];

        // Creative streak insight
        $streak = UserStatsService::getCreativeStreak($user);
        if ($streak > 0) {
            $insights[] = [
                'type' => 'streak',
                'title' => 'Creative Streak!',
                'message' => "You're on a {$streak}-day creative streak! Keep it up! 🔥",
                'color' => 'orange',
                'emoji' => '🔥',
            ];
        }

        // Completion rate insight
        $completionRate = $stats['total'] > 0 ? ($stats['completed'] / $stats['total']) * 100 : 0;
        if ($completionRate >= 80 && $stats['total'] >= 3) {
            $insights[] = [
                'type' => 'completion',
                'title' => 'High Achiever!',
                'message' => "You have a {$completionRate}% completion rate. Excellent work! ⭐",
                'color' => 'green',
                'emoji' => '⭐',
            ];
        }

        // Recent activity insight
        $lastSongTime = UserStatsService::getLastSongTime($user);
        if ($lastSongTime && $lastSongTime->diffInDays(now()) === 0) {
            $insights[] = [
                'type' => 'recent',
                'title' => 'Active Today!',
                'message' => 'You created music today. Your creativity is shining! ✨',
                'color' => 'purple',
                'emoji' => '✨',
            ];
        }

        // Motivational insight for inactive users
        if ($stats['total'] === 0) {
            $insights[] = [
                'type' => 'motivation',
                'title' => 'Ready to Start?',
                'message' => 'Your musical journey awaits! Create your first song request. 🚀',
                'color' => 'blue',
                'emoji' => '🚀',
            ];
        }

        return $insights;
    }

    /**
     * Get personalized greeting for profile.
     */
    public function getPersonalizedGreetingProperty(): string
    {
        /** @var User $user */
        $user = Auth::user();
        $firstName = explode(' ', $user->name)[0];

        return $this->getTimeBasedGreeting($firstName);
    }

    public function render()
    {
        return view('livewire.profile-stats', [
            'profileStats' => $this->profileStats,
            'achievements' => $this->achievements,
            'insights' => $this->insights,
            'personalizedGreeting' => $this->personalizedGreeting,
        ]);
    }
}
