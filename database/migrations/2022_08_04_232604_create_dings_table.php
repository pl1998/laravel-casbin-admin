<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDingsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dings', function (Blueprint $table): void {
            $table->id();
            $table->string('nick')->nullable();
            $table->string('unionid')->nullable();
            $table->string('openid')->nullable();
            $table->string('ding_id')->nullable();
            $table->integer('user_id')->default(0)->nullable();
            $table->string('ding_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dings');
    }
}
