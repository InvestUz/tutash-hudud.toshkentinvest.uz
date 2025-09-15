@extends('layouts.app')

@section('title', 'Yangi mulk qo\'shish')

@section('content')
    <!-- Make sure you have CSRF token in your layout head section -->
    <!-- Add this if not already present: <meta name="csrf-token" content="{{ csrf_token() }}"> -->

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

        <!-- File Upload Errors Display -->
        @if ($errors->any())
            <div class="px-6 py-4 bg-red-50 border-l-4 border-red-400">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Quyidagi xatolarni to'g'irlang:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data"
            onsubmit="return validateForm()" class="px-6 py-4" id="propertyForm">
            @csrf

            <!-- Bino egasi ma'lumotlari -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Bino egasi ma'lumotlari
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Korxona nomi / F.I.SH <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="owner_name" value="{{ old('owner_name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('owner_name') border-red-500 @enderror">
                        @error('owner_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            STIR/PINFL <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="owner_stir_pinfl" value="{{ old('owner_stir_pinfl') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('owner_stir_pinfl') border-red-500 @enderror">
                        @error('owner_stir_pinfl')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Bino kadastr raqami <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="building_cadastr_number" value="{{ old('building_cadastr_number') }}"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('building_cadastr_number') border-red-500 @enderror">
                        @error('building_cadastr_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Obyekt nomi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="object_name" value="{{ old('object_name') }}"
                            placeholder="Masalan: Alohida turgan bino, Ko'p qavatli binoning noturar qismi" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('object_name') border-red-500 @enderror">
                        @error('object_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Faoliyat turi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="activity_type" value="{{ old('activity_type') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('activity_type') border-red-500 @enderror"
                            placeholder="Masalan: Umumiy ovqatlanish, Savdo, Xizmat ko'rsatish">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Ijarachi ma'lumotlari
                </h3>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="has_tenant" value="1" {{ old('has_tenant') ? 'checked' : '' }}
                            onchange="toggleTenantFields(this)"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Ijarachi mavjud</span>
                    </label>
                </div>

                <div id="tenantFields"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('has_tenant') ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Korxona nomi / Ijarachi F.I.SH
                        </label>
                        <input type="text" name="tenant_name" value="{{ old('tenant_name') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_name') border-red-500 @enderror">
                        @error('tenant_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            STIR/PINFL
                        </label>
                        <input type="text" name="tenant_stir_pinfl" value="{{ old('tenant_stir_pinfl') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_stir_pinfl') border-red-500 @enderror">
                        @error('tenant_stir_pinfl')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Faoliyat turi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tenant_activity_type" value="{{ old('tenant_activity_type') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_activity_type') border-red-500 @enderror"
                            placeholder="Masalan: Umumiy ovqatlanish, Savdo, Xizmat ko'rsatish">
                        @error('tenant_activity_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Manzil ma'lumotlari -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Manzil ma'lumotlari
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tuman <span class="text-red-500">*</span>
                        </label>
                      <select id="district_id" name="district_id"
                            onchange="onDistrictChange(this)"
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
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
                           <select id="mahalla_id" name="mahalla_id"
                                onchange="onMahallaChange(this)"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Mahallani tanlang yoki yarating</option>
                        </select>
                            <button type="button"
                                onclick="showAddMahallaModal(document.getElementById('district_id').value)"
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
                            <select id="street_id" name="street_id"
                                    onchange="onStreetChange(this)"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <option value="">Ko'chani tanlang yoki yarating</option>
                            </select>
                            <button type="button"
                                onclick="showAddStreetModal(document.getElementById('mahalla_id').value)"
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
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('house_number') border-red-500 @enderror"
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
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('additional_info') border-red-500 @enderror"
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H7m2 0v-6m10 6v-6M7 7h10M7 11h10">
                        </path>
                    </svg>
                    Tutash hudud ma'lumotlari
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tutash hududdagi faoliyat turi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="adjacent_activity_type" value="{{ old('adjacent_activity_type') }}"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('adjacent_activity_type') border-red-500 @enderror"
                            placeholder="Masalan: Umumiy ovqatlanish, Savdo, Xizmat ko'rsatish, Elektr quvvatlash stantsiyasi">
                        @error('adjacent_activity_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tutash hudud maydoni <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="adjacent_activity_land" value="{{ old('adjacent_activity_land') }}"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('adjacent_activity_land') border-red-500 @enderror"
                            placeholder="kv.m">
                        @error('adjacent_activity_land')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tutash hududdagi qurilmalar <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-lg p-3 @error('adjacent_facilities') border-red-500 @enderror">
                            @php
                                $facilities = [
                                    'kapital_qurilma' => 'Kapital qurilma',
                                    'mavjud_emas' => 'Mavjud emas',
                                    'yengil_qurilma' => 'Yengil qurilma',
                                    'bostirma' => 'Bostirma',
                                    'beton_maydoncha' => 'Beton maydoncha',
                                    'elektr_quvvatlash' => 'Elektr quvvatlash',
                                    'avtoturargoh' => 'Avtoturargoh',
                                    'boshqalar' => 'Boshqalar',
                                ];
                            @endphp
                            @foreach ($facilities as $value => $label)
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

                    <!-- Design Code File - NEW FIELD -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Loyiha kodi fayli (ixtiyoriy)
                        </label>
                        <input type="file" name="design_code_file" accept=".pdf,.doc,.docx,.dwg,.zip"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('design_code_file') border-red-500 @enderror">
                        <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX, DWG, ZIP formatida, maksimal 10MB</p>
                        @error('design_code_file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Show previously uploaded file if validation failed -->
                        @if(old('design_code_file_name'))
                            <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded">
                                <span class="text-sm text-blue-700">Yuklangan fayl: {{ old('design_code_file_name') }}</span>
                                <input type="hidden" name="design_code_file_temp" value="{{ old('design_code_file_temp') }}">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Geolokatsiya -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                        </path>
                    </svg>
                    Geolokatsiya
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kenglik (Latitude)</label>
                        <input type="number" step="any" name="latitude" id="latitude"
                            value="{{ old('latitude') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('latitude') border-red-500 @enderror"
                            placeholder="41.2995">
                        @error('latitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uzunlik (Longitude)</label>
                        <input type="number" step="any" name="longitude" id="longitude"
                            value="{{ old('longitude') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('longitude') border-red-500 @enderror"
                            placeholder="69.2401">
                        @error('longitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <button type="button" onclick="getCurrentLocation()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full">
                            Joylashuvni aniqlash
                        </button>
                    </div>
                </div>
                <div id="map" style="height: 400px;" class="border rounded-lg"></div>
                <p class="text-sm text-gray-500 mt-2">Xaritada bosing yoki "Joylashuvni aniqlash" tugmasini bosing</p>
            </div>

            <!-- Fayllar - Updated Image Section -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                        </path>
                    </svg>
                    Fayllar
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Images Section -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                Rasmlar (kamida 4 ta) <span class="text-red-500">*</span>
                            </label>
                            <button type="button" onclick="addImageField()"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                                + Qo'shish
                            </button>
                        </div>

                        <!-- Show validation errors from previous upload attempt -->
                        @if(session('uploaded_images'))
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">
                                <p class="text-sm text-green-700 font-medium">Avvalgi yuklash urinishida {{ count(session('uploaded_images')) }} ta rasm saqlandi:</p>
                                <ul class="text-xs text-green-600 mt-1">
                                    @foreach(session('uploaded_images') as $image)
                                        <li>{{ basename($image) }}</li>
                                    @endforeach
                                </ul>
                                <!-- Hidden inputs to preserve uploaded images -->
                                @foreach(session('uploaded_images') as $index => $image)
                                    <input type="hidden" name="temp_images[]" value="{{ $image }}">
                                @endforeach
                            </div>
                        @endif

                        <!-- Image Upload Fields Container -->
                        <div id="imageFieldsContainer" class="space-y-3">
                            <!-- Default 4 image fields will be added here by JavaScript -->
                        </div>

                        <!-- Image Counter -->
                        <div class="mt-3 p-2 bg-gray-50 rounded text-sm">
                            <span id="imageCounter" class="font-medium">Jami rasmlar: 0</span>
                            <span class="text-gray-500 ml-2">(Kamida 4 ta kerak)</span>
                        </div>

                        @error('images')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('images.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Act File -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Akt fayli (ixtiyoriy)
                        </label>
                        <input type="file" name="act_file" accept=".pdf,.doc,.docx"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('act_file') border-red-500 @enderror">
                        <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX formatida, maksimal 10MB</p>
                        @error('act_file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Show previously uploaded act file if validation failed -->
                        @if(session('uploaded_act_file'))
                            <div class="mt-2 p-2 bg-blue-50 border border-blue-200 rounded">
                                <span class="text-sm text-blue-700">Yuklangan fayl: {{ basename(session('uploaded_act_file')) }}</span>
                                <input type="hidden" name="temp_act_file" value="{{ session('uploaded_act_file') }}">
                            </div>
                        @endif
                    </div>
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
                    <input type="text" id="newMahallaName" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        placeholder="Mahalla nomini kiriting">
                </div>
                <div class="px-6 py-4 border-t flex justify-end space-x-2">
                    <button onclick="hideModal('addMahallaModal')"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Bekor qilish
                    </button>
                    <button onclick="addNewMahalla()"
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
                    <input type="text" id="newStreetName" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        placeholder="Ko'cha nomini kiriting">
                </div>
                <div class="px-6 py-4 border-t flex justify-end space-x-2">
                    <button onclick="hideModal('addStreetModal')"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Bekor qilish
                    </button>
                    <button onclick="addNewStreet()"
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
        // =============== IMAGE FIELD MANAGEMENT ===============

        let imageFieldIndex = 0;
        let totalImages = 0;
        let tempImages = @json(session('uploaded_images', []));

        // Initialize default image fields when page loads
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Content Loaded');
            console.log('Temp images from session:', tempImages);

            // Add 4 default image fields
            for (let i = 0; i < 4; i++) {
                addImageField();
            }

            // Account for temporary images from previous upload attempt
            if (tempImages.length > 0) {
                totalImages = tempImages.length;
            }

            updateImageCounter();
            initializeForm();
        });

        function addImageField() {
            const container = document.getElementById('imageFieldsContainer');
            const fieldId = 'image_field_' + imageFieldIndex;

            const fieldHtml = `
            <div id="${fieldId}" class="image-field border border-gray-200 rounded-lg p-3 bg-gray-50">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-medium text-gray-700">
                        Rasm ${imageFieldIndex + 1}
                    </label>
                    <button type="button" onclick="removeImageField('${fieldId}')"
                            class="text-red-500 hover:text-red-700 text-sm">
                        × O'chirish
                    </button>
                </div>

                <input type="file"
                       name="images[]"
                       id="image_input_${imageFieldIndex}"
                       accept="image/*"
                       onchange="handleImageChange(this, '${fieldId}')"
                       class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">

                <div class="mt-2 text-xs text-gray-500">
                    JPEG, PNG, JPG formatida, maksimal 2MB
                </div>

                <!-- Image Preview -->
                <div id="preview_${fieldId}" class="mt-3 hidden">
                    <img id="img_${fieldId}" src="" alt="Preview"
                         class="w-24 h-24 object-cover rounded border">
                    <div id="info_${fieldId}" class="text-xs text-gray-500 mt-1"></div>
                </div>
            </div>
        `;

            container.insertAdjacentHTML('beforeend', fieldHtml);
            imageFieldIndex++;
            updateImageCounter();
        }

        function removeImageField(fieldId) {
            const field = document.getElementById(fieldId);
            if (field) {
                // Check if this field had an image
                const input = field.querySelector('input[type="file"]');
                if (input && input.files.length > 0) {
                    totalImages--;
                }

                field.remove();
                updateImageCounter();
                renumberImageFields();
            }
        }

        function renumberImageFields() {
            const fields = document.querySelectorAll('.image-field');
            fields.forEach((field, index) => {
                const label = field.querySelector('label');
                if (label) {
                    label.textContent = `Rasm ${index + 1}`;
                }
            });
        }

        function handleImageChange(input, fieldId) {
            const file = input.files[0];
            const preview = document.getElementById(`preview_${fieldId}`);
            const img = document.getElementById(`img_${fieldId}`);
            const info = document.getElementById(`info_${fieldId}`);

            if (file) {
                // Validate file size (2MB = 2048KB)
                if (file.size > 2048 * 1024) {
                    alert('Fayl hajmi 2MB dan oshmasligi kerak!');
                    input.value = '';
                    preview.classList.add('hidden');
                    return;
                }

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Faqat rasm fayllarini tanlang!');
                    input.value = '';
                    preview.classList.add('hidden');
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    info.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);

                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
            } else {
                preview.classList.add('hidden');
                input.classList.remove('border-green-500');
            }

            updateImageCounter();
        }

        function updateImageCounter() {
            const counter = document.getElementById('imageCounter');
            const fields = document.querySelectorAll('.image-field input[type="file"]');
            let filledFields = 0;

            fields.forEach(input => {
                if (input.files.length > 0) {
                    filledFields++;
                }
            });

            // Add temporary images count
            const currentTotal = filledFields + tempImages.length;

            if (counter) {
                counter.textContent = `Jami rasmlar: ${currentTotal}`;

                // Update counter color based on requirement
                const counterContainer = counter.parentElement;
                if (currentTotal >= 4) {
                    counterContainer.className = 'mt-3 p-2 bg-green-50 border border-green-200 rounded text-sm';
                    counter.className = 'font-medium text-green-700';
                } else {
                    counterContainer.className = 'mt-3 p-2 bg-red-50 border border-red-200 rounded text-sm';
                    counter.className = 'font-medium text-red-700';
                }
            }

            totalImages = currentTotal;
        }

        // =============== AJAX FUNCTIONS FOR INDEPENDENT LOCATION HANDLING ===============

        /**
         * Tuman o'zgarganda - MAHALLA VA KO'CHA ALOHIDA YUKLANADI
         */
        function onDistrictChange(selectElement) {
            // Element yoki value qabul qilishi mumkin
            const districtId = typeof selectElement === 'object' ? selectElement.value : selectElement;

            console.log('District changed to:', districtId);
            console.log('District ID type:', typeof districtId);

            // FIXED: District ID ni string dan number ga o'tkazish va validation
            const numericDistrictId = parseInt(districtId);

            if (!districtId || districtId === '' || districtId === 'null' || districtId === 'undefined' || isNaN(numericDistrictId) || numericDistrictId < 1 || numericDistrictId > 12) {
                console.log('District ID noto\'g\'ri:', districtId, 'Reset qilish...');
                resetMahallaSelect();
                resetStreetSelect();
                return;
            }

            console.log('Valid district ID:', numericDistrictId, 'Mahalla va ko\'chalarni yuklash boshlandi...');
            // Mahalla va ko'chani parallel ravishda yuklash (bir-biriga bog'liq emas)
            loadMahallas(numericDistrictId);
            loadStreets(numericDistrictId);
        }

        /**
         * Mahalla o'zgarganda - FAQAT LOG QILISH (ko'chani reset qilmaslik)
         */
        function onMahallaChange(selectElement) {
            const mahallaId = typeof selectElement === 'object' ? selectElement.value : selectElement;
            console.log('Mahalla changed to:', mahallaId, '(Ko\'cha o\'zgartirilmaydi)');
            // Hech narsa qilmaymiz - ko'cha mustaqil
        }

        /**
         * Ko'cha o'zgarganda - FAQAT LOG QILISH (mahallani reset qilmaslik)
         */
        function onStreetChange(selectElement) {
            const streetId = typeof selectElement === 'object' ? selectElement.value : selectElement;
            console.log('Street changed to:', streetId, '(Mahalla o\'zgartirilmaydi)');
            // Hech narsa qilmaymiz - mahalla mustaqil
        }

        /**
         * Mahallalarni yuklash - FAQAT TUMAN ASOSIDA
         */
        function loadMahallas(districtId) {
            console.log('Loading mahallas for district:', districtId);

            const mahallaSelect = document.getElementById('mahalla_id');

            // FIXED: District ID validation (1-12 orasida bo'lishi kerak)
            const numericDistrictId = parseInt(districtId);
            if (!districtId || districtId === '' || districtId === 'null' || isNaN(numericDistrictId) || numericDistrictId < 1 || numericDistrictId > 12) {
                console.log('Invalid district ID for mahallas:', districtId);
                resetMahallaSelect();
                return;
            }

            // Loading holatini ko'rsatish
            mahallaSelect.innerHTML = '<option value="">Yuklanmoqda...</option>';
            mahallaSelect.disabled = true;

            const url = `/api/mahallas?district_id=${numericDistrictId}`;
            console.log('Fetching mahallas from:', url);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                console.log('Mahallas response status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Mahallas error response:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Mahallas data received:', data);

                // Selectni tozalash va boshlang'ich optionni qo'shish
                mahallaSelect.innerHTML = '<option value="">Mahallani tanlang yoki yarating</option>';

                // Mahallalarni qo'shish
                if (Array.isArray(data)) {
                    if (data.length > 0) {
                        data.forEach(mahalla => {
                            const option = new Option(mahalla.name, mahalla.id);
                            mahallaSelect.add(option);
                        });
                        console.log(`Successfully loaded ${data.length} mahallas`);
                    } else {
                        console.log('No mahallas found for district:', numericDistrictId);
                    }
                } else {
                    console.error('Mahallas data is not an array:', data);
                }

                mahallaSelect.disabled = false;

                // Eski qiymatni tiklash (agar mavjud bo'lsa)
                @if(old('mahalla_id'))
                    const oldMahallaId = '{{ old("mahalla_id") }}';
                    if (oldMahallaId) {
                        mahallaSelect.value = oldMahallaId;
                        console.log('Restored old mahalla value:', oldMahallaId);
                    }
                @endif
            })
            .catch(error => {
                console.error('Error loading mahallas:', error);
                mahallaSelect.innerHTML = '<option value="">Xato! Qayta urinib ko\'ring</option>';
                mahallaSelect.disabled = false;
                alert('Mahallalarni yuklashda xato yuz berdi: ' + error.message);
            });
        }

        /**
         * Ko'chalarni yuklash - FAQAT TUMAN ASOSIDA (mahallaga bog'liq emas)
         */
        function loadStreets(districtId) {
            console.log('Loading streets for district:', districtId);

            const streetSelect = document.getElementById('street_id');

            // FIXED: District ID validation (1-12 orasida bo'lishi kerak)
            const numericDistrictId = parseInt(districtId);
            if (!districtId || districtId === '' || districtId === 'null' || isNaN(numericDistrictId) || numericDistrictId < 1 || numericDistrictId > 12) {
                console.log('Invalid district ID for streets:', districtId);
                resetStreetSelect();
                return;
            }

            // Loading holatini ko'rsatish
            streetSelect.innerHTML = '<option value="">Yuklanmoqda...</option>';
            streetSelect.disabled = true;

            // FIXED: District ID ni to'g'ri yuborish
            const url = `/api/streets?district_id=${numericDistrictId}`;
            console.log('Fetching streets from URL:', url);

            fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                console.log('Streets response status:', response.status);
                console.log('Streets response URL:', response.url);

                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('Streets response error body:', text);
                        throw new Error(`HTTP error! status: ${response.status}, body: ${text}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Streets data received:', data);
                console.log('Streets data type:', typeof data);
                console.log('Is array:', Array.isArray(data));

                // Selectni tozalash va boshlang'ich optionni qo'shish
                streetSelect.innerHTML = '<option value="">Ko\'chani tanlang yoki yarating</option>';

                // Ko'chalarni qo'shish
                if (Array.isArray(data)) {
                    if (data.length > 0) {
                        data.forEach(street => {
                            console.log('Adding street:', street);
                            const option = new Option(street.name, street.id);
                            streetSelect.add(option);
                        });
                        console.log(`Successfully loaded ${data.length} streets`);
                    } else {
                        console.log('No streets found for district:', numericDistrictId);
                        // Debug uchun
                        const debugOption = new Option(`Ushbu tumanda ko'chalar topilmadi (ID: ${numericDistrictId})`, '');
                        debugOption.disabled = true;
                        debugOption.style.color = '#999';
                        streetSelect.add(debugOption);
                    }
                } else {
                    console.error('Streets data is not an array:', data);
                    if (data && data.message) {
                        const errorOption = new Option(`Xato: ${data.message}`, '');
                        errorOption.disabled = true;
                        errorOption.style.color = '#f00';
                        streetSelect.add(errorOption);
                    }
                }

                streetSelect.disabled = false;

                // Eski qiymatni tiklash (agar mavjud bo'lsa)
                @if(old('street_id'))
                    const oldStreetId = '{{ old("street_id") }}';
                    if (oldStreetId) {
                        streetSelect.value = oldStreetId;
                        console.log('Restored old street value:', oldStreetId);
                    }
                @endif
            })
            .catch(error => {
                console.error('Error loading streets:', error);
                streetSelect.innerHTML = '<option value="">Xato! Qayta urinib ko\'ring</option>';
                streetSelect.disabled = false;

                // Production da foydalanuvchiga oddiy xabar ko'rsatish
                if (window.location.hostname !== 'localhost' && !window.location.hostname.includes('127.0.0.1')) {
                    alert('Ko\'chalarni yuklashda xato yuz berdi. Sahifani yangilab ko\'ring.');
                } else {
                    alert('Ko\'chalarni yuklashda xato yuz berdi: ' + error.message);
                }
            });
        }

        /**
         * Mahalla selectini boshlang'ich holatga qaytarish
         */
        function resetMahallaSelect() {
            const mahallaSelect = document.getElementById('mahalla_id');
            if (mahallaSelect) {
                mahallaSelect.innerHTML = '<option value="">Mahallani tanlang yoki yarating</option>';
                mahallaSelect.disabled = false;
            }
        }

        /**
         * Ko'cha selectini boshlang'ich holatga qaytarish
         */
        function resetStreetSelect() {
            const streetSelect = document.getElementById('street_id');
            if (streetSelect) {
                streetSelect.innerHTML = '<option value="">Ko\'chani tanlang yoki yarating</option>';
                streetSelect.disabled = false;
            }
        }

        // =============== MODAL FUNCTIONS WITH AJAX ===============

        function showAddMahallaModal() {
            const districtSelect = document.getElementById('district_id');
            const districtId = districtSelect ? districtSelect.value : null;

            console.log('showAddMahallaModal called with districtId:', districtId);

            if (!districtId) {
                alert('Avval tumanni tanlang!');
                return;
            }

            document.getElementById('newMahallaDistrictId').value = districtId;
            document.getElementById('addMahallaModal').classList.remove('hidden');
            document.getElementById('newMahallaName').focus();
        }

        function showAddStreetModal() {
            const districtSelect = document.getElementById('district_id');
            const districtId = districtSelect ? districtSelect.value : null;

            console.log('showAddStreetModal called with districtId:', districtId);

            if (!districtId) {
                alert('Avval tumanni tanlang!');
                return;
            }

            document.getElementById('newStreetDistrictId').value = districtId;
            document.getElementById('addStreetModal').classList.remove('hidden');
            document.getElementById('newStreetName').focus();
        }

        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');

                // Clear form inputs
                if (modalId === 'addMahallaModal') {
                    const nameInput = document.getElementById('newMahallaName');
                    if (nameInput) nameInput.value = '';
                } else if (modalId === 'addStreetModal') {
                    const nameInput = document.getElementById('newStreetName');
                    if (nameInput) nameInput.value = '';
                }
            }
        }

        /**
         * Yangi mahalla qo'shish
         */
        function addNewMahalla() {
            const districtId = document.getElementById('newMahallaDistrictId').value;
            const name = document.getElementById('newMahallaName').value.trim();

            console.log('addNewMahalla called with:', { districtId, name });

            if (!name) {
                alert('Mahalla nomini kiriting!');
                document.getElementById('newMahallaName').focus();
                return;
            }

            if (!districtId) {
                alert('Tuman tanlanmagan!');
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!token) {
                alert('CSRF token topilmadi! Sahifani yangilab ko\'ring.');
                return;
            }

            const addButton = document.querySelector('#addMahallaModal button[onclick="addNewMahalla()"]');
            const originalText = addButton ? addButton.textContent : 'Qo\'shish';

            if (addButton) {
                addButton.disabled = true;
                addButton.textContent = 'Qo\'shilmoqda...';
            }

            fetch('/api/mahallas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    name: name,
                    district_id: districtId
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(result => {
                console.log('Add mahalla result:', result);

                if (result.success && result.mahalla) {
                    const select = document.getElementById('mahalla_id');
                    if (select) {
                        const mahalla = result.mahalla;
                        const option = new Option(mahalla.name, mahalla.id, true, true);
                        select.add(option);
                    }

                    hideModal('addMahallaModal');
                    alert('Mahalla muvaffaqiyatli qo\'shildi!');
                } else {
                    alert('Xato: ' + (result.message || 'Noma\'lum xato'));
                }
            })
            .catch(error => {
                console.error('Error adding mahalla:', error);
                alert('Xato yuz berdi: ' + error.message);
            })
            .finally(() => {
                if (addButton) {
                    addButton.disabled = false;
                    addButton.textContent = originalText;
                }
            });
        }

        /**
         * Yangi ko'cha qo'shish
         */
        function addNewStreet() {
            const districtId = document.getElementById('newStreetDistrictId').value;
            const name = document.getElementById('newStreetName').value.trim();

            console.log('addNewStreet called with:', { districtId, name });

            if (!name) {
                alert('Ko\'cha nomini kiriting!');
                document.getElementById('newStreetName').focus();
                return;
            }

            if (!districtId) {
                alert('Tuman tanlanmagan!');
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!token) {
                alert('CSRF token topilmadi! Sahifani yangilab ko\'ring.');
                return;
            }

            const addButton = document.querySelector('#addStreetModal button[onclick="addNewStreet()"]');
            const originalText = addButton ? addButton.textContent : 'Qo\'shish';

            if (addButton) {
                addButton.disabled = true;
                addButton.textContent = 'Qo\'shilmoqda...';
            }

            fetch('/api/streets', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    name: name,
                    district_id: districtId
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(result => {
                console.log('Add street result:', result);

                if (result.success && result.street) {
                    const select = document.getElementById('street_id');
                    if (select) {
                        const street = result.street;
                        const option = new Option(street.name, street.id, true, true);
                        select.add(option);
                    }

                    hideModal('addStreetModal');
                    alert('Ko\'cha muvaffaqiyatli qo\'shildi!');
                } else {
                    alert('Xato: ' + (result.message || 'Noma\'lum xato'));
                }
            })
            .catch(error => {
                console.error('Error adding street:', error);
                alert('Xato yuz berdi: ' + error.message);
            })
            .finally(() => {
                if (addButton) {
                    addButton.disabled = false;
                    addButton.textContent = originalText;
                }
            });
        }

        // =============== FORM VALIDATION ===============

        function validateForm() {
            console.log('Validating form...');

            const requiredFields = document.querySelectorAll('[required]');
            let isValid = true;

            // Validate required fields
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            // Validate images - must have at least 4
            const newImagesCount = document.querySelectorAll('.image-field input[type="file"]').length;
            let validNewImages = 0;

            document.querySelectorAll('.image-field input[type="file"]').forEach(input => {
                if (input.files.length > 0) {
                    validNewImages++;
                }
            });

            const totalValidImages = validNewImages + tempImages.length;

            if (totalValidImages < 4) {
                alert('Kamida 4 ta rasm yuklang!');
                isValid = false;

                // Highlight empty image fields
                const imageFields = document.querySelectorAll('.image-field input[type="file"]');
                imageFields.forEach(input => {
                    if (input.files.length === 0) {
                        input.classList.add('border-red-500');
                    }
                });
            }

            // Check adjacent facilities - at least one must be selected
            const adjacentFacilities = document.querySelectorAll('input[name="adjacent_facilities[]"]:checked');
            if (adjacentFacilities.length === 0) {
                alert('Tutash hududdagi qurilmalardan kamida bittasini tanlang!');
                isValid = false;
            }

            // Check coordinates if provided
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            if (latInput && lngInput && (latInput.value || lngInput.value)) {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);

                if (isNaN(lat) || isNaN(lng)) {
                    alert('Koordinatalar noto\'g\'ri formatda kiritilgan!');
                    isValid = false;
                } else if (typeof isWithinTashkent === 'function' && !isWithinTashkent(lat, lng)) {
                    alert('Koordinatalar Toshkent shahri chegaralarida bo\'lishi kerak!');
                    isValid = false;
                }
            }

            if (!isValid) {
                // Scroll to first error
                const firstError = document.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }

            console.log('Form validation result:', isValid);
            return isValid;
        }

        // =============== TENANT FIELDS TOGGLE ===============

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

        // =============== LOCATION FUNCTIONS ===============

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        const latInput = document.getElementById('latitude');
                        const lngInput = document.getElementById('longitude');

                        if (latInput) latInput.value = lat;
                        if (lngInput) lngInput.value = lng;

                        // Update map if available
                        if (typeof map !== 'undefined' && map) {
                            map.setView([lat, lng], 16);

                            // Remove existing marker and add new one
                            if (typeof marker !== 'undefined' && marker) {
                                map.removeLayer(marker);
                            }

                            if (typeof L !== 'undefined') {
                                marker = L.marker([lat, lng]).addTo(map);
                            }
                        }

                        alert('Joylashuv muvaffaqiyatli aniqlandi!');
                    },
                    function(error) {
                        let errorMessage = 'Joylashuvni aniqlab bo\'lmadi: ';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage += 'Ruxsat berilmagan';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage += 'Ma\'lumot mavjud emas';
                                break;
                            case error.TIMEOUT:
                                errorMessage += 'Vaqt tugadi';
                                break;
                            default:
                                errorMessage += 'Noma\'lum xato';
                                break;
                        }
                        alert(errorMessage);
                    }
                );
            } else {
                alert('Brauzer geolokatsiyani qo\'llab-quvvatlamaydi!');
            }
        }

        // =============== FORM INITIALIZATION ===============

        function initializeForm() {
            console.log('Form initializing...');

            // Wait for global scripts to load
            setTimeout(() => {
                // Check if global functions are available
                if (typeof initMap === 'function' && typeof TASHKENT_CONFIG !== 'undefined') {
                    console.log('Global functions found, initializing map...');
                    initMap('map', TASHKENT_CONFIG.center.lat, TASHKENT_CONFIG.center.lng);
                } else {
                    console.log('Global map functions not found, skipping map initialization');
                }

                // Load mahallas and streets if district is already selected
                const districtSelect = document.getElementById('district_id');
                if (districtSelect && districtSelect.value) {
                    console.log('District already selected, loading locations:', districtSelect.value);
                    onDistrictChange(districtSelect.value);
                }
            }, 500);

            // Setup real-time validation
            setupRealTimeValidation();

            console.log('Form initialization complete');
        }

        // =============== REAL-TIME VALIDATION ===============

        function setupRealTimeValidation() {
            // Required fields validation
            const requiredFields = document.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                field.addEventListener('blur', function() {
                    if (this.value.trim()) {
                        this.classList.remove('border-red-500');
                        this.classList.add('border-green-500');
                    } else {
                        this.classList.add('border-red-500');
                        this.classList.remove('border-green-500');
                    }
                });

                field.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('border-red-500');
                    }
                });
            });

            // Adjacent facilities validation
            const facilityCheckboxes = document.querySelectorAll('input[name="adjacent_facilities[]"]');
            const facilityContainer = facilityCheckboxes.length > 0 ? facilityCheckboxes[0].closest('.space-y-2') : null;

            facilityCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const checked = document.querySelectorAll('input[name="adjacent_facilities[]"]:checked');

                    if (facilityContainer) {
                        if (checked.length > 0) {
                            facilityContainer.classList.remove('border-red-500');
                            facilityContainer.classList.add('border-green-500');
                        } else {
                            facilityContainer.classList.add('border-red-500');
                            facilityContainer.classList.remove('border-green-500');
                        }
                    }
                });
            });
        }

        // =============== KEYBOARD SHORTCUTS ===============

        document.addEventListener('keydown', function(e) {
            // Close modals with Escape key
            if (e.key === 'Escape') {
                const modals = ['addMahallaModal', 'addStreetModal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal && !modal.classList.contains('hidden')) {
                        hideModal(modalId);
                    }
                });
            }

            // Add image field with Ctrl+I
            if (e.ctrlKey && e.key === 'i') {
                e.preventDefault();
                addImageField();
            }
        });

        // =============== EXPORT FUNCTIONS FOR GLOBAL ACCESS ===============

        // Make functions available globally for onclick handlers
        window.addImageField = addImageField;
        window.removeImageField = removeImageField;
        window.handleImageChange = handleImageChange;
        window.validateForm = validateForm;
        window.toggleTenantFields = toggleTenantFields;
        window.showAddMahallaModal = showAddMahallaModal;
        window.showAddStreetModal = showAddStreetModal;
        window.hideModal = hideModal;
        window.addNewMahalla = addNewMahalla;
        window.addNewStreet = addNewStreet;
        window.getCurrentLocation = getCurrentLocation;
        window.onDistrictChange = onDistrictChange;
        window.onMahallaChange = onMahallaChange;
        window.onStreetChange = onStreetChange;
        window.loadMahallas = loadMahallas;
        window.loadStreets = loadStreets;

        // =============== DEBUG HELPER (DEVELOPMENT ONLY) ===============

        function debugForm() {
            // Only show debug info in development
            if (window.location.hostname === 'localhost' || window.location.hostname.includes('127.0.0.1')) {
                console.log('=== Debug Ma\'lumotlari ===');
                console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));

                const districtSelect = document.getElementById('district_id');
                const mahallaSelect = document.getElementById('mahalla_id');
                const streetSelect = document.getElementById('street_id');

                console.log('Tumanlar soni:', districtSelect ? districtSelect.options.length : 'Topilmadi');
                console.log('Joriy tuman:', districtSelect ? districtSelect.value : 'Topilmadi');
                console.log('Joriy mahalla:', mahallaSelect ? mahallaSelect.value : 'Topilmadi');
                console.log('Joriy ko\'cha:', streetSelect ? streetSelect.value : 'Topilmadi');
                console.log('Rasmlar soni:', totalImages);

                if (districtSelect && districtSelect.value) {
                    console.log('Tanlangan tuman uchun test:', districtSelect.value);

                    // Mahallalar testini o'tkazish
                    fetch(`/api/mahallas?district_id=${districtSelect.value}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log('Mahallalar test javobi:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Mahallalar soni:', Array.isArray(data) ? data.length : 'Massiv emas');
                    })
                    .catch(error => {
                        console.error('Mahallalar test xatosi:', error);
                    });

                    // Ko'chalar testini o'tkazish
                    fetch(`/api/streets?district_id=${districtSelect.value}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => {
                        console.log('Ko\'chalar test javobi:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Ko\'chalar soni:', Array.isArray(data) ? data.length : 'Massiv emas');
                    })
                    .catch(error => {
                        console.error('Ko\'chalar test xatosi:', error);
                    });
                }

                console.log('======================');
            }
        }

        // Make debug function available globally (development only)
        if (window.location.hostname === 'localhost' || window.location.hostname.includes('127.0.0.1')) {
            window.debugForm = debugForm;
        }

        // =============== ERROR HANDLING ===============

        // Global error handler for unhandled promise rejections
        window.addEventListener('unhandledrejection', function(event) {
            console.error('Unhandled promise rejection:', event.reason);

            // In production, don't show technical errors to users
            if (window.location.hostname !== 'localhost' && !window.location.hostname.includes('127.0.0.1')) {
                event.preventDefault(); // Prevent the default browser behavior
            }
        });

        // Global error handler
        window.addEventListener('error', function(event) {
            console.error('Global error:', event.error);

            // In production, log but don't alert users about technical errors
            if (window.location.hostname !== 'localhost' && !window.location.hostname.includes('127.0.0.1')) {
                // You could send this to a logging service
                // logErrorToService(event.error);
            }
        });

    </script>
@endsection
