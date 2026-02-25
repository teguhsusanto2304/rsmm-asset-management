<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\MenuService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MenuService::class, function ($app) {
            return new MenuService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom Blade directives for menu and feature access control
        
        /**
         * Check if current user can view a specific menu
         * Usage: @canViewMenu('dashboard')
         */
        Blade::if('canViewMenu', function ($menuId) {
            $menuService = app(MenuService::class);
            return $menuService->canViewMenu($menuId, auth()->user());
        });

        /**
         * Check if current user can access a feature
         * Usage: @canAccessFeature('asset.create')
         */
        Blade::if('canAccessFeature', function ($featureName) {
            $menuService = app(MenuService::class);
            return $menuService->canAccessFeature($featureName, auth()->user());
        });

        /**
         * Check for specific role
         * Usage: @hasRole('admin')
         */
        Blade::if('hasRole', function ($roles) {
            if (!auth()->check()) {
                return false;
            }
            
            if (is_string($roles)) {
                return auth()->user()->hasRole($roles);
            }
            
            return auth()->user()->hasAnyRole((array)$roles);
        });

        /**
         * Check for specific permission
         * Usage: @hasPermission('edit_asset')
         */
        Blade::if('hasPermission', function ($permissions) {
            if (!auth()->check()) {
                return false;
            }
            
            if (is_string($permissions)) {
                return auth()->user()->hasPermissionTo($permissions);
            }
            
            return auth()->user()->hasAnyPermission((array)$permissions);
        });
    }
}
