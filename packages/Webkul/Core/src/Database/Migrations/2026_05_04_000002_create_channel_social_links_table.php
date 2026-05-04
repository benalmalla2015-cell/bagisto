<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channel_social_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('channel_id');
            $table->enum('platform', ['whatsapp', 'facebook', 'instagram', 'tiktok', 'twitter']);
            $table->string('url');
            $table->timestamps();

            $table->foreign('channel_id')
                ->references('id')
                ->on('channels')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('channel_social_links');
    }
};
