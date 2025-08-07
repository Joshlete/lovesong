<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SongRequest;
use Illuminate\Http\Request;

class SongRequestController extends Controller
{
    /**
     * Display a listing of all song requests for admin.
     */
    public function index(Request $request)
    {
        $query = SongRequest::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%')
                  ->orWhere('email', 'like', '%' . $request->user . '%');
            });
        }

        // Search by recipient name
        if ($request->filled('search')) {
            $query->where('recipient_name', 'like', '%' . $request->search . '%');
        }

        $songRequests = $query->latest()->paginate(10);

        return view('admin.song-requests.index', compact('songRequests'));
    }

    /**
     * Display the specified song request for admin.
     */
    public function show(SongRequest $songRequest)
    {
        $songRequest->load('user');
        
        return view('admin.song-requests.show', compact('songRequest'));
    }

    /**
     * Show the form for editing the specified song request.
     */
    public function edit(SongRequest $songRequest)
    {
        $songRequest->load('user');
        
        return view('admin.song-requests.edit', compact('songRequest'));
    }

    /**
     * Update the specified song request in storage.
     */
    public function update(Request $request, SongRequest $songRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'payment_reference' => 'nullable|string|max:255',
            'file_url' => 'nullable|url',
            'delivered_at' => 'nullable|date',
        ]);

        // Auto-set delivered_at when status changes to completed
        if ($validated['status'] === 'completed' && !$validated['delivered_at']) {
            $validated['delivered_at'] = now();
        }

        // Clear delivered_at if status is not completed
        if ($validated['status'] !== 'completed') {
            $validated['delivered_at'] = null;
        }

        $songRequest->update($validated);

        return redirect()->route('admin.song-requests.show', $songRequest)
            ->with('success', 'Song request updated successfully!');
    }

    /**
     * Remove the specified song request from storage.
     */
    public function destroy(SongRequest $songRequest)
    {
        $songRequest->delete();

        return redirect()->route('admin.song-requests.index')
            ->with('success', 'Song request deleted successfully!');
    }

    /**
     * Update the status of a song request via AJAX.
     */
    public function updateStatus(Request $request, SongRequest $songRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $updates = ['status' => $validated['status']];

        // Auto-set delivered_at when status changes to completed
        if ($validated['status'] === 'completed') {
            $updates['delivered_at'] = now();
        } elseif ($validated['status'] !== 'completed') {
            $updates['delivered_at'] = null;
        }

        $songRequest->update($updates);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'status' => $songRequest->status,
            'delivered_at' => $songRequest->delivered_at?->format('M j, Y g:i A'),
        ]);
    }
}
