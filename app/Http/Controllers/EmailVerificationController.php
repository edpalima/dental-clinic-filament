<?php

namespace App\Http\Controllers;

use App\Models\User;
use Filament\Notifications\Notification;

class EmailVerificationController extends Controller
{
    /**
     * Handle email verification.
     *
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify($token)
    {
        // Find the user by the token
        $user = User::where('remember_token', $token)->first();

        if (!$user) {
            Notification::make()
                ->title('Invalid or expired verification link.')
                ->danger()
                ->send();

            return redirect()->route('filament.admin.auth.login');
        }

        // Mark the user's email as verified
        $user->email_verified_at = now();
        // $user->remember_token = null; // Clear the token
        $user->save();

        Notification::make()
            ->title('Registered successfully')
            ->success()
            ->send();

        // Redirect to login with success message
        return redirect()->route('filament.admin.auth.login');
    }
}
