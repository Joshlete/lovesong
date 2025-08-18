<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSongRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled at route level for admin
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'payment_reference' => 'nullable|string|max:255',
            'delivered_at' => 'nullable|date',
            'song_file' => [
                'nullable',
                'file',
                'max:51200', // 50MB in KB
                'mimes:mp3,wav,flac,m4a,aac,ogg',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status is required.',
            'status.in' => 'Selected status is invalid.',
            'payment_reference.string' => 'Payment reference must be a valid string.',
            'payment_reference.max' => 'Payment reference cannot exceed 255 characters.',
            'delivered_at.date' => 'Delivery date must be a valid date.',
            'song_file.file' => 'Song file must be a valid file.',
            'song_file.max' => 'Song file cannot exceed 50MB.',
            'song_file.mimes' => 'Song file must be an audio file (mp3, wav, flac, m4a, aac, ogg).',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'payment_reference' => 'payment reference',
            'delivered_at' => 'delivery date',
            'song_file' => 'song file',
        ];
    }
}
