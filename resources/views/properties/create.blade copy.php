@extends('layouts.app')

@section('title', 'Yangi mulk qo\'shish')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center">
            <a href="{{ route('properties.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-900">Yangi mulk qo'shish</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data" class="px-6 py-4">
        @csrf

        <!-- Bino egasi ma'lumotlari -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Bino egasi ma'lumotlari
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        F.I.SH <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="owner_name" value="{{ old('owner_name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('owner_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kompaniya nomi
                    </label>
                    <input type="text" name="owner_company" value="{{ old('owner_company') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('owner_company')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        STIR/PINFL <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="owner_stir_pinfl" value="{{ old('owner_stir_pinfl') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('owner_stir_pinfl')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bino kadastr raqami <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="building_cadastr_number" value="{{ old('building_cadastr_number') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('building_cadastr_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Obyekt nomi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="object_name" value="{{ old('object_name') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('object_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Faoliyat turi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="activity_type" value="{{ old('activity_type') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Masalan: Savdo, Xizmat ko'rsatish, Ishlab chiqarish">
                    @error('activity_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Ijarachi ma'lumotlari -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Ijarachi ma'lumotlari
            </h3>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="has_tenant" value="1" {{ old('has_tenant') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Ijarachi mavjud</span>
                </label>
            </div>

            <div id="tenantFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('has_tenant') ? '' : 'hidden' }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ijarachi F.I.SH
                    </label>
                    <input type="text" name="tenant_name" value="{{ old('tenant_name') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tenant_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kompaniya nomi
                    </label>
                    <input type="text" name="tenant_company" value="{{ old('tenant_company') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tenant_company')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        STIR/PINFL
                    </label>
                    <input type="text" name="tenant_stir_pinfl" value="{{ old('tenant_stir_pinfl') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tenant_stir_pinfl')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Manzil ma'lumotlari -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Manzil ma'lumotlari
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tuman <span class="text-red-500">*</span>
                    </label>
                    <select name="district_id" id="district_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tumanni tanlang</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('district_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mahalla <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <select name="mahalla_id" id="mahalla_id" required
                                class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Mahallani tanlang</option>
                        </select>
                        <button type="button" data-add-mahalla="true"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-r-lg">
                            +
                        </button>
                    </div>
                    @error('mahalla_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ko'cha <span class="text-red-500">*</span>
                    </label>
                    <div class="flex">
                        <select name="street_id" id="street_id" required
                                class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Ko'chani tanlang</option>
                        </select>
                        <button type="button" data-add-street="true"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-r-lg">
                            +
                        </button>
                    </div>
                    @error('street_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Uy raqami <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="house_number" value="{{ old('house_number') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Masalan: 15A">
                    @error('house_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mo'ljal uchun qo'shimcha ma'lumot
                    </label>
                    <textarea name="additional_info" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Masalan: Oloy bozori yonida, yashil uy">{{ old('additional_info') }}</textarea>
                    @error('additional_info')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Tutash hudud ma'lumotlari -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H7m2 0v-6m10 6v-6M7 7h10M7 11h10"></path>
                </svg>
                Tutash hudud ma'lumotlari
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tutash hududdagi faoliyat turi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="adjacent_activity_type" value="{{ old('adjacent_activity_type') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Masalan: Turarjoy, Savdo, Ishlab chiqarish">
                    @error('adjacent_activity_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tutash hududdagi qurilmalar <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-lg p-3">
                        @php
                            $facilities = [
                                'kapital_qurilma' => 'Kapital qurilma',
                                'mavjud_emas' => 'Mavjud emas',
                                'yengil_qurilma' => 'Yengil qurilma',
                                'bostirma' => 'Bostirma',
                                'beton_maydoncha' => 'Beton maydoncha',
                                'elektr_quvvatlash' => 'Elektr quvvatlash',
                                'avtoturargoh' => 'Avtoturargoh',
                                'boshqalar' => 'Boshqalar'
                            ];
                        @endphp
                        @foreach($facilities as $value => $label)
                            <label class="flex items-center">
                                <input type="checkbox" name="adjacent_facilities[]" value="{{ $value }}"
                                       {{ in_array($value, old('adjacent_facilities', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('adjacent_facilities')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Geolokatsiya -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                Geolokatsiya
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kenglik (Latitude)</label>
                    <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="41.2995">
                    @error('latitude')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Uzunlik (Longitude)</label>
                    <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="69.2401">
                    @error('longitude')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-end">
                    <button type="button" data-get-location="true"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full">
                        Joylashuvni aniqlash
                    </button>
                </div>
            </div>

            <!-- Map Loading Status -->
            <div id="mapStatus" class="mb-4 p-3 rounded-lg border bg-blue-50 text-blue-800 border-blue-300">
                <div class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" fill-rule="evenodd" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" clip-rule="evenodd"></path>
                    </svg>
                    Xarita yuklanmoqda...
                </div>
            </div>

            <div id="map" style="height: 400px;" class="border rounded-lg bg-gray-100"></div>
            <p class="text-sm text-gray-500 mt-2">Xaritada bosing yoki "Joylashuvni aniqlash" tugmasini bosing</p>
        </div>

        <!-- Dynamic Images Upload Section -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Rasmlar (kamida 4 ta) <span class="text-red-500">*</span>
            </h3>

            <!-- Image upload status -->
            <div id="imageUploadStatus" class="mb-4 p-3 rounded-lg border bg-red-50 text-red-800 border-red-300">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span id="imageStatusText">0 ta rasm yuklandi. Kamida 4 ta kerak!</span>
                </div>
            </div>

            <!-- Images Container -->
            <div id="imagesContainer" class="space-y-4">
                <!-- Initial 4 required image inputs -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="requiredImages">
                    <div class="image-upload-item" data-index="1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            1-rasm <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="images[]" accept="image/*" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="preview-container mt-2" id="preview_1"></div>
                        @error('images.0')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="image-upload-item" data-index="2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            2-rasm <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="images[]" accept="image/*" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="preview-container mt-2" id="preview_2"></div>
                        @error('images.1')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="image-upload-item" data-index="3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            3-rasm <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="images[]" accept="image/*" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="preview-container mt-2" id="preview_3"></div>
                        @error('images.2')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="image-upload-item" data-index="4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            4-rasm <span class="text-red-500">*</span>
                        </label>
                        <input type="file" name="images[]" accept="image/*" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="preview-container mt-2" id="preview_4"></div>
                        @error('images.3')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional images will be added here -->
                <div id="additionalImages" class="grid grid-cols-1 md:grid-cols-2 gap-4"></div>
            </div>

            <!-- Add More Images Button -->
            <div class="mt-4">
                <button type="button" data-add-image="true"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Yana rasm qo'shish
                </button>
            </div>

            <!-- Act File -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Akt fayli (ixtiyoriy)
                </label>
                <input type="file" name="act_file" accept=".pdf,.doc,.docx"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX formatida, maksimal 10MB</p>
                @error('act_file')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-4">
                <p class="text-sm text-gray-500">
                    <span class="text-red-500">*</span> JPEG, PNG, JPG formatida, har biri maksimal 2MB
                </p>
            </div>
        </div>

        <!-- Submit buttons -->
        <div class="flex justify-end space-x-4 border-t pt-6">
            <a href="{{ route('properties.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Bekor qilish
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Saqlash
            </button>
        </div>
    </form>
</div>

<!-- Add Mahalla Modal -->
<div id="addMahallaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium">Yangi mahalla qo'shish</h3>
            </div>
            <div class="px-6 py-4">
                <input type="hidden" id="newMahallaDistrictId">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mahalla nomi</label>
                <input type="text" id="newMahallaName"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2"
                       placeholder="Mahalla nomini kiriting">
            </div>
            <div class="px-6 py-4 border-t flex justify-end space-x-2">
                <button data-close-modal="addMahallaModal"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Bekor qilish
                </button>
                <button data-submit-mahalla="true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Qo'shish
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Street Modal -->
<div id="addStreetModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium">Yangi ko'cha qo'shish</h3>
            </div>
            <div class="px-6 py-4">
                <input type="hidden" id="newStreetMahallaId">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ko'cha nomi</label>
                <input type="text" id="newStreetName"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2"
                       placeholder="Ko'cha nomini kiriting">
            </div>
            <div class="px-6 py-4 border-t flex justify-end space-x-2">
                <button data-close-modal="addStreetModal"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                    Bekor qilish
                </button>
                <button data-submit-street="true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Qo'shish
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// =============== GOOGLE MAPS IFRAME WITH MARKER IMPLEMENTATION ===============
let imageCounter = 4;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up map and event listeners...');

    // Initialize map first
    setTimeout(() => {
        initializeMapIframe();
        setupEventListeners();
        updateImageStatus();

        // Initialize tenant fields visibility
        const tenantCheckbox = document.querySelector('input[name="has_tenant"]');
        if (tenantCheckbox) {
            toggleTenantFields(tenantCheckbox);
        }
    }, 100);
});

// =============== MAP IFRAME FUNCTIONS ===============
function initializeMapIframe() {
    console.log('Initializing Google Maps iframe...');

    // Show loading status
    updateMapStatus('Xarita yuklanmoqda...', 'loading');

    // Default coordinates for Tashkent
    let currentLat = 41.2995;
    let currentLng = 69.2401;

    // Check if there are existing coordinates
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    if (latInput && lngInput && latInput.value && lngInput.value) {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
            currentLat = lat;
            currentLng = lng;
        }
    }

    // Create the map iframe
    createMapIframe(currentLat, currentLng);

    console.log('Map iframe created successfully');
}

function updateMapStatus(message, type) {
    const statusDiv = document.getElementById('mapStatus');
    if (!statusDiv) return;

    let className, icon;

    switch(type) {
        case 'loading':
            className = 'mb-4 p-3 rounded-lg border bg-blue-50 text-blue-800 border-blue-300';
            icon = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" fill-rule="evenodd" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" clip-rule="evenodd"></path>
            </svg>`;
            break;
        case 'success':
            className = 'mb-4 p-3 rounded-lg border bg-green-50 text-green-800 border-green-300';
            icon = `<svg class="-ml-1 mr-3 h-5 w-5 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>`;
            break;
        case 'error':
            className = 'mb-4 p-3 rounded-lg border bg-red-50 text-red-800 border-red-300';
            icon = `<svg class="-ml-1 mr-3 h-5 w-5 text-red-800" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>`;
            break;
    }

    statusDiv.className = className;
    statusDiv.innerHTML = `
        <div class="flex items-center">
            ${icon}
            ${message}
        </div>
    `;

    // Hide status after success
    if (type === 'success') {
        setTimeout(() => {
            statusDiv.style.display = 'none';
        }, 3000);
    }
}

function createMapIframe(lat, lng) {
    const mapContainer = document.getElementById('map');
    if (!mapContainer) {
        console.error('Map container not found!');
        updateMapStatus('Xarita konteyner topilmadi!', 'error');
        return;
    }

    // Clear existing content
    mapContainer.innerHTML = '';

    // Create iframe for Google Maps with marker
    const iframe = document.createElement('iframe');
    iframe.style.cssText = `
        width: 100%;
        height: 400px;
        border: 2px solid #2196f3;
        border-radius: 8px;
        background: #f3f4f6;
    `;
    iframe.loading = 'lazy';
    iframe.allowfullscreen = true;
    iframe.referrerpolicy = 'no-referrer-when-downgrade';

    // Create Google Maps embed URL with marker
    // This URL format automatically places a red marker at the specified coordinates
    const embedUrl = `https://www.google.com/maps/embed/v1/place?key=&q=${lat},${lng}&center=${lat},${lng}&zoom=15`;

    // Alternative URL format that works without API key and shows marker
    const alternativeUrl = `https://maps.google.com/maps?q=${lat},${lng}&t=&z=15&ie=UTF8&iwloc=&output=embed`;

    iframe.src = alternativeUrl;

    // Handle iframe load events
    iframe.onload = function() {
        console.log('Map iframe loaded successfully');
        updateMapStatus('Xarita va marker muvaffaqiyatli yuklandi!', 'success');
    };

    iframe.onerror = function() {
        console.error('Failed to load map iframe');
        updateMapStatus('Xarita yuklanmadi. Internet aloqasini tekshiring.', 'error');
    };

    // Add iframe to container
    mapContainer.appendChild(iframe);

    // Add coordinate info below map
    const coordInfo = document.createElement('div');
    coordInfo.style.cssText = `
        margin-top: 10px;
        padding: 10px 12px;
        background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        color: #374151;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    `;
    coordInfo.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
            <svg style="width: 16px; height: 16px; color: #ef4444;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
            </svg>
            <span><strong>Marker joylashuvi:</strong></span>
        </div>
        <div style="margin-top: 4px;">
            Lat: <span id="current-lat-display" style="font-family: monospace; background: #f3f4f6; padding: 2px 4px; border-radius: 3px;">${lat.toFixed(6)}</span> |
            Lng: <span id="current-lng-display" style="font-family: monospace; background: #f3f4f6; padding: 2px 4px; border-radius: 3px;">${lng.toFixed(6)}</span>
        </div>
        <div style="margin-top: 6px; font-size: 12px; color: #6b7280;">
            Koordinatalarni o'zgartirish uchun yuqoridagi maydonlarni to'ldiring
        </div>
    `;

    mapContainer.appendChild(coordInfo);

    // Add action buttons below coordinate info
    const actionButtons = document.createElement('div');
    actionButtons.style.cssText = `
        margin-top: 8px;
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    `;

    actionButtons.innerHTML = `
        <button type="button" data-open-in-google="true" style="
            background: #4285f4;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
        " title="Google Maps'da ochish">
            <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 20 20">
                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path>
                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path>
            </svg>
            Google Maps
        </button>
        <button type="button" data-copy-coordinates="true" style="
            background: #059669;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
        " title="Koordinatalarni nusxalash">
            <svg style="width: 14px; height: 14px;" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"></path>
                <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"></path>
            </svg>
            Nusxalash
        </button>
    `;

    mapContainer.appendChild(actionButtons);

    console.log('Map iframe created with marker at coordinates:', lat, lng);
}

function updateMapLocation(lat, lng) {
    console.log('Updating map location:', lat, lng);

    // Show updating status
    updateMapStatus('Xarita va marker yangilanmoqda...', 'loading');

    // Update coordinate inputs
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    if (latInput) latInput.value = lat.toFixed(6);
    if (lngInput) lngInput.value = lng.toFixed(6);

    // Update coordinate display
    const latDisplay = document.getElementById('current-lat-display');
    const lngDisplay = document.getElementById('current-lng-display');

    if (latDisplay) latDisplay.textContent = lat.toFixed(6);
    if (lngDisplay) lngDisplay.textContent = lng.toFixed(6);

    // Recreate iframe with new coordinates and marker
    createMapIframe(lat, lng);
}

// =============== GEOLOCATION FUNCTION ===============
function getCurrentLocation() {
    console.log('Getting current location...');

    if (!navigator.geolocation) {
        showMessage('Geolokatsiya ushbu brauzerda qo\'llab-quvvatlanmaydi', 'error');
        return;
    }

    showMessage('Joylashuvingiz aniqlanmoqda...', 'loading');

    const options = {
        enableHighAccuracy: true,
        timeout: 15000,
        maximumAge: 60000
    };

    navigator.geolocation.getCurrentPosition(
        function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            console.log('Location found:', lat, lng);

            // Update map and inputs
            updateMapLocation(lat, lng);

            showMessage(`Joylashuvingiz aniqlandi va xaritada ko'rsatildi!\nLat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`, 'success');
        },
        function(error) {
            console.error('Geolocation error:', error);

            let message = 'Geolokatsiya xatosi: ';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    message += "Geolokatsiya ruxsati berilmadi. Brauzer sozlamalarida ruxsat bering.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    message += "Joylashuv ma'lumotlari mavjud emas.";
                    break;
                case error.TIMEOUT:
                    message += "Geolokatsiya so'rovi vaqti tugadi.";
                    break;
                default:
                    message += "Noma'lum xato yuz berdi.";
                    break;
            }
            showMessage(message, 'error');
        },
        options
    );
}

// =============== HELPER FUNCTIONS FOR MAP ACTIONS ===============
function openInGoogleMaps(lat, lng) {
    const url = `https://www.google.com/maps?q=${lat},${lng}`;
    window.open(url, '_blank');
}

function copyCoordinates(lat, lng) {
    const coordinates = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

    if (navigator.clipboard) {
        navigator.clipboard.writeText(coordinates).then(() => {
            showMessage(`Koordinatalar nusxalandi: ${coordinates}`, 'success');
        }).catch(() => {
            showMessage('Koordinatalarni nusxalashda xato', 'error');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = coordinates;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showMessage(`Koordinatalar nusxalandi: ${coordinates}`, 'success');
        } catch (err) {
            showMessage('Koordinatalarni nusxalashda xato', 'error');
        }
        document.body.removeChild(textArea);
    }
}

// =============== MESSAGE FUNCTION ===============
function showMessage(message, type) {
    console.log('Showing message:', message, type);

    // Remove existing messages
    const existingMessages = document.querySelectorAll('.location-message');
    existingMessages.forEach(msg => msg.remove());

    const messageDiv = document.createElement('div');
    messageDiv.className = 'location-message';

    let bgColor, textColor, borderColor;
    switch(type) {
        case 'success':
            bgColor = '#d1fae5';
            textColor = '#065f46';
            borderColor = '#6ee7b7';
            break;
        case 'error':
            bgColor = '#fee2e2';
            textColor = '#991b1b';
            borderColor = '#fca5a5';
            break;
        case 'loading':
            bgColor = '#dbeafe';
            textColor = '#1e40af';
            borderColor = '#93c5fd';
            break;
        default:
            bgColor = '#f3f4f6';
            textColor = '#374151';
            borderColor = '#d1d5db';
    }

    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${bgColor};
        color: ${textColor};
        border: 1px solid ${borderColor};
        padding: 12px 16px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        max-width: 320px;
        font-size: 14px;
        line-height: 1.4;
        transition: all 0.3s ease;
        white-space: pre-line;
    `;

    messageDiv.textContent = message;
    document.body.appendChild(messageDiv);

    // Auto remove message
    if (type !== 'loading') {
        setTimeout(() => {
            if (document.body.contains(messageDiv)) {
                messageDiv.style.opacity = '0';
                messageDiv.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (document.body.contains(messageDiv)) {
                        document.body.removeChild(messageDiv);
                    }
                }, 300);
            }
        }, type === 'success' ? 4000 : 5000);
    }
}

// =============== EVENT LISTENERS ===============
function setupEventListeners() {
    console.log('Setting up event listeners...');

    // Handle all clicks with event delegation
    document.addEventListener('click', function(e) {
        // Get location button
        if (e.target.hasAttribute('data-get-location')) {
            e.preventDefault();
            getCurrentLocation();
        }

        // Open in Google Maps button
        if (e.target.hasAttribute('data-open-in-google') || e.target.closest('[data-open-in-google]')) {
            e.preventDefault();
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            if (latInput && lngInput && latInput.value && lngInput.value) {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    openInGoogleMaps(lat, lng);
                }
            }
        }

        // Copy coordinates button
        if (e.target.hasAttribute('data-copy-coordinates') || e.target.closest('[data-copy-coordinates]')) {
            e.preventDefault();
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            if (latInput && lngInput && latInput.value && lngInput.value) {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);
                if (!isNaN(lat) && !isNaN(lng)) {
                    copyCoordinates(lat, lng);
                }
            }
        }

        // Add image button
        if (e.target.hasAttribute('data-add-image')) {
            e.preventDefault();
            addImageField();
        }

        // Modal buttons
        if (e.target.hasAttribute('data-add-mahalla')) {
            e.preventDefault();
            const districtId = document.getElementById('district_id').value;
            showAddMahallaModal(districtId);
        }

        if (e.target.hasAttribute('data-add-street')) {
            e.preventDefault();
            const mahallaId = document.getElementById('mahalla_id').value;
            showAddStreetModal(mahallaId);
        }

        if (e.target.hasAttribute('data-close-modal')) {
            e.preventDefault();
            const modalId = e.target.getAttribute('data-close-modal');
            hideModal(modalId);
        }

        if (e.target.hasAttribute('data-submit-mahalla')) {
            e.preventDefault();
            addNewMahalla();
        }

        if (e.target.hasAttribute('data-submit-street')) {
            e.preventDefault();
            addNewStreet();
        }

        // Image remove buttons
        if (e.target.hasAttribute('data-remove-image')) {
            e.preventDefault();
            const index = e.target.getAttribute('data-remove-image');
            removeImageHandler(index);
        }

        if (e.target.hasAttribute('data-remove-field')) {
            e.preventDefault();
            const index = parseInt(e.target.getAttribute('data-remove-field'));
            removeImageFieldHandler(index);
        }
    });

    // Handle form changes
    document.addEventListener('change', function(e) {
        // Tenant checkbox
        if (e.target.name === 'has_tenant') {
            toggleTenantFields(e.target);
        }

        // District selection
        if (e.target.name === 'district_id') {
            loadMahallas(e.target.value, 'mahalla_id');
        }

        // Mahalla selection
        if (e.target.name === 'mahalla_id') {
            loadStreets(e.target.value, 'street_id');
        }

        // Image inputs
        if (e.target.name === 'images[]') {
            const container = e.target.closest('[data-index]');
            if (container) {
                const index = container.getAttribute('data-index');
                previewImageHandler(e.target, index);
            }
        }
    });

    // Handle coordinate input changes
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    if (latInput && lngInput) {
        function updateMapFromInputs() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);

            if (!isNaN(lat) && !isNaN(lng)) {
                // Validate coordinates are reasonable
                if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                    console.log('Updating map from input changes:', lat, lng);
                    updateMapLocation(lat, lng);
                }
            }
        }

        latInput.addEventListener('blur', updateMapFromInputs);
        lngInput.addEventListener('blur', updateMapFromInputs);

        // Also update on Enter key
        latInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                updateMapFromInputs();
            }
        });

        lngInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                updateMapFromInputs();
            }
        });
    }

    // Handle form submission
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName === 'FORM' && form.querySelector('input[name="owner_name"]')) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
        }
    });
}

// =============== ALL OTHER FUNCTIONS (IMAGE, VALIDATION, ETC.) ===============
// [Include all the image handling, validation, and other functions from the previous script]

function previewImageHandler(input, index) {
    const preview = document.getElementById(`preview_${index}`);
    if (!preview) return;

    preview.innerHTML = '';

    if (input.files && input.files[0]) {
        const file = input.files[0];

        if (file.size > 2048 * 1024) {
            preview.innerHTML = `<div style="color: #dc2626; font-size: 14px; padding: 8px; background: #fef2f2; border-radius: 4px; border: 1px solid #fecaca;">❌ Fayl juda katta! (${(file.size / 1024 / 1024).toFixed(1)}MB)<br>Maksimal: 2MB</div>`;
            input.value = '';
            input.style.borderColor = '#ef4444';
            updateImageStatus();
            return;
        }

        if (!file.type.match('image.*')) {
            preview.innerHTML = `<div style="color: #dc2626; font-size: 14px; padding: 8px; background: #fef2f2; border-radius: 4px; border: 1px solid #fecaca;">❌ Faqat rasm fayllarini yuklang!</div>`;
            input.value = '';
            input.style.borderColor = '#ef4444';
            updateImageStatus();
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div style="position: relative;">
                    <img src="${e.target.result}" style="width: 100%; height: 128px; object-fit: cover; border-radius: 4px; border: 1px solid #d1d5db;" alt="Preview">
                    <button type="button" data-remove-image="${index}" style="position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 14px; border: none; cursor: pointer;" title="O'chirish">×</button>
                    <div style="font-size: 12px; text-align: center; margin-top: 4px; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="${file.name}">${file.name.length > 15 ? file.name.substring(0, 15) + '...' : file.name}</div>
                    <div style="font-size: 12px; text-align: center; color: #9ca3af;">${(file.size / 1024).toFixed(0)}KB</div>
                    <div style="font-size: 12px; text-align: center; color: #059669; font-weight: 500;">✓ Yuklandi</div>
                </div>
            `;
        };
        reader.readAsDataURL(file);

        input.style.borderColor = '#10b981';
    }

    updateImageStatus();
}

function removeImageHandler(index) {
    const input = document.querySelector(`[data-index="${index}"] input[type="file"]`);
    const preview = document.getElementById(`preview_${index}`);

    if (input) {
        input.value = '';
        input.style.borderColor = '';
        if (input.required) {
            input.style.borderColor = '#ef4444';
        }
    }

    if (preview) {
        preview.innerHTML = '';
    }

    updateImageStatus();
}

function addImageField() {
    imageCounter++;
    const additionalImages = document.getElementById('additionalImages');

    const imageItem = document.createElement('div');
    imageItem.className = 'image-upload-item';
    imageItem.setAttribute('data-index', imageCounter);

    imageItem.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <label style="display: block; font-size: 14px; font-weight: 500; color: #374151;">
                ${imageCounter}-rasm (ixtiyoriy)
            </label>
            <button type="button" data-remove-field="${imageCounter}"
                    style="color: #ef4444; font-size: 14px; font-weight: 500; background: none; border: none; cursor: pointer;">
                O'chirish
            </button>
        </div>
        <input type="file" name="images[]" accept="image/*"
               style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 12px;">
        <div style="margin-top: 8px;" id="preview_${imageCounter}"></div>
    `;

    additionalImages.appendChild(imageItem);
}

function removeImageFieldHandler(index) {
    const imageItem = document.querySelector(`[data-index="${index}"]`);
    if (imageItem && index > 4) {
        imageItem.remove();
        updateImageStatus();
    }
}

function updateImageStatus() {
    const allInputs = document.querySelectorAll('input[name="images[]"]');
    const requiredInputs = document.querySelectorAll('#requiredImages input[name="images[]"]');

    let totalUploaded = 0;
    let requiredUploaded = 0;

    if (requiredInputs) {
        requiredInputs.forEach(input => {
            if (input.files && input.files.length > 0) {
                requiredUploaded++;
            }
        });
    }

    if (allInputs) {
        allInputs.forEach(input => {
            if (input.files && input.files.length > 0) {
                totalUploaded++;
            }
        });
    }

    const statusDiv = document.getElementById('imageUploadStatus');
    const statusText = document.getElementById('imageStatusText');

    if (statusText && statusDiv) {
        if (requiredUploaded >= 4) {
            statusDiv.className = 'mb-4 p-3 rounded-lg border bg-green-50 text-green-800 border-green-300';
            statusText.textContent = `${totalUploaded} ta rasm yuklandi. Majburiy rasmlar to'liq yuklandi! ✓`;
        } else {
            statusDiv.className = 'mb-4 p-3 rounded-lg border bg-red-50 text-red-800 border-red-300';
            statusText.textContent = `${totalUploaded} ta rasm yuklandi. ${4 - requiredUploaded} ta majburiy rasm kamida kerak!`;
        }
    }
}

function validateForm() {
    console.log('Validating form...');
    let isValid = true;
    const errors = [];

    // Validate required fields
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#ef4444';
            isValid = false;
            const label = field.closest('div').querySelector('label');
            if (label) {
                errors.push(`${label.textContent.replace('*', '').trim()} maydonini to'ldiring`);
            }
        } else {
            field.style.borderColor = '';
        }
    });

    // Validate required images
    const requiredImages = document.querySelectorAll('#requiredImages input[name="images[]"]');
    let missingImages = [];

    if (requiredImages) {
        requiredImages.forEach((input, index) => {
            if (!input.files || input.files.length === 0) {
                input.style.borderColor = '#ef4444';
                missingImages.push(`${index + 1}-rasm`);
                isValid = false;
            }
        });
    }

    if (missingImages.length > 0) {
        errors.push(`Quyidagi majburiy rasmlarni yuklang: ${missingImages.join(', ')}`);
    }

    // Validate adjacent facilities
    const adjacentFacilities = document.querySelectorAll('input[name="adjacent_facilities[]"]:checked');
    if (adjacentFacilities.length === 0) {
        errors.push('Tutash hududdagi qurilmalardan kamida bittasini tanlang');
        isValid = false;
    }

    // Validate coordinates if provided
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    if (latInput && lngInput && (latInput.value || lngInput.value)) {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);

        if (isNaN(lat) || isNaN(lng)) {
            errors.push('Koordinatalar noto\'g\'ri formatda kiritilgan');
            isValid = false;
        } else if (lat < 37 || lat > 46 || lng < 56 || lng > 74) {
            errors.push('Koordinatalar O\'zbekiston hududida bo\'lishi kerak');
            isValid = false;
        }
    }

    // Show errors if any
    if (!isValid) {
        const errorMessage = 'Quyidagi xatolarni tuzating:\n' + errors.map((error, index) => `${index + 1}. ${error}`).join('\n');
        alert(errorMessage);

        // Scroll to first error
        const firstError = document.querySelector('[style*="border-color: rgb(239, 68, 68)"]');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    return isValid;
}

function toggleTenantFields(checkbox) {
    const tenantFields = document.getElementById('tenantFields');
    if (tenantFields) {
        if (checkbox.checked) {
            tenantFields.classList.remove('hidden');
        } else {
            tenantFields.classList.add('hidden');
        }
    }
}

function showAddMahallaModal(districtId) {
    if (!districtId) {
        alert('Avval tumanni tanlang!');
        return;
    }
    const modal = document.getElementById('addMahallaModal');
    const districtInput = document.getElementById('newMahallaDistrictId');
    if (modal && districtInput) {
        districtInput.value = districtId;
        modal.classList.remove('hidden');
    }
}

function showAddStreetModal(mahallaId) {
    if (!mahallaId) {
        alert('Avval mahallani tanlang!');
        return;
    }
    const modal = document.getElementById('addStreetModal');
    const mahallaInput = document.getElementById('newStreetMahallaId');
    if (modal && mahallaInput) {
        mahallaInput.value = mahallaId;
        modal.classList.remove('hidden');
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
}

function addNewMahalla() {
    const districtId = document.getElementById('newMahallaDistrictId').value;
    const mahallaName = document.getElementById('newMahallaName').value;

    if (!mahallaName.trim()) {
        alert('Mahalla nomini kiriting!');
        return;
    }

    console.log('Adding new mahalla:', mahallaName, 'to district:', districtId);
    // Add your backend implementation here

    hideModal('addMahallaModal');
    document.getElementById('newMahallaName').value = '';
}

function addNewStreet() {
    const mahallaId = document.getElementById('newStreetMahallaId').value;
    const streetName = document.getElementById('newStreetName').value;

    if (!streetName.trim()) {
        alert('Ko\'cha nomini kiriting!');
        return;
    }

    console.log('Adding new street:', streetName, 'to mahalla:', mahallaId);
    // Add your backend implementation here

    hideModal('addStreetModal');
    document.getElementById('newStreetName').value = '';
}

function loadMahallas(districtId, targetSelectId, selectedValue = null) {
    console.log('Loading mahallas for district:', districtId);
    // Add your backend implementation here
}

function loadStreets(mahallaId, targetSelectId, selectedValue = null) {
    console.log('Loading streets for mahalla:', mahallaId);
    // Add your backend implementation here
}

console.log('Google Maps iframe with marker script loaded successfully');
</script>
@endsection
