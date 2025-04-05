<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
  public function send(Request $request): JsonResponse
  {
    $validated = $request->validate([
      'name' => 'required|string|max:100',
      'email' => 'required|email|max:150',
      'subject' => 'required|string|max:150',
      'message' => 'required|string|max:3000',
    ]);

    try {
      $recipient = config('mail.to.address');

      if (!$recipient) {
        Log::warning('📭 MAIL_TO_ADDRESS manquant dans .env');
        return response()->json([
          'error' => 'Configuration d’envoi incomplète.',
        ], 500);
      }

      Mail::to($recipient)->send(new ContactFormMail($validated));

      Log::info('📨 Mail SMTP envoyé avec succès', [
        'to' => $recipient,
        'from' => $validated['email'],
        'subject' => $validated['subject'],
      ]);

      return response()->json([
        'message' => 'Message envoyé avec succès',
      ]);
    } catch (\Throwable $e) {
      Log::error('❌ Échec d’envoi du message de contact', [
        'error' => $e->getMessage(),
        'payload' => $validated,
      ]);

      return response()->json([
        'error' => 'Une erreur est survenue lors de l’envoi du message.',
      ], 500);
    }
  }
}
