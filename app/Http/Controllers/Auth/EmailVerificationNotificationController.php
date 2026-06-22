<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        // Pega o usuário direto da Request (Mais limpo e rápido)
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {

            if ($user->role === 'player') {
                return redirect()->intended(route('player.eventos'));
            } elseif ($user->role === 'organizador') {
                return redirect()->intended(route('org.dashboard'));
            }

            // Fallback caso seja um admin ou role indefinida
            return redirect('/');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
