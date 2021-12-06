<?php

namespace AwemaPL\Allegro\Admin\Sections\Installations\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use AwemaPL\Allegro\Facades\Allegro;

class Installation
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Allegro::canInstallation()){
            $route = Route::getRoutes()->match($request);
            $name = $route->getName();
            if (!in_array($name, config('allegro.routes.admin.installation.expect'))){
                return redirect()->route('allegro.admin.installation.index');
            }
        }
        return $next($request);
    }
}
