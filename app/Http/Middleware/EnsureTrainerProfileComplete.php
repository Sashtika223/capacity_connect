<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTrainerProfileComplete
{
    /**
     * Handle an incoming request.
     * Redirects trainers with incomplete profiles (<100%) to the profile page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->isTrainer()) {
            $profile = $user->trainerProfile;
            $isProfileRoute = $request->routeIs('trainer.profile*') || $request->routeIs('logout');

            if ((! $profile || ! $profile->isComplete()) && ! $isProfileRoute) {
                return redirect()->route('trainer.profile')
                    ->with('warning', 'Complete your trainer profile to 100% to become eligible for certification.');
            }
        }

        return $next($request);
    }
}
