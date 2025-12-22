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
        Schema::table('properties', function (Blueprint $table) {
            $table->string('monitoring_file')->nullable()->after('needs_monitoring')->comment('Муаммо файли');
            $table->text('monitoring_comment')->nullable()->after('monitoring_file')->comment('Муаммо сабаби');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['monitoring_file', 'monitoring_comment']);
        });
    }
};
