<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        //公共
        Route::middleware('public')
            ->namespace('App\Http\Controllers')
            ->group(base_path('routes/public.php'));

        //后台
        Route::prefix('admin')
            ->middleware('admin')
            ->namespace('App\Http\Controllers\Admin')
            ->group(base_path('routes/admin.php'));

        //前台
        Route::middleware('app')
            ->namespace('App\Http\Controllers\App')
            ->group(base_path('routes/app.php'));
    }
}
