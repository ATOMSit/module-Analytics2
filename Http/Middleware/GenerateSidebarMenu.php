<?php

namespace Modules\Analytics\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateSidebarMenu
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $menu = \Menu::get('sidebar_menu');
        $menu->add('AUDIENCE', ['icon' => 'fas fa-chart-area', 'id' => "user-1", 'route' => ['admin.analytics.index', 'website' => \Request::segment(1)]]);
        return $next($request);
    }
}