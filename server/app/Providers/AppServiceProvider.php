<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // //模型多态映射
        // Relation::morphMap([
        //     'recharge' => 'App\Models\Recharge',
        //     'redeem_game' => 'App\Models\RedeemGame'
        // ]);

        //集合方法扩展
        Collection::macro('convert', function($level = 1) {
            return array_map(function($value) use ($level) {
                return $value->convert($level);
            }, $this->items);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
