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
        if (!Schema::hasTable('topsofthetops')) {
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
        }

        if (!Schema::hasTable('streamers_twitch')) {
            Schema::create('streamers_twitch', function (Blueprint $table) {
                $table->string('id', 16)->primary();
                $table->string('login', 64)->nullable();
                $table->string('display_name', 64)->nullable();
                $table->string('type', 64)->nullable();
                $table->string('broadcaster_type', 64)->nullable();
                $table->string('description', 1024)->nullable();
                $table->string('profile_image_url', 1024)->nullable();
                $table->string('offline_image_url', 1024)->nullable();
                $table->integer('view_count')->nullable();
                $table->string('created_at', 128)->nullable();
            });
        }

        if (!Schema::hasTable('tokens_twitch')) {
            Schema::create('tokens_twitch', function (Blueprint $table) {
                $table->string('user_id', 100);
                $table->string('token', 100);
                $table->primary(['user_id', 'token']);
            });
        }

        if (!Schema::hasTable('users_twitch')) {
            Schema::create('users_twitch', function (Blueprint $table) {
                $table->id('user_id');
                $table->string('username', 100);
                $table->string('password', 100);
                $table->primary('user_id');
            });
        }

        if (!Schema::hasTable('user_follows')) {
            Schema::create('user_follows', function (Blueprint $table) {
                $table->string('user_id', 100);
                $table->string('streamer_id', 100);
                $table->primary(['user_id', 'streamer_id']);
                $table->foreign('user_id')->references('user_id')->on('users_twitch');
                $table->foreign('streamer_id')->references('id')->on('streamers_twitch');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topsofthetops');
        Schema::dropIfExists('streamers_twitch');
        Schema::dropIfExists('tokens_twitch');
        Schema::dropIfExists('users_twitch');
    }
};
