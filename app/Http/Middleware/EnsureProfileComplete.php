<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Profile create page pe already hain toh redirect mat karo
        if ($request->routeIs('student.profile.create') ||
            $request->routeIs('student.profile.store')) {
            return $next($request);
        }

        // Profile nahi bani toh redirect karo
        if ($user->isStudent() && !$user->studentProfile) {
            return redirect()->route('student.profile.create')
                ->with('warning', 'Please complete your profile first.');
        }

        return $next($request);
    }
}