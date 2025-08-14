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
        Schema::table('song_requests', function (Blueprint $table) {
            $table->string('payment_intent_id')->nullable()->after('payment_reference');
            $table->enum('payment_status', ['pending', 'processing', 'succeeded', 'failed', 'refunded'])
                  ->default('pending')->after('payment_intent_id');
            $table->timestamp('payment_completed_at')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('song_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_intent_id', 'payment_status', 'payment_completed_at']);
        });
    }
};
