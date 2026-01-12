<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        /** @var User $user */
        $user = auth()->user();

        if (!in_array($user->role, $roles)) {
            abort(403, 'У вас нет доступа к этому разделу');
        }

        return $next($request);
    }
}
