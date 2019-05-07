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
            $table->integer('pid')->default(0);
            $table->string('name', 50);
            $table->string('url', 200);
            $table->tinyInteger('is_menu')->default(0)->comment('是否为菜单:1.是;0.否');
            $table->string('icon', 30)->default('')->comment('图标css类');
            $table->tinyInteger('status')->default(1)->comment('1.正常;0.禁用;-1.删除');
            $table->timestamps();
        });

        //初始化数据
        $this->initData();
    }

    //初始化数据
    public function initData(){
        $datetime = date('Y-m-d H:i:s');
        $rules = [
            [
                'id' => 1,
                'pid' => 0,
                'name' => '权限管理',
                'url' => '/admin/auth/index',
                'is_menu' => 1,
                'icon' => 'fa fa-lock',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ],[
                'id'    => 2,
                'pid'   => 1,
                'name'  => '用户列表',
                'url'   => '/admin/auth/user/list',
                'is_menu' => 1,
                'icon'  => '',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ],[
                'id' => 3,
                'pid' => 2,
                'name' => '用户添加',
                'url' => '/admin/auth/user/add',
                'is_menu' => 0,
                'icon' => '',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ],[
                'id' => 4,
                'pid' => 2,
                'name' => '用户编辑',
                'url' => '/admin/auth/user/update',
                'is_menu' => 0,
                'icon' => '',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ],[
                'id' => 5,
                'pid' => 1,
                'name' => '角色列表',
                'url' => '/admin/auth/group/list',
                'is_menu' => 1,
                'icon' => '',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ],[
                'id' => 6,
                'pid' => 5,
                'name' => '角色添加',
                'url' => '/admin/auth/group/add',
                'is_menu' => 0,
                'icon' => '',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ],[
                'id' => 7,
                'pid' => 5,
                'name' => '角色编辑',
                'url' => '/admin/auth/group/update',
                'is_menu' => 0,
                'icon' => '',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ],[
                'id' => 8,
                'pid' => 1,
                'name' => '菜单列表',
                'url' => '/admin/auth/menu/list',
                'is_menu' => 0,
                'icon' => '',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ], [
                'id' => 9,
                'pid' => 8,
                'name' => '菜单添加',
                'url' => '/admin/auth/menu/add',
                'is_menu' => 0,
                'icon' => '',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ], [
                'id' => 10,
                'pid' => 8,
                'name' => '菜单编辑',
                'url' => '/admin/auth/menu/update',
                'is_menu' => 0,
                'icon' => '',
                'created_at' => $datetime,
                'updated_at' => $datetime
            ]
        ];
        DB::table('ad_rule')->insert($rules);
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
