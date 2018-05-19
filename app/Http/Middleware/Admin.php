<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // se verifica daca userul este logat si daca are rol de admin
        if (Auth::guard($guard)->check() AND !Auth::user()->admin) {
            // in caz contrar se redirectioneaza catre pagina principala
            return redirect('/');
        }

        return $next($request);
    }
}
