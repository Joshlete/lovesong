<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Keep existing status enum for now - we'll use payment_status to track payments
        // This avoids complex enum modifications in PostgreSQL
        // Status will represent: pending (awaiting payment), in_progress (work started), completed, cancelled
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to rollback since we're not changing the enum
    }
};
