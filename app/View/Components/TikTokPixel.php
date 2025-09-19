<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TikTokPixel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $event,
        public array $data = []
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.tik-tok-pixel');
    }

    /**
     * Determine if TikTok pixel is configured.
     */
    public function isConfigured(): bool
    {
        return !empty(config('services.tiktok.pixel_id'));
    }
}