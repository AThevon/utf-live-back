<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    if (app()->environment('local')) {
      // Routes web (Filament/admin)
      Route::middleware('web')
        ->group(base_path('routes/web.php'));

      // Routes API
      Route::middleware('api')
        ->prefix('api')
        ->group(base_path('routes/api.php'));
    } else {
      // Routes web (admin.undertheflow.com)
      Route::middleware('web')
        ->domain('admin.undertheflow.com')
        ->group(base_path('routes/web.php'));

      // Routes API (api.undertheflow.com)
      Route::middleware('api')
        ->domain('api.undertheflow.com')
        ->prefix('api') 
        ->group(base_path('routes/api.php'));
    }
  }
}
