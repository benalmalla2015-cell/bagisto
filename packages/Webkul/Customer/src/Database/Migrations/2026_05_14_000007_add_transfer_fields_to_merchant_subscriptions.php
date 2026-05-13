<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('merchant_subscriptions', function (Blueprint $table) {
            $table->string('transfer_number')->nullable()->after('status');
            $table->string('receipt_path')->nullable()->after('transfer_number');
        });
    }

    public function down(): void
    {
        Schema::table('merchant_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['transfer_number', 'receipt_path']);
        });
    }
};
