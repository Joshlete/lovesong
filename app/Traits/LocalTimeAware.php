<?php

namespace App\Traits;

trait LocalTimeAware
{
    /**
     * The user's local hour (0-23).
     */
    public int $userLocalHour = 12; // Default to noon

    /**
     * Update the user's local hour from client-side JavaScript.
     */
    public function updateLocalHour(int $hour): void
    {
        $this->userLocalHour = $hour;
        $this->onLocalTimeUpdated();
    }

    /**
     * Hook for components to react to time updates.
     * Override this in your component if needed.
     */
    protected function onLocalTimeUpdated(): void
    {
        // Override in component if needed
    }

    /**
     * Get a time-based greeting for the user.
     */
    public function getTimeBasedGreeting(?string $name = null): string
    {
        $nameString = $name ? ", {$name}" : '';

        return match (true) {
            $this->userLocalHour < 6 => "Early bird{$nameString}! 🌅",
            $this->userLocalHour < 12 => "Good morning{$nameString}! ☀️",
            $this->userLocalHour < 17 => "Good afternoon{$nameString}! 🌤️",
            $this->userLocalHour < 20 => "Good evening{$nameString}! 🌆",
            default => "Night owl{$nameString}! 🌙",
        };
    }

    /**
     * Get a time-based message (without name).
     */
    public function getTimeBasedMessage(): string
    {
        return match (true) {
            $this->userLocalHour < 6 => 'Early creativity session! 🌅',
            $this->userLocalHour < 12 => 'Perfect morning for music! ☀️',
            $this->userLocalHour < 17 => 'Afternoon inspiration time! 🌤️',
            $this->userLocalHour < 20 => 'Evening vibes incoming! 🌆',
            default => 'Late night creativity! 🌙',
        };
    }

    /**
     * Initialize local time on mount.
     */
    public function initLocalTime(): void
    {
        $this->dispatch('get-local-time');
    }
}
