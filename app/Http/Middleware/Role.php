<?php

namespace App\Http\Middleware;

use App\Enums\ExceptionTypes;
use App\Enums\GroupRole;
use App\Exceptions\BusinessException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Providers\RouteServiceProvider;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $method = $request->method();
        $user = $request->user();

        if (!$user) {
            return redirect(RouteServiceProvider::HOME);
        }

        switch ($user->group_role) {
            case GroupRole::ADMIN:
                return $next($request);
                break;
            case GroupRole::EDITOR:
                if (in_array($method, ['GET', 'PUT', 'PATCH'])) {
                    return $next($request);
                }
                break;
            case GroupRole::REVIEWER:
                if (in_array($method, ['GET'])) {
                    return $next($request);
                }
                break;
        }
        throw new BusinessException(ExceptionTypes::NOT_ALLOW_ACCESS);
    }
}
