<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            if (app()->environment('local')) {
                Route::middleware('web')
                    ->group(base_path('routes/web.php'));

                Route::middleware('api')
                    ->prefix('api')
                    ->group(base_path('routes/api.php'));
            } else {
                Route::middleware('web')
                    ->domain('admin.undertheflow.com')
                    ->group(base_path('routes/web.php'));

                Route::middleware('api')
                    ->domain('api.undertheflow.com')
                    ->prefix('api')
                    ->group(base_path('routes/api.php'));
            }
        });
    }
}