@extends('layouts.app')

@section('title', 'Mulkni tahrirlash - ' . $property->object_name)

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center">
            <a href="{{ route('properties.show', $property) }}" class="mr-4 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="text-xl font-semibold text-gray-900">Mulkni tahrirlash: {{ $property->object_name }}</h2>
        </div>
    </div>

    <form method="POST" action="{{ route('properties.update', $property) }}" enctype="multipart/form-data"
          onsubmit="return validateEditForm()" class="px-6 py-4">
        @csrf
        @method('PUT')

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
                    <input type="text" name="owner_name" value="{{ old('owner_name', $property->owner_name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('owner_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

               
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        STIR/PINFL <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="owner_stir_pinfl" value="{{ old('owner_stir_pinfl', $property->owner_stir_pinfl) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('owner_stir_pinfl')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bino kadastr raqami <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="building_cadastr_number" value="{{ old('building_cadastr_number', $property->building_cadastr_number) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('building_cadastr_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Obyekt nomi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="object_name" value="{{ old('object_name', $property->object_name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('object_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Faoliyat turi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="activity_type" value="{{ old('activity_type', $property->activity_type) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
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
                    <input type="checkbox" name="has_tenant" value="1"
                           {{ old('has_tenant', $property->has_tenant) ? 'checked' : '' }}
                           onchange="toggleTenantFields(this)"
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Ijarachi mavjud</span>
                </label>
            </div>

            <div id="tenantFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('has_tenant', $property->has_tenant) ? '' : 'hidden' }}">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ijarachi F.I.SH
                    </label>
                    <input type="text" name="tenant_name" value="{{ old('tenant_name', $property->tenant_name) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('tenant_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

               
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        STIR/PINFL
                    </label>
                    <input type="text" name="tenant_stir_pinfl" value="{{ old('tenant_stir_pinfl', $property->tenant_stir_pinfl) }}"
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
                            onchange="loadMahallas(this.value, 'mahalla_id', {{ old('mahalla_id', $property->mahalla_id) }})"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tumanni tanlang</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}"
                                    {{ old('district_id', $property->district_id) == $district->id ? 'selected' : '' }}>
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
                                onchange="loadStreets(this.value, 'street_id', {{ old('street_id', $property->street_id) }})"
                                class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Mahallani tanlang</option>
                            @foreach($mahallas as $mahalla)
                                <option value="{{ $mahalla->id }}"
                                        {{ old('mahalla_id', $property->mahalla_id) == $mahalla->id ? 'selected' : '' }}>
                                    {{ $mahalla->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="showAddMahallaModal(document.getElementById('district_id').value)"
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
                            @foreach($streets as $street)
                                <option value="{{ $street->id }}"
                                        {{ old('street_id', $property->street_id) == $street->id ? 'selected' : '' }}>
                                    {{ $street->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="showAddStreetModal(document.getElementById('mahalla_id').value)"
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
                    <input type="text" name="house_number" value="{{ old('house_number', $property->house_number) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('house_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mo'ljal uchun qo'shimcha ma'lumot
                    </label>
                    <textarea name="additional_info" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">{{ old('additional_info', $property->additional_info) }}</textarea>
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
                    <input type="text" name="adjacent_activity_type" value="{{ old('adjacent_activity_type', $property->adjacent_activity_type) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('adjacent_activity_type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                        <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tutash hudud maydoni <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="adjacent_activity_land" value="{{ old('adjacent_activity_land', $property->adjacent_activity_land) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('adjacent_activity_land')
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
                            $selectedFacilities = old('adjacent_facilities', $property->adjacent_facilities ?? []);
                        @endphp
                        @foreach($facilities as $value => $label)
                            <label class="flex items-center">
                                <input type="checkbox" name="adjacent_facilities[]" value="{{ $value }}"
                                       {{ in_array($value, $selectedFacilities) ? 'checked' : '' }}
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kenglik</label>
                    <input type="number" step="any" name="latitude" id="latitude"
                           value="{{ old('latitude', $property->latitude) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('latitude')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Uzunlik</label>
                    <input type="number" step="any" name="longitude" id="longitude"
                           value="{{ old('longitude', $property->longitude) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
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
        </div>

        <!-- Fayllar -->
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
                Fayllar
            </h3>

            <!-- Current Images -->
            <div class="mb-6">
                <h4 class="text-md font-medium text-gray-700 mb-2">Mavjud rasmlar ({{ count($property->images) }} ta)</h4>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($property->images as $image)
                        <div class="relative">
                            <img src="{{ Storage::url($image) }}" alt="Mulk rasmi"
                                 class="w-full h-24 object-cover rounded border">
                        </div>
                    @endforeach
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Yangi rasmlar yuklasangiz, mavjud rasmlar o'rnibosar.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Yangi rasmlar (ixtiyoriy, kamida 4 ta bo'lsa)
                    </label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*"
                           onchange="previewImages(this)"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">JPEG, PNG, JPG formatida, har biri maksimal 2MB</p>
                    <div id="imagePreview" class="grid grid-cols-4 gap-2 mt-2"></div>
                    @error('images')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Yangi akt fayli (ixtiyoriy)
                    </label>
                    <input type="file" name="act_file" accept=".pdf,.doc,.docx"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX formatida, maksimal 10MB</p>
                    @if($property->act_file)
                        <p class="text-sm text-gray-500 mt-1">
                            Mavjud fayl:
                            <a href="{{ Storage::url($property->act_file) }}"
                               target="_blank" class="text-blue-600 hover:text-blue-800">
                                Ko'rish
                            </a>
                        </p>
                    @endif
                    @error('act_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit buttons -->
        <div class="flex justify-end space-x-4 border-t pt-6">
            <a href="{{ route('properties.show', $property) }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg">
                Bekor qilish
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                Yangilash
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
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
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
                <input type="text" id="newStreetName"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
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
    function toggleTenantFields(checkbox) {
        const tenantFields = document.getElementById('tenantFields');
        if (checkbox.checked) {
            tenantFields.classList.remove('hidden');
        } else {
            tenantFields.classList.add('hidden');
        }
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $property->latitude ?? 41.2995 }};
        const lng = {{ $property->longitude ?? 69.2401 }};
        initMap('map', lat, lng);
    });

    function validateEditForm() {
        // Check if new images are uploaded, they should be at least 4
        const images = document.getElementById('images');
        if (images && images.files.length > 0 && images.files.length < 4) {
            alert('Agar yangi rasmlar yuklasangiz, kamida 4 ta bo\'lishi kerak!');
            return false;
        }

        // Other validations...
        const requiredFields = document.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });

        return isValid;
    }
</script>
@endsection
