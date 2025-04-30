<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use App\Mail\ContactFormMail;
use App\Services\ResendService;

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
      $sent = ResendService::send($validated);

      if (!$sent) {
        return response()->json([
          'error' => 'L’email n’a pas pu être envoyé.',
        ], 500);
      }

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
