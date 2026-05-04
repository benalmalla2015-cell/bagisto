<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('merchant_status', ['pending', 'active', 'suspended'])->default('pending')->after('status');
            $table->enum('subscription_status', ['active', 'expired', 'none'])->default('none')->after('merchant_status');
            $table->date('subscription_ends_at')->nullable()->after('subscription_status');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['merchant_status', 'subscription_status', 'subscription_ends_at']);
        });
    }
};
