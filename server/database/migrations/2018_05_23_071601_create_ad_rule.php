<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_rule', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('url', 200);
            $table->tinyInteger('status')->default(1)->comment('1.正常;0.禁用;-1.删除');
            $table->timestamps();
        });

        $datetime   = date('Y-m-d H:i:s');

        //数据插入
        DB::table('ad_rule')->insert([[
            'id'    => 1,
            'name'  => '权限管理',
            'url'   => 'admin/auth/index',
            'created_at'    => $datetime,
            'updated_at'    => $datetime
        ],[
            'id'    => 2,
            'name'  => '用户列表',
            'url'   => 'admin/auth/user/list',
            'created_at'    => $datetime,
            'updated_at'    => $datetime
        ],[
            'id'    => 3,
            'name'  => '角色列表',
            'url'   => 'admin/auth/group/list',
            'created_at'    => $datetime,
            'updated_at'    => $datetime,
        ],[
            'id'    => 4,
            'name'  => '菜单列表',
            'url'   => 'admin/auth/menu/list',
            'created_at'    => $datetime,
            'updated_at'    => $datetime
        ]]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_rule');
    }
}
