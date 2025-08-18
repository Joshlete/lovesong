<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PsychCard extends Component
{
    public string $variant;

    public string $title;

    public ?string $subtitle;

    public ?string $emoji;

    public ?string $gradient;

    public bool $pulsing;

    public bool $hoverable;

    public ?string $action;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $variant = 'default',
        string $title = '',
        ?string $subtitle = null,
        ?string $emoji = null,
        ?string $gradient = null,
        bool $pulsing = false,
        bool $hoverable = true,
        ?string $action = null
    ) {
        $this->variant = $variant;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->emoji = $emoji;
        $this->gradient = $gradient;
        $this->pulsing = $pulsing;
        $this->hoverable = $hoverable;
        $this->action = $action;
    }

    /**
     * Get the gradient classes for the card variant.
     */
    public function getGradientClasses(): string
    {
        if ($this->gradient) {
            return $this->gradient;
        }

        return match ($this->variant) {
            'success' => 'from-green-400 via-emerald-500 to-teal-500',
            'warning' => 'from-yellow-400 via-orange-400 to-red-400',
            'info' => 'from-blue-400 via-purple-500 to-pink-500',
            'celebration' => 'from-purple-400 via-pink-500 to-red-500',
            'achievement' => 'from-indigo-400 via-purple-500 to-pink-500',
            'progress' => 'from-cyan-400 via-blue-500 to-indigo-500',
            'social' => 'from-pink-400 via-red-400 to-orange-400',
            default => 'from-gray-100 to-gray-200',
        };
    }

    /**
     * Get the base card classes.
     */
    public function getCardClasses(): string
    {
        $baseClasses = 'relative overflow-hidden rounded-2xl shadow-xl backdrop-blur-sm border border-white/20';

        $hoverClasses = $this->hoverable ? 'transform hover:scale-105 transition-all duration-300 cursor-pointer' : '';

        $pulseClasses = $this->pulsing ? 'animate-pulse' : '';

        $gradientClasses = $this->variant === 'default' ? 'bg-white' : 'bg-gradient-to-br '.$this->getGradientClasses();

        return trim("{$baseClasses} {$hoverClasses} {$pulseClasses} {$gradientClasses}");
    }

    /**
     * Get text color classes based on variant.
     */
    public function getTextClasses(): string
    {
        return $this->variant === 'default' ? 'text-gray-900' : 'text-white';
    }

    /**
     * Get subtitle text classes.
     */
    public function getSubtitleClasses(): string
    {
        return $this->variant === 'default' ? 'text-gray-600' : 'text-white/80';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.psych-card');
    }
}
