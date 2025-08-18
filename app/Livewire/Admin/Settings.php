<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class Settings extends Component
{
    public $song_price;

    protected $rules = [
        'song_price' => 'required|numeric|min:1|max:999.99',
    ];

    protected $messages = [
        'song_price.required' => 'Song price is required.',
        'song_price.numeric' => 'Song price must be a valid number.',
        'song_price.min' => 'Song price must be at least $1.00.',
        'song_price.max' => 'Song price cannot exceed $999.99.',
    ];

    public function mount(): void
    {
        $this->song_price = Setting::getSongPrice();
    }

    public function save(): void
    {
        $this->validate();

        Setting::setSongPrice((float) $this->song_price);

        session()->flash('success', 'Settings updated successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings');
    }
}