<?php

namespace App\Models;

use App\Services\S3FileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongRequest extends Model
{
    /** @use HasFactory<\Database\Factories\SongRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'recipient_name',
        'style',
        'mood',
        'lyrics_idea',
        'song_description',
        'genre_details',
        'tempo',
        'vocals',
        'instruments',
        'song_structure',
        'inspiration',

        'special_instructions',
        'price_usd',
        'currency',
        'status',
        'payment_reference',
        'payment_intent_id',
        'stripe_checkout_session_id',
        'payment_status',
        'payment_completed_at',
        'file_url',
        'file_path',
        'file_size',
        'original_filename',
        'delivered_at',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'payment_completed_at' => 'datetime',
        'price_usd' => 'decimal:2',
        'file_size' => 'integer',
    ];

    /**
     * Get the user that owns the song request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a fresh download URL for the song file (short expiration for immediate use)
     */
    public function generateFreshDownloadUrl(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        $s3Service = app(S3FileService::class);

        // Use original filename if available, otherwise use the stored filename
        $downloadFilename = $this->original_filename ?: $this->getDisplayFilename();

        return $s3Service->getDownloadUrl($this->file_path, 5, $downloadFilename); // 5 minutes - just for the immediate download
    }

    /**
     * Check if the song has a file uploaded to S3
     */
    public function hasS3File(): bool
    {
        return ! empty($this->file_path);
    }

    /**
     * Check if the song has a file
     */
    public function hasFile(): bool
    {
        return $this->hasS3File();
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): ?string
    {
        if (! $this->file_size) {
            return null;
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }

    /**
     * Get the display filename for the user
     */
    public function getDisplayFilename(): ?string
    {
        if ($this->original_filename) {
            return $this->original_filename;
        }

        if ($this->file_path) {
            return basename($this->file_path);
        }

        return null;
    }

    /**
     * Boot the model
     */
    protected static function booted()
    {
        // Automatically set price from admin settings when creating new requests
        static::creating(function ($songRequest) {
            if (empty($songRequest->price_usd)) {
                $songRequest->price_usd = Setting::getSongPrice();
            }
        });

        // Delete the S3 file when the song request is deleted
        static::deleting(function ($songRequest) {
            if ($songRequest->file_path) {
                $s3Service = app(S3FileService::class);
                $s3Service->deleteSong($songRequest->file_path);
            }
        });
    }
}
