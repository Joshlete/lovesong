<?php

namespace App\Models;

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
        'price_usd',
        'currency',
        'status',
        'payment_reference',
        'payment_intent_id',
        'stripe_checkout_session_id',
        'payment_status',
        'payment_completed_at',
        'file_url',
        'delivered_at',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
        'payment_completed_at' => 'datetime',
        'price_usd'    => 'decimal:2',
    ];

    /**
     * Get the user that owns the song request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
