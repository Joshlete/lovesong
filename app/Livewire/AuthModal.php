<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AuthModal extends Component
{
    public $showModal = false;

    public $activeTab = 'login'; // 'login' or 'register'

    // Login fields
    public $loginEmail = '';

    public $loginPassword = '';

    public $remember = false;

    // Register fields
    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    // UI state
    public $errorMessage = '';

    public $isProcessing = false;


    protected $listeners = ['openModal', 'openLoginModal', 'openRegisterModal'];

    public function openModal($data = [])
    {
        $this->activeTab = $data['tab'] ?? 'login';
        $this->showModal = true;
        $this->resetErrorBag();
        $this->errorMessage = '';
        
        // Debug: Log what tab was requested
        \Log::info('AuthModal opened with tab: ' . $this->activeTab, $data);
    }

    public function openLoginModal()
    {
        $this->activeTab = 'login';
        $this->showModal = true;
        $this->resetErrorBag();
        $this->errorMessage = '';
    }

    public function openRegisterModal()
    {
        $this->activeTab = 'register';
        $this->showModal = true;
        $this->resetErrorBag();
        $this->errorMessage = '';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
        $this->errorMessage = '';
    }

    public function login()
    {
        $this->isProcessing = true;

        $this->validate([
            'loginEmail' => 'required|email',
            'loginPassword' => 'required',
        ], [
            'loginEmail.required' => 'Email is required',
            'loginEmail.email' => 'Please enter a valid email',
            'loginPassword.required' => 'Password is required',
        ]);

        if (Auth::attempt(['email' => $this->loginEmail, 'password' => $this->loginPassword], $this->remember)) {
            session()->regenerate();
            $this->redirect(route('dashboard'));
        } else {
            $this->errorMessage = 'Invalid email or password. Please try again.';
            $this->isProcessing = false;
        }
    }

    public function register()
    {
        $this->isProcessing = true;

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Passwords do not match',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);
        session()->regenerate();

        $this->redirect(route('dashboard'));
    }

    public function mount()
    {
        // Auto-open modal if redirected from old auth routes
        if (session('openModal')) {
            $this->activeTab = session('openModal');
            $this->showModal = true;
        }
    }

    public function render()
    {
        return view('livewire.auth-modal');
    }
}
