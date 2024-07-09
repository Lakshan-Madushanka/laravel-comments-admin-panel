<?php

namespace LakM\CommentsAdminPanel\Middleware;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AdminPanelAccessMiddleware
{
    /**
     * @throws AuthorizationException
     */
    public function handle(Request $request, \Closure $next)
    {
        $this->isAdminPanelAccessible();

        return $next($request);
    }

    /**
     * @throws AuthorizationException
     */
    public function isAdminPanelAccessible(): bool
    {
        if (!config('comments-admin-panel.enabled')) {
            $this->throwException();
        }

        if (!App::isProduction()) {
            return true;
        }

        $authGuard = config('comments.auth_guard') === 'default' ? Auth::getDefaultDriver() : config('comments.auth_guard');

        $user = Auth::guard($authGuard)->user();

        if ($user && method_exists($user, 'canAccessAdminPanel') && Auth::guard($authGuard)->user()?->canAccessAdminPanel()) {
            return true;
        }

        $this->throwException();
    }

    /**
     * @throws AuthorizationException
     */
    public function throwException()
    {
        throw (new AuthorizationException())->withStatus(404);
    }
}
