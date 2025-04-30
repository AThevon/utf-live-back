<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class ResendService
{
    public static function send(array $data): bool
    {
        $html = View::make('emails.contact', [
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'],
            'body' => $data['message'],
        ])->render();

        $response = Http::withToken(config('services.resend.key'))
            ->post('https://api.resend.com/emails', [
                'from' => config('mail.from.address'),
                'to' => config('mail.to.address'),
                'reply_to' => $data['email'],
                'subject' => $data['subject'],
                'html' => $html,
            ]);

        if (! $response->successful()) {
            Log::error('❌ Erreur Resend API', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return false;
        }

        return true;
    }
}
