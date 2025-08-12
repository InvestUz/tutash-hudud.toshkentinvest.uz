<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            // Bino egasi ma'lumotlari
            $table->string('owner_name');
            $table->string('owner_company')->nullable();
            $table->string('owner_stir_pinfl');
            $table->string('building_cadastr_number');
            $table->string('object_name');
            $table->string('activity_type');

            // Ijarachi ma'lumotlari
            $table->boolean('has_tenant')->default(false);
            $table->string('tenant_name')->nullable();
            $table->string('tenant_company')->nullable();
            $table->string('tenant_stir_pinfl')->nullable();

            // Manzil ma'lumotlari
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->foreignId('mahalla_id')->constrained()->onDelete('cascade');
            $table->foreignId('street_id')->constrained()->onDelete('cascade');
            $table->string('house_number');
            $table->text('additional_info')->nullable();

            // Tutash hudud ma'lumotlari
            $table->string('adjacent_activity_type');
            $table->json('adjacent_facilities'); // Array of facilities

            // Geolokatsiya
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url')->nullable();

            // Fayllar
            $table->json('images'); // Array of image paths
            $table->string('act_file')->nullable();

            // Yaratuvchi
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
