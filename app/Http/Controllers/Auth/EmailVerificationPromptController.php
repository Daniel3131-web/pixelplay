<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {   
        $user = $request->user();

        if ($user->role === 'player') {
            return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('player.eventos', absolute: false))
                    : view('auth.verify-email');
        } elseif ($user->role === 'organizador') {
            return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('player.eventos', absolute: false))
                    : view('auth.verify-email');
        }

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('/', absolute: false))
                    : view('auth.verify-email');
    }
}
