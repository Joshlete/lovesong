<?php

namespace App\Livewire;

use App\Models\SongRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditSongRequest extends Component
{
    public SongRequest $songRequest;

    // Form fields matching the enhanced CreateSongRequest
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
        'song_description.required' => 'Song description is required.',
        'lyrics_idea.max' => 'Lyrics ideas cannot exceed 2000 characters.',
        'song_description.max' => 'Song description cannot exceed 1000 characters.',
    ];

    public function mount(SongRequest $songRequest): void
    {
        // Ensure user can only edit their own song requests
        if ($songRequest->user_id !== Auth::id()) {
            abort(404);
        }

        // Only allow editing if the request is still pending
        if ($songRequest->status !== 'pending') {
            session()->flash('error', 'You can only edit pending song requests.');
            $this->redirect(route('song-requests.show', $songRequest));

            return;
        }

        $this->songRequest = $songRequest;

        // Populate form fields
        $this->song_title = $songRequest->recipient_name;
        $this->style = $songRequest->style;
        $this->mood = $songRequest->mood;
        $this->lyrics_idea = $songRequest->lyrics_idea;
        $this->song_description = $songRequest->song_description;
        $this->genre_details = $songRequest->genre_details;
        $this->tempo = $songRequest->tempo;
        $this->vocals = $songRequest->vocals;
        $this->instruments = $songRequest->instruments;
        $this->song_structure = $songRequest->song_structure;
        $this->inspiration = $songRequest->inspiration;
        $this->special_instructions = $songRequest->special_instructions;
    }

    public function getShowCustomStyleProperty(): bool
    {
        return $this->style === 'other';
    }

    public function getShowCustomMoodProperty(): bool
    {
        return $this->mood === 'other';
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

    public function update(): void
    {
        $this->isSubmitting = true;

        try {
            $this->validate();

            $finalStyle = $this->style === 'other' ? $this->custom_style : $this->style;
            $finalMood = $this->mood === 'other' ? $this->custom_mood : $this->mood;

            $this->songRequest->update([
                'recipient_name' => $this->song_title,
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
            ]);

            session()->flash('success', 'Song request updated successfully!');
            $this->redirect(route('song-requests.show', $this->songRequest));

        } catch (\Exception $e) {
            $this->isSubmitting = false;
            session()->flash('error', 'There was an error updating your song request. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.edit-song-request');
    }
}
