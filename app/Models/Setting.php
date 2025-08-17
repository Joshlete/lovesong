<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        
        if (! $setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value by key
     */
    public static function set(string $key, mixed $value, string $type = 'string', ?string $description = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );
    }

    /**
     * Get the current song price
     */
    public static function getSongPrice(): float
    {
        return (float) static::get('song_price_usd', 5.00);
    }

    /**
     * Set the song price
     */
    public static function setSongPrice(float $price): void
    {
        static::set('song_price_usd', $price, 'decimal', 'Default price for custom songs in USD');
    }

    /**
     * Cast value to the appropriate type
     */
    private static function castValue(string $value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'number', 'decimal' => is_numeric($value) ? (float) $value : 0,
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}