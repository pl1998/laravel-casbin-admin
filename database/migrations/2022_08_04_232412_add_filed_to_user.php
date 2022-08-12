<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFiledToUser extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('avatar')->nullable();
            $table->string('ding_id', 100)->nullable();
            $table->string('oauth_id', 100)->nullable();
            $table->tinyInteger('oauth_type')->default(0)->comment('1.微博 2.钉钉');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
        });
    }
}
