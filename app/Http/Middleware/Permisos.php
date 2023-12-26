<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use App\Models\Modules;
use App\Models\Permission_module_user;
use App\Models\Permission;
use RealRashid\SweetAlert\Facades\Alert;

class Permisos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $controlador)
    {
        $user = Auth::user();
        return $next($request);
    }
}
