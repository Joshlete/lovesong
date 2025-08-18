<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $status;

    public ?string $size;

    /**
     * Create a new component instance.
     */
    public function __construct(string $status, ?string $size = 'sm')
    {
        $this->status = $status;
        $this->size = $size;
    }

    /**
     * Get the CSS classes for the status badge.
     */
    public function getStatusClasses(): string
    {
        $baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full font-medium';

        $sizeClasses = match ($this->size) {
            'xs' => 'text-xs',
            'sm' => 'text-xs',
            'md' => 'text-sm px-3 py-1',
            'lg' => 'text-base px-4 py-2',
            default => 'text-xs',
        };

        $statusClasses = match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'paid' => 'bg-green-100 text-green-800',
            'unpaid' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'processing' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };

        return "{$baseClasses} {$sizeClasses} {$statusClasses}";
    }

    /**
     * Format the status text for display.
     */
    public function getFormattedStatus(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.status-badge');
    }
}
