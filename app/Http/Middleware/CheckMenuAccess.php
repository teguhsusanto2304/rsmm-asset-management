<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\MenuService;

class CheckMenuAccess
{
    /**
     * Handle an incoming request.
     * 
     * Usage in routes:
     * Route::get('/assets', [AssetController::class, 'index'])->middleware('check.menu:asset_management');
     */
    public function handle(Request $request, Closure $next, string $menuId): Response
    {
        $menuService = app(MenuService::class);
        
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (!$menuService->canViewMenu($menuId, auth()->user())) {
            abort(403, 'Anda tidak memiliki akses ke menu ini.');
        }

        return $next($request);
    }
}
