<?php

namespace App\Constants;

class SongRequestStatus
{
    public const PENDING = 'pending';

    public const IN_PROGRESS = 'in_progress';

    public const COMPLETED = 'completed';

    public const CANCELLED = 'cancelled';

    /**
     * Get all statuses.
     */
    public static function all(): array
    {
        return [
            self::PENDING,
            self::IN_PROGRESS,
            self::COMPLETED,
            self::CANCELLED,
        ];
    }

    /**
     * Get status labels for UI display.
     */
    public static function labels(): array
    {
        return [
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Get status colors for UI components.
     */
    public static function colors(): array
    {
        return [
            self::PENDING => 'yellow',
            self::IN_PROGRESS => 'blue',
            self::COMPLETED => 'green',
            self::CANCELLED => 'red',
        ];
    }

    /**
     * Get status emojis.
     */
    public static function emojis(): array
    {
        return [
            self::PENDING => '⏳',
            self::IN_PROGRESS => '🎨',
            self::COMPLETED => '🎉',
            self::CANCELLED => '❌',
        ];
    }
}
