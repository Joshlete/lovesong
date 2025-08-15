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
            $table->string('file_path')->nullable()->after('file_url');
            $table->bigInteger('file_size')->nullable()->after('file_path');
            $table->string('original_filename')->nullable()->after('file_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('song_requests', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'file_size', 'original_filename']);
        });
    }
};