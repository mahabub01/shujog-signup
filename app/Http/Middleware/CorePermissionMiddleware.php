<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Routing\Route;
use Modules\Core\Services\PermissionAccessService;
use Illuminate\Http\Response;

class CorePermissionMiddleware
{
    protected $auth;
    protected $route;
    public function __construct(Guard $auth, Route $route) {
        $this->auth = $auth;
        $this->route = $route;
    }

    public function handle($request, Closure $next)
    {

        if(!PermissionAccessService::canAccess($this->route->getActionName(), $this->auth->user())){
            return new Response('<h1 style="margin-top: 150px;color:dimgray"><center>401<br>ACCESS DENIED</center></h1>', 401);
        }
        return $next($request);
    }

}
