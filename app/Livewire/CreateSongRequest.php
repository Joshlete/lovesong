<?php

namespace App\Livewire;

use App\Models\Setting;
use App\Models\SongRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateSongRequest extends Component
{
    // Basic song information
    public $song_title = '';

    public $style = '';

    public $custom_style = '';

    public $mood = '';

    public $custom_mood = '';

    public $lyrics_idea = '';

    public $song_description = '';

    public $genre_details = '';

    public $tempo = '';

    public $vocals = '';

    public $instruments = '';

    public $song_structure = '';

    public $inspiration = '';

    public $special_instructions = '';

    // UI state

    public $isSubmitting = false;

    protected $rules = [
        'song_title' => 'required|string|max:255',
        'style' => 'required|string|max:255',
        'custom_style' => 'required_if:style,other|string|max:255',
        'mood' => 'required|string|max:255',
        'custom_mood' => 'required_if:mood,other|string|max:255',
        'lyrics_idea' => 'nullable|string|max:2000',
        'song_description' => 'required|string|max:1000',
        'genre_details' => 'nullable|string|max:500',
        'tempo' => 'nullable|string|max:100',
        'vocals' => 'nullable|string|max:200',
        'instruments' => 'nullable|string|max:500',
        'song_structure' => 'nullable|string|max:500',
        'inspiration' => 'nullable|string|max:500',

        'special_instructions' => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'song_title.required' => 'Song title is required.',
        'style.required' => 'Musical style is required.',
        'custom_style.required_if' => 'Please specify your custom style.',
        'mood.required' => 'Mood is required.',
        'custom_mood.required_if' => 'Please specify your custom mood.',
        'lyrics_idea.max' => 'Lyrics ideas cannot exceed 2000 characters.',
        'song_description.required' => 'Song description is required.',
        'song_description.max' => 'Song description cannot exceed 1000 characters.',
    ];

    public function mount(): void
    {
        // Initialize any default values if needed
    }

    public function updatedStyle(): void
    {
        if ($this->style !== 'other') {
            $this->custom_style = '';
        }
    }

    public function updatedMood(): void
    {
        if ($this->mood !== 'other') {
            $this->custom_mood = '';
        }
    }

    public function submit(): void
    {
        $this->isSubmitting = true;

        try {
            $this->validate();

            $finalStyle = $this->style === 'other' ? $this->custom_style : $this->style;
            $finalMood = $this->mood === 'other' ? $this->custom_mood : $this->mood;

            $songRequest = SongRequest::create([
                'user_id' => Auth::id(),
                'recipient_name' => $this->song_title, // Using recipient_name field for song title
                'style' => $finalStyle,
                'mood' => $finalMood,
                'lyrics_idea' => $this->lyrics_idea,
                'song_description' => $this->song_description,
                'genre_details' => $this->genre_details,
                'tempo' => $this->tempo,
                'vocals' => $this->vocals,
                'instruments' => $this->instruments,
                'song_structure' => $this->song_structure,
                'inspiration' => $this->inspiration,

                'special_instructions' => $this->special_instructions,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // Auto-send verification email if user isn't verified yet
            /** @var User $user */
            $user = Auth::user();
            if ($user && ! $user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
            }

            $message = 'Song request created successfully!';
            if ($user && ! $user->hasVerifiedEmail()) {
                $message .= ' We\'ve sent a verification email to complete your order.';
            }

            session()->flash('success', $message);
            $this->redirect(route('song-requests.show', $songRequest));

        } catch (\Exception $e) {
            $this->isSubmitting = false;
            session()->flash('error', 'There was an error creating your song request. Please try again.');
        }
    }

    public function getSongPrice(): float
    {
        return Setting::getSongPrice();
    }

    public function render()
    {
        return view('livewire.create-song-request');
    }
}
