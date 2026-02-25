<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\MenuService;

class CheckFeatureAccess
{
    /**
     * Handle an incoming request.
     * 
     * Usage in routes:
     * Route::post('/assets', [AssetController::class, 'store'])->middleware('check.feature:asset.create');
     */
    public function handle(Request $request, Closure $next, string $featureName): Response
    {
        $menuService = app(MenuService::class);
        
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (!$menuService->canAccessFeature($featureName, auth()->user())) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        return $next($request);
    }
}
