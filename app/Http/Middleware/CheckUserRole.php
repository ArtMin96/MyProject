<?php

namespace App\Http\Middleware;

use App\Role\RoleChecker;
use App\User;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CheckUserRole
{
    /**
     * @var RoleChecker
     */
    protected $roleChecker;

    public function __construct(RoleChecker $roleChecker)
    {
        $this->roleChecker = $roleChecker;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $role
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, $role)
    {
        $roles = explode('|',$role);
        /** @var User $user */
        $user = Auth::guard()->user();

        foreach ($roles as $rol){
            if ($this->roleChecker->check($user, $rol)) {
                return $next($request);
            }
        }
        abort('403','You do not have permission to view this page');

    }
}
