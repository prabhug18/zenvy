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
        Schema::create('homepage_videos', function (Blueprint $theme) {
            $theme->id();
            $theme->string('title')->nullable();
            $theme->string('video_url');
            $theme->string('video_type')->default('upload'); // upload, youtube, vimeo
            $theme->string('thumbnail')->nullable();
            $theme->integer('order')->default(0);
            $theme->boolean('status')->default(1);
            $theme->softDeletes();
            $theme->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_videos');
    }
};
