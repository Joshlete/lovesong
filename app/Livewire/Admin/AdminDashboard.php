<?php

namespace App\Livewire\Admin;

use App\Models\SongRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminDashboard extends Component
{
    public int $recentRequestsLimit = 10;

    /**
     * Get admin dashboard statistics.
     */
    public function getStatsProperty(): array
    {
        return [
            'total_requests' => SongRequest::count(),
            'pending_requests' => SongRequest::where('status', 'pending')->count(),
            'in_progress_requests' => SongRequest::where('status', 'in_progress')->count(),
            'completed_requests' => SongRequest::where('status', 'completed')->count(),
            'total_users' => User::count(),
            'total_revenue' => SongRequest::where('payment_status', 'succeeded')->sum('price_usd'),
        ];
    }

    /**
     * Get recent song requests with user information.
     */
    public function getRecentRequestsProperty()
    {
        return SongRequest::with('user')
            ->latest()
            ->take($this->recentRequestsLimit)
            ->get()
            ->map(function ($request) {
                $request->time_ago = $request->created_at->diffForHumans();
                $request->formatted_date = $request->created_at->format('M d, Y');

                return $request;
            });
    }

    /**
     * Get pending requests that need attention.
     */
    public function getPendingRequestsProperty()
    {
        return SongRequest::with('user')
            ->where('status', 'pending')
            ->where('payment_status', 'succeeded')
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Get revenue analytics.
     */
    public function getRevenueAnalyticsProperty(): array
    {
        $today = now()->startOfDay();
        $thisWeek = now()->startOfWeek();
        $thisMonth = now()->startOfMonth();

        return [
            'today' => SongRequest::where('payment_status', 'succeeded')
                ->where('payment_completed_at', '>=', $today)
                ->sum('price_usd'),
            'this_week' => SongRequest::where('payment_status', 'succeeded')
                ->where('payment_completed_at', '>=', $thisWeek)
                ->sum('price_usd'),
            'this_month' => SongRequest::where('payment_status', 'succeeded')
                ->where('payment_completed_at', '>=', $thisMonth)
                ->sum('price_usd'),
        ];
    }

    /**
     * Get popular song styles.
     */
    public function getPopularStylesProperty()
    {
        return SongRequest::select('style', DB::raw('count(*) as total'))
            ->whereNotNull('style')
            ->where('style', '!=', '')
            ->groupBy('style')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
    }

    /**
     * Refresh dashboard data.
     */
    public function refreshData(): void
    {
        // This will trigger a re-render with fresh data
        $this->dispatch('admin-dashboard-refreshed');
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard', [
            'stats' => $this->stats,
            'recentRequests' => $this->recentRequests,
            'pendingRequests' => $this->pendingRequests,
            'revenueAnalytics' => $this->revenueAnalytics,
            'popularStyles' => $this->popularStyles,
        ]);
    }
}
