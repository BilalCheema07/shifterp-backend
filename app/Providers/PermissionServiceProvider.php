<?php

namespace App\Providers;

use App\Models\Tenant\Permission;
use Illuminate\Support\Facades\Gate as FacadesGate;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try{
            Permission::get()->map(function($permission){
                FacadesGate::define($permission->slug, function($user) use($permission){
                    return $user->hasPermissionTo($permission);
                });
            });
        }catch(\Exception $e){
            report($e);
            return false;
        }
        
    }
}
