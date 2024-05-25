<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class MiddlewareFilamentAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $auth = Filament::auth();

        if (!$auth->check()) {
            return redirect(route('login'));
        }

        Auth::shouldUse(Filament::getAuthGuard());

        /** @var Model $user */
        $user = $auth->user();


        $panel = Filament::getCurrentPanel();

        if ($user instanceof FilamentUser) {

            if ($user->role !== 'ADMIN') {
                return redirect('admin/appointments');
            }


            // if (!$user->canAccessPanel($panel) && config('app.env') !== 'local') {
            //     return redirect(route('user.home'));
            // }
        }

        return $next($request);
    }
}
