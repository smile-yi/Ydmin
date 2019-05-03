<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApGame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ap_game', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('游戏名称');
            $table->string('faq_tpl')->comment('faq模板地址');
            $table->tinyInteger('status')->default(1)->comment('状态:-1.删除;0.禁用;1.正常');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ap_game');
    }
}
