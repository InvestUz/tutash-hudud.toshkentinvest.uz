<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('shartnoma_raqami')->nullable()->after('owner_verified');
            $table->date('shartnoma_sanasi')->nullable()->after('shartnoma_raqami');
            $table->timestamp('shartnoma_tizimga_kiritilgan_vaqti')->nullable()->after('shartnoma_sanasi');

            // Index for faster searches
            $table->index('shartnoma_raqami');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['shartnoma_raqami']);
            $table->dropColumn([
                'shartnoma_raqami',
                'shartnoma_sanasi',
                'shartnoma_tizimga_kiritilgan_vaqti'
            ]);
        });
    }
};
