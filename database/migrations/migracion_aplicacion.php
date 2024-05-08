<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('topsofthetops', function (Blueprint $table) {
            $table->string('game_id', 10);
            $table->string('game_name', 50);
            $table->string('user_name', 50);
            $table->integer('total_videos');
            $table->integer('total_views');
            $table->string('most_viewed_title', 300);
            $table->integer('most_viewed_views');
            $table->string('most_viewed_duration', 30);
            $table->string('most_viewed_created_at', 50);
            $table->dateTime('date');
        });

        Schema::create('users_twitch', function (Blueprint $table) {
            $table->string('id', 16)->primary();
            $table->string('login', 64)->nullable();
            $table->string('display_name', 64)->nullable();
            $table->string('type', 64)->nullable();
            $table->string('broadcaster_type', 64)->nullable();
            $table->string('desciption', 1024)->nullable();
            $table->string('profile_image_url', 1024)->nullable();
            $table->string('offline_image_url', 1024)->nullable();
            $table->integer('view_count')->nullable();
            $table->string('created_at', 128)->nullable();
        });

        Schema::create('tokens_twitch', function (Blueprint $table) {
            $table->string('user_id', 100);
            $table->string('token', 100);
            $table->primary(['user_id', 'token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topsofthetops');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tokens');
    }
};