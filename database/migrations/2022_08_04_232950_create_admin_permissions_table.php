<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('icon')->nullable();
            $table->string('path')->nullable();
            $table->string('url')->nullable();
            $table->string('title')->nullable();
            $table->tinyInteger('hidden')->nullable()->comment('是否隐藏 1:是 0否');
            $table->tinyInteger('is_menu')->nullable()->comment('是否菜单 1:是 0否');
            $table->tinyInteger('p_id')->nullable()->default(0)->comment('父级节点');
            $table->string('method')->nullable()->default('GET')->comment('请求方法');
            $table->tinyInteger('status')->comment('状态 1正常；0禁用');
            $table->softDeletes();
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
        Schema::dropIfExists('admin_permissions');
    }
}
