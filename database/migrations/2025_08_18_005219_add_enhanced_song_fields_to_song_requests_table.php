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
            // Enhanced fields for better song creation with Suno
            $table->text('song_description')->nullable()->after('lyrics_idea');
            $table->string('genre_details')->nullable()->after('song_description');
            $table->string('tempo')->nullable()->after('genre_details');
            $table->string('vocals')->nullable()->after('tempo');
            $table->text('instruments')->nullable()->after('vocals');
            $table->text('song_structure')->nullable()->after('instruments');
            $table->text('inspiration')->nullable()->after('song_structure');
            $table->string('target_length')->nullable()->after('inspiration');
            $table->text('special_instructions')->nullable()->after('target_length');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('song_requests', function (Blueprint $table) {
            $table->dropColumn([
                'song_description',
                'genre_details',
                'tempo',
                'vocals',
                'instruments',
                'song_structure',
                'inspiration',
                'target_length',
                'special_instructions',
            ]);
        });
    }
};
