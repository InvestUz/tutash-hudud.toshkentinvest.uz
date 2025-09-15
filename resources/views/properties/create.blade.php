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

        <!-- Error Display -->
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

            <!-- Cadastral Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Kadastr va asosiy ma'lumotlar
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kadastr raqami <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="building_cadastr_number" value="{{ old('building_cadastr_number') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('building_cadastr_number') border-red-500 @enderror"
                            placeholder="Bino kadastr raqamini kiriting">
                        @error('building_cadastr_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            STIR/PINFL <span class="text-red-500">*</span>
                        </label>
                        <div class="flex">
                            <input type="text" name="owner_stir_pinfl" id="owner_stir_pinfl" value="{{ old('owner_stir_pinfl') }}" required
                                class="w-full border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('owner_stir_pinfl') border-red-500 @enderror"
                                placeholder="9 raqam (yuridik) yoki 14 raqam (jismoniy)">
                            <button type="button" onclick="validateOwnerStirPinfl()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg">
                                Tekshirish
                            </button>
                        </div>
                        <div id="owner_validation_result" class="mt-2 text-sm"></div>
                        @error('owner_stir_pinfl')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Korxona nomi / F.I.SH <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('owner_name') border-red-500 @enderror">
                        @error('owner_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
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
                        <select id="district_id" name="district_id" onchange="PropertyForm.onDistrictChange(this)"
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" required>
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
                            <select id="mahalla_id" name="mahalla_id" onchange="PropertyForm.onMahallaChange(this)"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Mahallani tanlang yoki yarating</option>
                            </select>
                            <button type="button" onclick="PropertyForm.showAddMahallaModal()"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-r-lg">+</button>
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
                            <select id="street_id" name="street_id" onchange="PropertyForm.onStreetChange(this)"
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Ko'chani tanlang yoki yarating</option>
                            </select>
                            <button type="button" onclick="PropertyForm.showAddStreetModal()"
                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-r-lg">+</button>
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
                </div>
            </div>

            <!-- Contact Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Aloqa ma'lumotlari
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Rahbar F.I.SH <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="director_name" value="{{ old('director_name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('director_name') border-red-500 @enderror"
                            placeholder="Rahbar ism-familiyasini kiriting">
                        @error('director_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Telefon raqami <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('phone_number') border-red-500 @enderror"
                            placeholder="+998 90 123 45 67">
                        @error('phone_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Building Measurements -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Bino o'lchamlari
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Fasad uzunligi (m) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="building_facade_length" value="{{ old('building_facade_length') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('building_facade_length') border-red-500 @enderror"
                            placeholder="4">
                        @error('building_facade_length')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Yozgi terassa tomonlari (m) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="summer_terrace_sides" value="{{ old('summer_terrace_sides') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('summer_terrace_sides') border-red-500 @enderror"
                            placeholder="1.2">
                        @error('summer_terrace_sides')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Yo'lgacha masofa (m) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="distance_to_roadway" value="{{ old('distance_to_roadway') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('distance_to_roadway') border-red-500 @enderror"
                            placeholder="1.2">
                        @error('distance_to_roadway')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Trotuargacha masofa (m) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="distance_to_sidewalk" value="{{ old('distance_to_sidewalk') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('distance_to_sidewalk') border-red-500 @enderror"
                            placeholder="1.2">
                        @error('distance_to_sidewalk')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Umumiy maydon (m²) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" name="total_area" value="{{ old('total_area') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('total_area') border-red-500 @enderror"
                            placeholder="100">
                        @error('total_area')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Usage Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H7m2 0v-6m10 6v-6M7 7h10M7 11h10"></path>
                    </svg>
                    Foydalanish ma'lumotlari
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foydalanish maqsadi <span class="text-red-500">*</span>
                        </label>
                        <select name="usage_purpose" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('usage_purpose') border-red-500 @enderror">
                            <option value="">Tanlang</option>
                            <option value="Umumiy ovqatlanish" {{ old('usage_purpose') == 'Umumiy ovqatlanish' ? 'selected' : '' }}>Umumiy ovqatlanish</option>
                            <option value="Savdo" {{ old('usage_purpose') == 'Savdo' ? 'selected' : '' }}>Savdo</option>
                            <option value="Xizmat ko'rsatish" {{ old('usage_purpose') == 'Xizmat ko\'rsatish' ? 'selected' : '' }}>Xizmat ko'rsatish</option>
                            <option value="Boshqa" {{ old('usage_purpose') == 'Boshqa' ? 'selected' : '' }}>Boshqa</option>
                        </select>
                        @error('usage_purpose')
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Terassada qurilmalar <span class="text-red-500">*</span>
                        </label>
                        <select name="terrace_buildings_available" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('terrace_buildings_available') border-red-500 @enderror">
                            <option value="">Tanlang</option>
                            <option value="Xa" {{ old('terrace_buildings_available') == 'Xa' ? 'selected' : '' }}>Xa</option>
                            <option value="Yoq" {{ old('terrace_buildings_available') == 'Yoq' ? 'selected' : '' }}>Yoq</option>
                        </select>
                        @error('terrace_buildings_available')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Doimiy qurilmalar <span class="text-red-500">*</span>
                        </label>
                        <select name="terrace_buildings_permanent" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('terrace_buildings_permanent') border-red-500 @enderror">
                            <option value="">Tanlang</option>
                            <option value="Xa" {{ old('terrace_buildings_permanent') == 'Xa' ? 'selected' : '' }}>Xa</option>
                            <option value="Yoq" {{ old('terrace_buildings_permanent') == 'Yoq' ? 'selected' : '' }}>Yoq</option>
                        </select>
                        @error('terrace_buildings_permanent')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ruxsatnoma mavjudmi? <span class="text-red-500">*</span>
                        </label>
                        <select name="has_permit" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('has_permit') border-red-500 @enderror">
                            <option value="">Tanlang</option>
                            <option value="Xa" {{ old('has_permit') == 'Xa' ? 'selected' : '' }}>Xa</option>
                            <option value="Yoq" {{ old('has_permit') == 'Yoq' ? 'selected' : '' }}>Yoq</option>
                        </select>
                        @error('has_permit')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Tenant Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Ijarachi ma'lumotlari
                </h3>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="has_tenant" value="1" {{ old('has_tenant') ? 'checked' : '' }}
                            onchange="PropertyForm.toggleTenantFields(this)"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Ijarachi mavjud</span>
                    </label>
                </div>

                <div id="tenantFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('has_tenant') ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ijarachi STIR/PINFL
                        </label>
                        <div class="flex">
                            <input type="text" name="tenant_stir_pinfl" id="tenant_stir_pinfl" value="{{ old('tenant_stir_pinfl') }}"
                                class="w-full border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_stir_pinfl') border-red-500 @enderror">
                            <button type="button" onclick="validateTenantStirPinfl()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg">
                                Tekshirish
                            </button>
                        </div>
                        <div id="tenant_validation_result" class="mt-2 text-sm"></div>
                        @error('tenant_stir_pinfl')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ijarachi nomi / F.I.SH
                        </label>
                        <input type="text" name="tenant_name" id="tenant_name" value="{{ old('tenant_name') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('tenant_name') border-red-500 @enderror">
                        @error('tenant_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ijarachi faoliyat turi
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

            <!-- Adjacent Area Information -->
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
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('adjacent_activity_type') border-red-500 @enderror"
                            placeholder="Masalan: Umumiy ovqatlanish, Savdo, Xizmat ko'rsatish">
                        @error('adjacent_activity_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tutash hudud maydoni <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="adjacent_activity_land" value="{{ old('adjacent_activity_land') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('adjacent_activity_land') border-red-500 @enderror"
                            placeholder="kv.m">
                        @error('adjacent_activity_land')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
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
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Qo'shimcha ma'lumotlar
                </h3>
                <div>
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

            <!-- Map and Location -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Geolokatsiya va xarita
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kenglik (Latitude)</label>
                        <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('latitude') border-red-500 @enderror"
                            placeholder="41.2995">
                        @error('latitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Uzunlik (Longitude)</label>
                        <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('longitude') border-red-500 @enderror"
                            placeholder="69.2401">
                        @error('longitude')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="button" onclick="PropertyForm.getCurrentLocation()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex-1">
                            Joylashuvni aniqlash
                        </button>
                        <button type="button" onclick="PropertyForm.toggleDrawingMode()" id="drawButton"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex-1">
                            Poligon chizish
                        </button>
                    </div>
                </div>

                <!-- Map Container -->
                <div id="propertyMap" style="height: 500px;" class="border rounded-lg mb-4"></div>

                <!-- Hidden field for polygon coordinates -->
                <input type="hidden" name="polygon_coordinates" id="polygon_coordinates" value="{{ old('polygon_coordinates') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <h4 class="font-medium text-blue-800 mb-2">Marker qo'yish:</h4>
                        <p>Xaritada bosing yoki "Joylashuvni aniqlash" tugmasini bosing</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <h4 class="font-medium text-green-800 mb-2">Poligon chizish:</h4>
                        <p>"Poligon chizish" tugmasini bosib, xaritada nuqtalarni belgilang</p>
                    </div>
                </div>
            </div>

            <!-- Files Upload -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                    Fayllar yuklash
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Images Section -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                Rasmlar (kamida 4 ta) <span class="text-red-500">*</span>
                            </label>
                            <button type="button" onclick="PropertyForm.addImageField()"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                                + Qo'shish
                            </button>
                        </div>

                        <!-- Image Upload Fields Container -->
                        <div id="imageFieldsContainer" class="space-y-3">
                            <!-- Default image fields will be added here by JavaScript -->
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

                    <!-- Other Files -->
                    <div class="space-y-4">
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
                        </div>

                        <!-- Design Code File -->
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
                        </div>
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
                    <button onclick="PropertyForm.hideModal('addMahallaModal')"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Bekor qilish
                    </button>
                    <button onclick="PropertyForm.addNewMahalla()"
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
                    <input type="hidden" id="newStreetDistrictId">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ko'cha nomi</label>
                    <input type="text" id="newStreetName" class="w-full border border-gray-300 rounded-lg px-3 py-2"
                        placeholder="Ko'cha nomini kiriting">
                </div>
                <div class="px-6 py-4 border-t flex justify-end space-x-2">
                    <button onclick="PropertyForm.hideModal('addStreetModal')"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Bekor qilish
                    </button>
                    <button onclick="PropertyForm.addNewStreet()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Qo'shish
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

    <script>
        // Global namespace to avoid conflicts
        const PropertyForm = {
            // Variables
            propertyMap: null,
            propertyMarker: null,
            drawnItems: null,
            drawControl: null,
            isDrawingMode: false,
            imageFieldIndex: 0,
            totalImages: 0,

            // Tashkent bounds
            TASHKENT_BOUNDS: {
                center: [41.2995, 69.2401],
                bounds: [
                    [40.9, 68.8], // Southwest
                    [41.6, 69.8]  // Northeast
                ]
            },

            // Initialize everything when DOM is loaded
            init: function() {
                console.log('PropertyForm initializing...');

                // Add default image fields
                for (let i = 0; i < 4; i++) {
                    this.addImageField();
                }
                this.updateImageCounter();

                // Initialize map
                this.initializeMap();

                // Load locations if district is already selected
                const districtSelect = document.getElementById('district_id');
                if (districtSelect && districtSelect.value) {
                    this.onDistrictChange(districtSelect);
                }

                console.log('PropertyForm initialized successfully');
            },

            // Initialize map
            initializeMap: function() {
                // Initialize map
                this.propertyMap = L.map('propertyMap').setView(this.TASHKENT_BOUNDS.center, 12);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(this.propertyMap);

                // Initialize drawn items layer
                this.drawnItems = new L.FeatureGroup();
                this.propertyMap.addLayer(this.drawnItems);

                // Initialize draw control
                this.drawControl = new L.Control.Draw({
                    position: 'topright',
                    draw: {
                        polygon: {
                            allowIntersection: false,
                            showArea: true
                        },
                        rectangle: false,
                        circle: false,
                        circlemarker: false,
                        marker: false,
                        polyline: false
                    },
                    edit: {
                        featureGroup: this.drawnItems,
                        remove: true
                    }
                });

                // Map click event for marker placement
                const self = this;
                this.propertyMap.on('click', function(e) {
                    if (!self.isDrawingMode) {
                        console.log('Map clicked at:', e.latlng.lat, e.latlng.lng);
                        self.placeMarker(e.latlng.lat, e.latlng.lng);
                    }
                });

                // Draw events
                this.propertyMap.on(L.Draw.Event.CREATED, function(e) {
                    const layer = e.layer;
                    self.drawnItems.addLayer(layer);

                    // Save polygon coordinates
                    if (e.layerType === 'polygon') {
                        const coords = layer.getLatLngs()[0].map(point => [point.lng, point.lat]);
                        coords.push(coords[0]); // Close the polygon
                        document.getElementById('polygon_coordinates').value = JSON.stringify([coords]);
                    }
                });

                this.propertyMap.on(L.Draw.Event.EDITED, function(e) {
                    const layers = e.layers;
                    layers.eachLayer(function(layer) {
                        if (layer instanceof L.Polygon) {
                            const coords = layer.getLatLngs()[0].map(point => [point.lng, point.lat]);
                            coords.push(coords[0]);
                            document.getElementById('polygon_coordinates').value = JSON.stringify([coords]);
                        }
                    });
                });

                this.propertyMap.on(L.Draw.Event.DELETED, function(e) {
                    document.getElementById('polygon_coordinates').value = '';
                });

                // Don't restrict map bounds for marker placement - only for viewing
                // this.propertyMap.setMaxBounds(this.TASHKENT_BOUNDS.bounds);

                console.log('Map initialized successfully');
            },

            // Place marker on map
            placeMarker: function(lat, lng) {
                console.log('Placing marker at:', lat, lng);

                // Remove existing marker
                if (this.propertyMarker) {
                    this.propertyMap.removeLayer(this.propertyMarker);
                    console.log('Removed existing marker');
                }

                // Add new marker - allow placement anywhere, not restricted by bounds
                this.propertyMarker = L.marker([lat, lng]).addTo(this.propertyMap);
                console.log('Added new marker at:', lat, lng);

                // Update coordinate inputs
                document.getElementById('latitude').value = lat.toFixed(8);
                document.getElementById('longitude').value = lng.toFixed(8);

                console.log('Updated coordinate inputs');
            },

            // Toggle drawing mode
            toggleDrawingMode: function() {
                const button = document.getElementById('drawButton');

                if (!this.isDrawingMode) {
                    this.propertyMap.addControl(this.drawControl);
                    button.textContent = 'Chizishni to\'xtatish';
                    button.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex-1';
                    this.isDrawingMode = true;
                } else {
                    this.propertyMap.removeControl(this.drawControl);
                    button.textContent = 'Poligon chizish';
                    button.className = 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex-1';
                    this.isDrawingMode = false;
                }
            },

            // Get current location
            getCurrentLocation: function() {
                console.log('Getting current location...');

                if (!this.propertyMap) {
                    console.log('Map is not initialized!');
                    alert('Xarita hali yuklanmagan!');
                    return;
                }

                if (navigator.geolocation) {
                    const self = this;
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            console.log('Location found:', lat, lng);

                            // Always place marker first
                            self.placeMarker(lat, lng);
                            self.propertyMap.setView([lat, lng], 16);

                            // Check if within Tashkent bounds and show appropriate message
                            const isWithinTashkent = (lat >= self.TASHKENT_BOUNDS.bounds[0][0] && lat <= self.TASHKENT_BOUNDS.bounds[1][0] &&
                                                    lng >= self.TASHKENT_BOUNDS.bounds[0][1] && lng <= self.TASHKENT_BOUNDS.bounds[1][1]);

                            if (isWithinTashkent) {
                                alert('Joylashuv muvaffaqiyatli aniqlandi!');
                            } else {
                                alert('Joylashuv aniqlandi, lekin Toshkent shahri chegaralaridan tashqarida. Markerni qo\'lda moslashtiring.');
                            }
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
            },

            // District change handler
            onDistrictChange: function(selectElement) {
                const districtId = typeof selectElement === 'object' ? selectElement.value : selectElement;

                console.log('District changed to:', districtId);

                if (!districtId) {
                    this.resetMahallaSelect();
                    this.resetStreetSelect();
                    return;
                }

                this.loadMahallas(districtId);
                this.loadStreets(districtId);
            },

            // Mahalla change handler
            onMahallaChange: function(selectElement) {
                const mahallaId = typeof selectElement === 'object' ? selectElement.value : selectElement;
                console.log('Mahalla changed to:', mahallaId);
            },

            // Street change handler
            onStreetChange: function(selectElement) {
                const streetId = typeof selectElement === 'object' ? selectElement.value : selectElement;
                console.log('Street changed to:', streetId);
            },

            // Load mahallas
            loadMahallas: async function(districtId) {
                const mahallaSelect = document.getElementById('mahalla_id');

                if (!districtId) {
                    this.resetMahallaSelect();
                    return;
                }

                mahallaSelect.innerHTML = '<option value="">Yuklanmoqda...</option>';
                mahallaSelect.disabled = true;

                try {
                    const response = await fetch(`/api/mahallas?district_id=${districtId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    mahallaSelect.innerHTML = '<option value="">Mahallani tanlang yoki yarating</option>';

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(mahalla => {
                            const option = new Option(mahalla.name, mahalla.id);
                            mahallaSelect.add(option);
                        });
                    }

                    mahallaSelect.disabled = false;
                } catch (error) {
                    console.error('Error loading mahallas:', error);
                    mahallaSelect.innerHTML = '<option value="">Xato! Qayta urinib ko\'ring</option>';
                    mahallaSelect.disabled = false;
                }
            },

            // Load streets
            loadStreets: async function(districtId) {
                const streetSelect = document.getElementById('street_id');

                if (!districtId) {
                    this.resetStreetSelect();
                    return;
                }

                streetSelect.innerHTML = '<option value="">Yuklanmoqda...</option>';
                streetSelect.disabled = true;

                try {
                    const response = await fetch(`/api/streets?district_id=${districtId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    streetSelect.innerHTML = '<option value="">Ko\'chani tanlang yoki yarating</option>';

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(street => {
                            const option = new Option(street.name, street.id);
                            streetSelect.add(option);
                        });
                    }

                    streetSelect.disabled = false;
                } catch (error) {
                    console.error('Error loading streets:', error);
                    streetSelect.innerHTML = '<option value="">Xato! Qayta urinib ko\'ring</option>';
                    streetSelect.disabled = false;
                }
            },

            // Reset selects
            resetMahallaSelect: function() {
                const mahallaSelect = document.getElementById('mahalla_id');
                if (mahallaSelect) {
                    mahallaSelect.innerHTML = '<option value="">Mahallani tanlang yoki yarating</option>';
                    mahallaSelect.disabled = false;
                }
            },

            resetStreetSelect: function() {
                const streetSelect = document.getElementById('street_id');
                if (streetSelect) {
                    streetSelect.innerHTML = '<option value="">Ko\'chani tanlang yoki yarating</option>';
                    streetSelect.disabled = false;
                }
            },

            // Modal functions
            showAddMahallaModal: function() {
                const districtSelect = document.getElementById('district_id');
                const districtId = districtSelect ? districtSelect.value : null;

                if (!districtId) {
                    alert('Avval tumanni tanlang!');
                    return;
                }

                document.getElementById('newMahallaDistrictId').value = districtId;
                document.getElementById('addMahallaModal').classList.remove('hidden');
                document.getElementById('newMahallaName').focus();
            },

            showAddStreetModal: function() {
                const districtSelect = document.getElementById('district_id');
                const districtId = districtSelect ? districtSelect.value : null;

                if (!districtId) {
                    alert('Avval tumanni tanlang!');
                    return;
                }

                document.getElementById('newStreetDistrictId').value = districtId;
                document.getElementById('addStreetModal').classList.remove('hidden');
                document.getElementById('newStreetName').focus();
            },

            hideModal: function(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.classList.add('hidden');
                    if (modalId === 'addMahallaModal') {
                        document.getElementById('newMahallaName').value = '';
                    } else if (modalId === 'addStreetModal') {
                        document.getElementById('newStreetName').value = '';
                    }
                }
            },

            // Add new mahalla
            addNewMahalla: async function() {
                const districtId = document.getElementById('newMahallaDistrictId').value;
                const name = document.getElementById('newMahallaName').value.trim();

                if (!name) {
                    alert('Mahalla nomini kiriting!');
                    return;
                }

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch('/api/mahallas', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: name,
                            district_id: districtId
                        })
                    });

                    const result = await response.json();

                    if (result.success && result.mahalla) {
                        const select = document.getElementById('mahalla_id');
                        const option = new Option(result.mahalla.name, result.mahalla.id, true, true);
                        select.add(option);
                        this.hideModal('addMahallaModal');
                        alert('Mahalla muvaffaqiyatli qo\'shildi!');
                    } else {
                        alert('Xato: ' + (result.message || 'Noma\'lum xato'));
                    }
                } catch (error) {
                    alert('Xato yuz berdi: ' + error.message);
                }
            },

            // Add new street
            addNewStreet: async function() {
                const districtId = document.getElementById('newStreetDistrictId').value;
                const name = document.getElementById('newStreetName').value.trim();

                if (!name) {
                    alert('Ko\'cha nomini kiriting!');
                    return;
                }

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch('/api/streets', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            name: name,
                            district_id: districtId
                        })
                    });

                    const result = await response.json();

                    if (result.success && result.street) {
                        const select = document.getElementById('street_id');
                        const option = new Option(result.street.name, result.street.id, true, true);
                        select.add(option);
                        this.hideModal('addStreetModal');
                        alert('Ko\'cha muvaffaqiyatli qo\'shildi!');
                    } else {
                        alert('Xato: ' + (result.message || 'Noma\'lum xato'));
                    }
                } catch (error) {
                    alert('Xato yuz berdi: ' + error.message);
                }
            },

            // Image field management
            addImageField: function() {
                const container = document.getElementById('imageFieldsContainer');
                const fieldId = 'image_field_' + this.imageFieldIndex;

                const fieldHtml = `
                    <div id="${fieldId}" class="image-field border border-gray-200 rounded-lg p-3 bg-gray-50">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-sm font-medium text-gray-700">Rasm ${this.imageFieldIndex + 1}</label>
                            <button type="button" onclick="PropertyForm.removeImageField('${fieldId}')"
                                    class="text-red-500 hover:text-red-700 text-sm">× O'chirish</button>
                        </div>
                        <input type="file" name="images[]" accept="image/*" onchange="PropertyForm.handleImageChange(this, '${fieldId}')"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <div class="mt-2 text-xs text-gray-500">JPEG, PNG, JPG formatida, maksimal 2MB</div>
                        <div id="preview_${fieldId}" class="mt-3 hidden">
                            <img id="img_${fieldId}" src="" alt="Preview" class="w-24 h-24 object-cover rounded border">
                            <div id="info_${fieldId}" class="text-xs text-gray-500 mt-1"></div>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', fieldHtml);
                this.imageFieldIndex++;
                this.updateImageCounter();
            },

            removeImageField: function(fieldId) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.remove();
                    this.updateImageCounter();
                    this.renumberImageFields();
                }
            },

            renumberImageFields: function() {
                const fields = document.querySelectorAll('.image-field');
                fields.forEach((field, index) => {
                    const label = field.querySelector('label');
                    if (label) {
                        label.textContent = `Rasm ${index + 1}`;
                    }
                });
            },

            handleImageChange: function(input, fieldId) {
                const file = input.files[0];
                const preview = document.getElementById(`preview_${fieldId}`);
                const img = document.getElementById(`img_${fieldId}`);
                const info = document.getElementById(`info_${fieldId}`);

                if (file) {
                    if (file.size > 2048 * 1024) {
                        alert('Fayl hajmi 2MB dan oshmasligi kerak!');
                        input.value = '';
                        preview.classList.add('hidden');
                        return;
                    }

                    if (!file.type.startsWith('image/')) {
                        alert('Faqat rasm fayllarini tanlang!');
                        input.value = '';
                        preview.classList.add('hidden');
                        return;
                    }

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

                this.updateImageCounter();
            },

            updateImageCounter: function() {
                const counter = document.getElementById('imageCounter');
                const fields = document.querySelectorAll('.image-field input[type="file"]');
                let filledFields = 0;

                fields.forEach(input => {
                    if (input.files.length > 0) {
                        filledFields++;
                    }
                });

                if (counter) {
                    counter.textContent = `Jami rasmlar: ${filledFields}`;
                    const counterContainer = counter.parentElement;

                    if (filledFields >= 4) {
                        counterContainer.className = 'mt-3 p-2 bg-green-50 border border-green-200 rounded text-sm';
                        counter.className = 'font-medium text-green-700';
                    } else {
                        counterContainer.className = 'mt-3 p-2 bg-red-50 border border-red-200 rounded text-sm';
                        counter.className = 'font-medium text-red-700';
                    }
                }

                this.totalImages = filledFields;
            },

            // Tenant fields toggle
            toggleTenantFields: function(checkbox) {
                const tenantFields = document.getElementById('tenantFields');
                if (tenantFields) {
                    if (checkbox.checked) {
                        tenantFields.classList.remove('hidden');
                    } else {
                        tenantFields.classList.add('hidden');
                    }
                }
            }
        };

        // STIR/PINFL validation functions (outside of PropertyForm object)
        async function validateOwnerStirPinfl() {
            const stirPinfl = document.getElementById('owner_stir_pinfl').value.trim();
            const resultDiv = document.getElementById('owner_validation_result');
            const nameInput = document.getElementById('owner_name');

            if (!stirPinfl) {
                resultDiv.innerHTML = '<span class="text-red-600">STIR/PINFL ni kiriting</span>';
                return;
            }

            resultDiv.innerHTML = '<span class="text-blue-600">Tekshirilmoqda...</span>';

            try {
                const response = await fetch('/api/validate-stir-pinfl', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ stir_pinfl: stirPinfl })
                });

                const result = await response.json();

                if (result.success) {
                    resultDiv.innerHTML = `<span class="text-green-600">✓ Tasdiqlandi: ${result.name}</span>`;
                    if (result.name && nameInput) {
                        nameInput.value = result.name;
                    }
                } else {
                    resultDiv.innerHTML = `<span class="text-red-600">✗ Xato: ${result.error}</span>`;
                }
            } catch (error) {
                resultDiv.innerHTML = '<span class="text-red-600">✗ Serverda xato yuz berdi</span>';
            }
        }

        async function validateTenantStirPinfl() {
            const stirPinfl = document.getElementById('tenant_stir_pinfl').value.trim();
            const resultDiv = document.getElementById('tenant_validation_result');
            const nameInput = document.getElementById('tenant_name');

            if (!stirPinfl) {
                resultDiv.innerHTML = '<span class="text-red-600">STIR/PINFL ni kiriting</span>';
                return;
            }

            resultDiv.innerHTML = '<span class="text-blue-600">Tekshirilmoqda...</span>';

            try {
                const response = await fetch('/api/validate-stir-pinfl', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ stir_pinfl: stirPinfl })
                });

                const result = await response.json();

                if (result.success) {
                    resultDiv.innerHTML = `<span class="text-green-600">✓ Tasdiqlandi: ${result.name}</span>`;
                    if (result.name && nameInput) {
                        nameInput.value = result.name;
                    }
                } else {
                    resultDiv.innerHTML = `<span class="text-red-600">✗ Xato: ${result.error}</span>`;
                }
            } catch (error) {
                resultDiv.innerHTML = '<span class="text-red-600">✗ Serverda xato yuz berdi</span>';
            }
        }

        // Form validation
        function validateForm() {
            let isValid = true;

            // Required fields validation
            const requiredFields = document.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            // Images validation
            if (PropertyForm.totalImages < 4) {
                alert('Kamida 4 ta rasm yuklang!');
                isValid = false;
            }

            // Adjacent facilities validation
            const adjacentFacilities = document.querySelectorAll('input[name="adjacent_facilities[]"]:checked');
            if (adjacentFacilities.length === 0) {
                alert('Tutash hududdagi qurilmalardan kamida bittasini tanlang!');
                isValid = false;
            }

            return isValid;
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Global DOM loaded, setting up...');

            // Wait a bit for other scripts to load
            setTimeout(function() {
                PropertyForm.init();
            }, 100);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                PropertyForm.hideModal('addMahallaModal');
                PropertyForm.hideModal('addStreetModal');
            }
        });
    </script>
@endsection
