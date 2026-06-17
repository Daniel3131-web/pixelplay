<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user(); 

        if ($user->hasVerifiedEmail()) {
            if ($user->role === 'player') {
                return redirect()->intended(route('player.torneios') . '?verified=1');
            }
            if ($user->role === 'organizador') {
                return redirect()->intended(route('org.dashboard') . '?verified=1');
            }
            return redirect('/' . '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        if ($user->role === 'player') {
            return redirect()->intended(route('player.torneios') . '?verified=1');
        }

        if ($user->role === 'organizador') {
            return redirect()->intended(route('org.dashboard') . '?verified=1');
        }

        // Fallback caso seja um admin ou role indefinida
        return redirect('/' . '?verified=1');
    }
}