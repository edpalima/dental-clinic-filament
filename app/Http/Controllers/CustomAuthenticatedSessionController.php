<?php

namespace App\Http\Controllers;

use App\Models\User;
use Filament\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Http\Request;

class CustomAuthenticatedSessionController extends AuthenticatedSessionController
{
    /**
     * Handle the post-authentication redirection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role == User::ROLE_PATIENT) {
            return redirect()->intended('/appointment-page'); // Change this to your appointment page path
        }

        // Default redirect path
        return redirect()->intended('/dashboard'); // Change this to your default redirect path
    }
}
