<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApFaq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ap_faq', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id')->comment('游戏ID');
            $table->string('title')->comment('标题');
            $table->text('description')->comment('描述');
            $table->text('answer')->comment('答案');
            $table->integer('admin_id')->comment('添加人ID');
            $table->string('image')->comment('图片路径');
            $table->string('author')->comment('作者姓名');
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
        Schema::dropIfExists('ap_faq');
    }
}
