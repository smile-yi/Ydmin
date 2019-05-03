<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->nullable()->default(0)->comment('父id');
            $table->string('name', 50)->comment('名称');
            $table->string('url', 200)->nullable()->comment('地址');
            $table->integer('rule_id')->nullable()->comment('权限ID');
            $table->tinyInteger('show')->default(1)->comment('1.显示;0.隐藏');
            $table->string('icon')->nullable()->comment('图标css类');
            $table->tinyInteger('status')->default(1)->comment('1.正常;-1.删除');
            $table->timestamps();

            $table->index('pid');
        });

        //数据插入
        $datetime   = date('Y-m-d H:i:s');
        DB::table('ad_menu')->insert([[
            'id'    => 1,
            'pid'   => 0,
            'name'  => '权限管理',
            'url'   => 'admin/auth/index',
            'rule_id'   => 1,
            'show'  => 1,
            'icon'  => 'fa fa-lock',
            'created_at'    => $datetime,
            'updated_at'    => $datetime
        ],[
            'id'    => 2,
            'pid'   => 1,
            'name'  => '用户列表',
            'url'   => 'admin/auth/user/list',
            'rule_id'   => 2,
            'show'  => 1,
            'icon'  => '',
            'created_at'    => $datetime,
            'updated_at'    => $datetime
        ],[
            'id'    => 3,
            'pid'   => 1,
            'name'  => '角色列表',
            'url'   => 'admin/auth/group/list',
            'rule_id'   => 3,
            'show'  => 1,
            'icon'  => '',
            'created_at'    => $datetime,
            'updated_at'    => $datetime
        ],[
            'id'    => 4,
            'pid'   => 1,
            'name'  => '菜单列表',
            'url'   => 'admin/auth/menu/list',
            'rule_id'   => 4,
            'show'  => 1,
            'icon'  => '',
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
        Schema::dropIfExists('ad_menu');
    }
}