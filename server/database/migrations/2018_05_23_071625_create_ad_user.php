<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use SmileYi\Utils\Common;

class CreateAdUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 100);
            $table->string('password', 100);
            $table->string('nickname', 100)->nullable();
            $table->string('truename', 100)->nullable();
            $table->string('avatar', 100)->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->string('email', 100)->nullable();
            $table->smallInteger('login_count')->nullable();
            $table->dateTime('last_login_time')->nullable();
            $table->bigInteger('last_login_ip')->nullable();
            $table->string('token', 100)->nullable();
            $table->dateTime('token_deadline')->nullable()->comment('token结束日期');
            $table->tinyInteger('status')->nullable()->default(1)
                ->comment('状态:1.正常;0.禁用;-1.删除');
            $table->timestamps();

            $table->unique('token');
        });

        //初始化数据
        $this->initData();
    }

    //初始化数据
    public function initData(){
        //数据插入
        DB::table('ad_user')->insert([
            'id' => 1,
            'username'  => 'yesadmin',
            'nickname'  => 'yesadmin',
            'truename'  => 'yesadmin',
            'email' => 'yesadmin@example.com',
            'password'  => Common::md5('yesadmin123'),
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_user');
    }
}
