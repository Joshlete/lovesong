<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function show()
    {
        return view('legal.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function store(ContactFormRequest $request)
    {
        try {
            $contactData = $request->validated();
            
            // Send email to admin
            Mail::to(config('app.admin_email'))
                ->send(new ContactFormMail($contactData));

            // Log the contact form submission
            Log::info('Contact form submitted', [
                'name' => $contactData['name'],
                'email' => $contactData['email'],
                'subject' => $contactData['subject'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()
                ->route('contact')
                ->with('success', '🎉 Thank you for your message! We\'ll get back to you within 24 hours.');

        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Sorry, there was an error sending your message. Please try again or email us directly.');
        }
    }
}