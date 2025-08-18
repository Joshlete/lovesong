<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AchievementBadge extends Component
{
    public string $achievement;

    public string $level;

    public string $emoji;

    public bool $unlocked;

    public ?string $description;

    public int $progress;

    public int $total;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $achievement,
        string $level = 'bronze',
        string $emoji = '🏆',
        bool $unlocked = false,
        ?string $description = null,
        int $progress = 0,
        int $total = 1
    ) {
        $this->achievement = $achievement;
        $this->level = $level;
        $this->emoji = $emoji;
        $this->unlocked = $unlocked;
        $this->description = $description;
        $this->progress = $progress;
        $this->total = $total;
    }

    /**
     * Get the badge styling based on level.
     */
    public function getBadgeClasses(): string
    {
        $baseClasses = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-bold shadow-lg transform transition-all duration-300';

        if (! $this->unlocked) {
            return "{$baseClasses} bg-gray-200 text-gray-500 opacity-50";
        }

        $levelClasses = match ($this->level) {
            'bronze' => 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-white hover:scale-105',
            'silver' => 'bg-gradient-to-r from-gray-400 to-gray-300 text-gray-800 hover:scale-105',
            'gold' => 'bg-gradient-to-r from-yellow-400 to-yellow-300 text-yellow-900 hover:scale-105 animate-pulse',
            'platinum' => 'bg-gradient-to-r from-purple-400 to-pink-400 text-white hover:scale-105 animate-pulse',
            'diamond' => 'bg-gradient-to-r from-cyan-400 to-blue-500 text-white hover:scale-105 animate-bounce',
            default => 'bg-gradient-to-r from-blue-400 to-blue-500 text-white hover:scale-105',
        };

        return "{$baseClasses} {$levelClasses}";
    }

    /**
     * Get progress percentage.
     */
    public function getProgressPercentage(): float
    {
        if ($this->total === 0) {
            return 0;
        }

        return min(100, ($this->progress / $this->total) * 100);
    }

    /**
     * Check if achievement is close to completion.
     */
    public function isAlmostComplete(): bool
    {
        return $this->getProgressPercentage() >= 80 && ! $this->unlocked;
    }

    /**
     * Get the achievement title with level.
     */
    public function getTitle(): string
    {
        $levelEmoji = match ($this->level) {
            'bronze' => '🥉',
            'silver' => '🥈',
            'gold' => '🥇',
            'platinum' => '💎',
            'diamond' => '💠',
            default => '🏆',
        };

        return "{$levelEmoji} {$this->achievement}";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.achievement-badge');
    }
}
