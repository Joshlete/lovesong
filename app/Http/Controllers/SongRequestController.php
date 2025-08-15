<?php

namespace App\Http\Controllers;

use App\Models\SongRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SongRequestController extends Controller
{
    /**
     * Display a listing of the user's song requests.
     */
    public function index()
    {
        $songRequests = Auth::user()->songRequests()->latest()->paginate(10);
        
        return view('song-requests.index', compact('songRequests'));
    }

    /**
     * Show the form for creating a new song request.
     */
    public function create()
    {
        return view('song-requests.create');
    }

    /**
     * Store a newly created song request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'style' => 'nullable|string|max:255',
            'mood' => 'nullable|string|max:255',
            'lyrics_idea' => 'nullable|string',
            'price_usd' => 'required|numeric|min:0',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['payment_status'] = 'pending';

        $songRequest = SongRequest::create($validated);

        return redirect()->route('song-requests.show', $songRequest)
            ->with('success', 'Song request created successfully!');
    }

    /**
     * Display the specified song request.
     */
    public function show(SongRequest $songRequest)
    {
        // Ensure user can only view their own song requests
        if ($songRequest->user_id !== Auth::id()) {
            abort(404);
        }

        return view('song-requests.show', compact('songRequest'));
    }

    /**
     * Show the form for editing the specified song request.
     */
    public function edit(SongRequest $songRequest)
    {
        // Ensure user can only edit their own song requests
        if ($songRequest->user_id !== Auth::id()) {
            abort(404);
        }

        // Only allow editing if the request is still pending
        if ($songRequest->status !== 'pending') {
            return redirect()->route('song-requests.show', $songRequest)
                ->with('error', 'You can only edit pending song requests.');
        }

        return view('song-requests.edit', compact('songRequest'));
    }

    /**
     * Update the specified song request in storage.
     */
    public function update(Request $request, SongRequest $songRequest)
    {
        // Ensure user can only update their own song requests
        if ($songRequest->user_id !== Auth::id()) {
            abort(404);
        }

        // Only allow updating if the request is still pending
        if ($songRequest->status !== 'pending') {
            return redirect()->route('song-requests.show', $songRequest)
                ->with('error', 'You can only edit pending song requests.');
        }

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'style' => 'nullable|string|max:255',
            'mood' => 'nullable|string|max:255',
            'lyrics_idea' => 'nullable|string',
            'price_usd' => 'required|numeric|min:0',
        ]);

        $songRequest->update($validated);

        return redirect()->route('song-requests.show', $songRequest)
            ->with('success', 'Song request updated successfully!');
    }

    /**
     * Remove the specified song request from storage.
     */
    public function destroy(SongRequest $songRequest)
    {
        // Ensure user can only delete their own song requests
        if ($songRequest->user_id !== Auth::id()) {
            abort(404);
        }

        // Only allow deletion if the request is still pending
        if ($songRequest->status !== 'pending') {
            return redirect()->route('song-requests.show', $songRequest)
                ->with('error', 'You can only delete pending song requests.');
        }

        $songRequest->delete();

        return redirect()->route('song-requests.index')
            ->with('success', 'Song request deleted successfully!');
    }

    /**
     * Download song file for the user
     */
    public function download(SongRequest $songRequest)
    {
        // Ensure user can only download their own completed songs
        if ($songRequest->user_id !== Auth::id()) {
            abort(404);
        }

        if ($songRequest->status !== 'completed') {
            abort(403, 'Song is not yet completed');
        }

        // Check for S3 file first
        if ($songRequest->hasS3File()) {
            $s3Service = app(\App\Services\S3FileService::class);
            
            if (!$s3Service->songExists($songRequest->file_path)) {
                abort(404, 'File not found in storage');
            }

            return redirect($songRequest->download_url);
        }

        // Fallback to legacy file_url
        if ($songRequest->file_url) {
            return redirect($songRequest->file_url);
        }

        abort(404, 'No file available for download');
    }
}
