<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * 注册应用程序的响应宏。
     *
     * @return void
     */
    public function boot()
    {   
        Response::macro('api', function ($value = 'Oh yes!') {
            return Response::json([
                'errno' => 0,
                'errmsg' => '',
                'data' => $value
            ]);
        });
    }
}