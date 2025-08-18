<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ResendVerificationButton extends Component
{
    public $emailSent = false;
    public $isLoading = false;
    public $cooldownSeconds = 0;
    public $variant = 'default'; // 'default', 'banner', 'inline'

    public function mount($variant = 'default')
    {
        $this->variant = $variant;

        // Check session state
        if (session('verification-link-sent')) {
            $this->emailSent = true;
        }
    }

    public function getButtonClass()
    {
        return match($this->variant) {
            'banner' => 'bg-white text-orange-600 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-gray-100 transition',
            'inline' => 'text-purple-600 underline hover:text-purple-700 text-sm',
            default => 'w-full bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold py-3 rounded-xl hover:from-purple-700 hover:to-pink-600 transform hover:scale-[1.02] transition shadow-lg'
        };
    }

    public function showSuccessMessage()
    {
        return $this->variant !== 'inline';
    }

    public function showProgressBar()
    {
        return $this->variant !== 'inline';
    }

    public function initializeCooldown($seconds)
    {
        $this->cooldownSeconds = $seconds;
        $this->emailSent = true;
    }

    public function sendVerificationEmail()
    {
        // Check if user is already verified
        if (Auth::user()->hasVerifiedEmail()) {
            session()->flash('message', 'Your email is already verified!');
            return;
        }

        $this->isLoading = true;

        try {
            // Send verification email
            Auth::user()->sendEmailVerificationNotification();
            
            // Set success state
            $this->emailSent = true;
            $this->cooldownSeconds = 60;
            
            // Store in session
            session(['verification-link-sent' => true]);
            
            // Start countdown
            $this->dispatch('startResendCooldown', seconds: 60);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send verification email. Please try again.');
        }

        $this->isLoading = false;
    }

    public function decrementCooldown()
    {
        if ($this->cooldownSeconds > 0) {
            $this->cooldownSeconds--;
        } else {
            $this->cooldownSeconds = 0;
        }
    }

    public function render()
    {
        return view('livewire.resend-verification-button');
    }
}