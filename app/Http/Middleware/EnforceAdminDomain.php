<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnforceAdminDomain
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->getHost() !== 'admin.undertheflow.com') {
            abort(403, 'Accès interdit au panneau d’administration.');
        }

        return $next($request);
    }
}
