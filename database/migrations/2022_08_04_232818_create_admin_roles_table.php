<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminRolesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->nullable()->comment('角色名称');
            $table->string('description')->nullable()->comment('描述');
            $table->tinyInteger('status')->default(0)->comment('状态 0正常 1 禁用');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_roles');
    }
}
