<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merchant_social_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('whatsapp', 500)->nullable();
            $table->string('facebook', 500)->nullable();
            $table->string('instagram', 500)->nullable();
            $table->string('tiktok', 500)->nullable();
            $table->string('twitter', 500)->nullable();
            $table->timestamps();

            $table->unique('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merchant_social_links');
    }
};
