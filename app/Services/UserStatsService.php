<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserStatsService
{
    /**
     * Get comprehensive stats for a user.
     */
    public static function getStats(User $user): array
    {
        return Cache::remember("user-stats-{$user->id}", 60, function () use ($user) {
            $songRequests = $user->songRequests();

            return [
                'total' => $songRequests->count(),
                'pending' => (clone $songRequests)->where('status', 'pending')->count(),
                'completed' => (clone $songRequests)->where('status', 'completed')->count(),
                'in_progress' => (clone $songRequests)->where('status', 'in_progress')->count(),
                'cancelled' => (clone $songRequests)->where('status', 'cancelled')->count(),
            ];
        });
    }

    /**
     * Get quick stats for header display.
     */
    public static function getQuickStats(User $user): array
    {
        $stats = self::getStats($user);

        return [
            'total_songs' => $stats['total'],
            'completed_songs' => $stats['completed'],
            'pending_songs' => $stats['pending'],
        ];
    }

    /**
     * Get recent song requests for a user.
     */
    public static function getRecentRequests(User $user, int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return $user->songRequests()
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get the user's creative streak (consecutive days with requests).
     */
    public static function getCreativeStreak(User $user): int
    {
        $dates = $user->songRequests()
            ->orderBy('created_at', 'desc')
            ->pluck('created_at')
            ->map(fn ($date) => $date->format('Y-m-d'))
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $streak = 1;
        $yesterday = now()->subDay()->format('Y-m-d');

        // Start from today or yesterday
        if (! in_array(now()->format('Y-m-d'), $dates->toArray()) &&
            ! in_array($yesterday, $dates->toArray())) {
            return 0;
        }

        // Count consecutive days
        $previousDate = now()->format('Y-m-d');
        foreach ($dates as $date) {
            $diff = \Carbon\Carbon::parse($previousDate)->diffInDays(\Carbon\Carbon::parse($date));
            if ($diff > 1) {
                break;
            }
            if ($diff === 1) {
                $streak++;
            }
            $previousDate = $date;
        }

        return $streak;
    }

    /**
     * Clear cached stats for a user.
     */
    public static function clearCache(User $user): void
    {
        Cache::forget("user-stats-{$user->id}");
    }

    /**
     * Get the last song request time.
     */
    public static function getLastSongTime(User $user): ?\Carbon\Carbon
    {
        $lastSong = $user->songRequests()->latest()->first();

        return $lastSong?->created_at;
    }
}
