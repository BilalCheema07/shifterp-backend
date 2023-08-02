<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Middleware\IdentifyTenantUsingAuth;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/



Route::prefix('tenant/api')->middleware([
	'api',
	'auth:sanctum',
	IdentifyTenantUsingAuth::class,
	// InitializeTenancyByDomain::class,
	// PreventAccessFromCentralDomains::class
])->group(base_path('routes/tenant/api.php'));

// Route:: middleware([
// 	'api',
// 	IdentifyTenantUsingAuth::class,
// 	// InitializeTenancyByDomain::class,
// 	// PreventAccessFromCentralDomains::class
// ])->group(base_path('routes/tenant/web.php') );

// Route::prefix('admin')->middleware([
// 	'web',
// 	'auth:sanctum',
// 	IdentifyTenantUsingAuth::class,
// 	// InitializeTenancyByDomain::class,
// 	// PreventAccessFromCentralDomains::class
// ])->group(base_path('routes/tenant/admin.php') );


