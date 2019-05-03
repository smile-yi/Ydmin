<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20)->comment('名称');
            $table->string('depict', 100)->nullable()->comment('描述');
            $table->text('rule_ids')->nullable()->comment('权限ID集 json');
            $table->tinyInteger('status')->nullable()->default(1)
                ->comment('状态:1.正常;0.禁用;-1.删除');
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
        Schema::dropIfExists('ad_group');
    }
}
