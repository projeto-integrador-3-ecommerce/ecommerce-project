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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('preference_id')->nullable();
            $table->text('checkout_url')->nullable();
            $table->string('external_payment_id')->nullable();
            $table->string('status_detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'preference_id',
                'checkout_url',
                'external_payment_id',
                'status_detail',
            ]);
        });
    }
};
