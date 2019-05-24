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
            $table->string('desc', 100)->nullable()->comment('描述');
            $table->text('rule_ids')->nullable()->comment('权限ID集 json');
            $table->tinyInteger('status')->default(1)->unsigned()
                ->comment('状态:1.正常;2.禁用;9.删除');
            $table->timestamps();
        });

        $this->initData();
    }

    //初始化数据
    public function initData(){
        DB::table('ad_group')->insert([
            'id' => 1,
            'name' => '默认组',
            'desc' => '默认组',
            'rule_ids' => json_encode([1, 2, 3]),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
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
