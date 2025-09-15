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

            // Building Measurements
            $table->decimal('building_facade_length', 8, 2); // Длина фасада здания
            $table->decimal('summer_terrace_sides', 8, 2); // Длины сторон летней террасы
            $table->decimal('distance_to_roadway', 8, 2); // Расстояние до проезжей части
            $table->decimal('distance_to_sidewalk', 8, 2); // Расстояние до тротуара
            $table->decimal('total_area', 8, 2); // Общая площадь

            // Usage Information
            $table->string('usage_purpose'); // Назначение использования
            $table->enum('terrace_buildings_available', ['Xa', 'Yoq']); // Наличие построек на террасе
            $table->enum('terrace_buildings_permanent', ['Xa', 'Yoq']); // Наличие построек на террасе (постоянные)
            $table->enum('has_permit', ['Xa', 'Yoq']); // Если Да - has permit

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
            $table->json('adjacent_facilities'); // Array of facilities

            // Geolocation
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('google_maps_url')->nullable();
            $table->json('polygon_coordinates')->nullable(); // For polygon drawing

            // Files
            $table->json('images'); // Array of image paths (minimum 4)
            $table->string('act_file')->nullable();
            $table->string('design_code_file')->nullable();

            // API Integration Fields
            $table->json('owner_api_data')->nullable(); // Store full API response
            $table->json('tenant_api_data')->nullable(); // Store tenant API response
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
