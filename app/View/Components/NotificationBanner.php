<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NotificationBanner extends Component
{
    public string $type;

    public string $title;

    public string $message;

    public ?string $action = null;

    public ?string $actionUrl = null;

    public ?string $actionText = null;

    public ?string $emoji = null;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $type = 'info',
        string $title = '',
        string $message = '',
        ?string $action = null,
        ?string $actionUrl = null,
        ?string $actionText = null,
        ?string $emoji = null
    ) {
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->action = $action;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
        $this->emoji = $emoji;
    }

    /**
     * Get the CSS classes for the banner type.
     */
    public function getTypeClasses(): string
    {
        return match ($this->type) {
            'success' => 'from-green-400 to-emerald-500',
            'warning' => 'from-yellow-400 to-orange-500',
            'error' => 'from-red-400 to-red-500',
            'info' => 'from-blue-400 to-blue-500',
            default => 'from-gray-400 to-gray-500',
        };
    }

    /**
     * Get the icon for the banner type.
     */
    public function getIcon(): string
    {
        return match ($this->type) {
            'success' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>',
            'warning' => '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>',
            'error' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>',
            'info' => '<path fill-rule="evenodd" d="M2.94 6.412A2 2 0 002 8.108V16a2 2 0 002 2h12a2 2 0 002-2V8.108a2 2 0 00-.94-1.696l-6-3.75a2 2 0 00-2.12 0l-6 3.75zm3.56 2.123L8 7.383V5a1 1 0 112 0v2.383l1.5 1.152a1 1 0 01.5.865V12a1 1 0 11-2 0V9.4L8 8.35 6 9.4V12a1 1 0 11-2 0V9.4a1 1 0 01.5-.865z" clip-rule="evenodd"></path>',
            default => '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notification-banner');
    }
}
