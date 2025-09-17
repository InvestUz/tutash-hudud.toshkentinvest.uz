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

            // Cadastral and Building Information
            $table->string('building_cadastr_number')->unique();
            $table->string('owner_stir_pinfl');
            $table->string('owner_name');

            // Address Information
            $table->foreignId('district_id')->constrained()->onDelete('cascade');
            $table->foreignId('mahalla_id')->constrained()->onDelete('cascade');
            $table->foreignId('street_id')->constrained()->onDelete('cascade');
            $table->string('house_number');

            // Contact Information
            $table->string('director_name');
            $table->string('phone_number');

            // NEW: Area Measurements (Length x Width approach)
            $table->decimal('area_length', 8, 2)->nullable(); // Uzunlik (m)
            $table->decimal('area_width', 8, 2)->nullable(); // Kenglik (m)
            $table->decimal('calculated_land_area', 8, 2)->nullable(); // Auto-calculated area
            $table->string('area_calculation_method')->nullable(); // Calculation method used

            // REMOVED: Old four-side measurements (can be removed in migration or kept for backward compatibility)
            // $table->decimal('side_a_b', 8, 2)->nullable();
            // $table->decimal('side_b_c', 8, 2)->nullable();
            // $table->decimal('side_c_d', 8, 2)->nullable();
            // $table->decimal('side_d_a', 8, 2)->nullable();

            // Original measurements (keep for backward compatibility)
            $table->decimal('building_facade_length', 8, 2); // Fasad uzunligi
            $table->decimal('summer_terrace_sides', 8, 2); // Yozgi terassa tomonlari
            $table->decimal('distance_to_roadway', 8, 2); // Yo'lgacha masofa
            $table->decimal('distance_to_sidewalk', 8, 2); // Trotuargacha masofa
            $table->decimal('total_area', 8, 2); // Umumiy maydon (manual input)

            // Usage Information
            $table->string('usage_purpose');
            $table->enum('terrace_buildings_available', ['Xa', 'Yoq']);
            $table->enum('terrace_buildings_permanent', ['Xa', 'Yoq']);
            $table->enum('has_permit', ['Xa', 'Yoq']);

            // Activity types
            $table->string('activity_type');
            $table->string('tenant_activity_type')->nullable();

            // Tenant Information
            $table->boolean('has_tenant')->default(false);
            $table->string('tenant_name')->nullable();
            $table->string('tenant_stir_pinfl')->nullable();

            // Additional Information
            $table->text('additional_info')->nullable();

            // Adjacent Area Information
            $table->string('adjacent_activity_type');
            $table->string('adjacent_activity_land')->nullable();
            $table->json('adjacent_facilities');

            // Geolocation
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url')->nullable();
            $table->json('polygon_coordinates')->nullable();

            // Files
            $table->json('images');
            $table->string('act_file')->nullable();
            $table->string('design_code_file')->nullable();

            // API Integration Fields
            $table->json('owner_api_data')->nullable();
            $table->json('tenant_api_data')->nullable();
            $table->boolean('owner_verified')->default(false);
            $table->boolean('tenant_verified')->default(false);

            // Creator
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');

            $table->timestamps();

            // Indexes
            $table->index(['district_id', 'mahalla_id', 'street_id']);
            $table->index('owner_stir_pinfl');
            $table->index('tenant_stir_pinfl');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
