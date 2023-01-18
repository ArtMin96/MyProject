<?php

namespace App\Http\Middleware;

use Closure;

class CheckAdmin
{
    /**
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->check() and auth()->user()->hasRoles(['ROLE_SUPER_ADMIN','ROLE_ADMIN','ROLE_SHOP_MANAGER','ROLE_BLOG_MANAGER','ROLE_SUPPORT','ROLE_WAREHOUSE'])){
            return $next($request);
        }
        abort('404');
    }
}

