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

    <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data"
          onsubmit="return validateForm()" class="px-6 py-4">
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
                           onchange="toggleTenantFields(this)"
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
                            onchange="loadMahallas(this.value, 'mahalla_id')"
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
                                onchange="loadStreets(this.value, 'street_id')"
                                class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Mahallani tanlang</option>
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

 <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            Rasmlar ({{ count($property->images) }} ta)
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($property->images as $image)
                                <div class="aspect-w-1 aspect-h-1">
                                    <img src="{{ Storage::url($image) }}"
                                         alt="Mulk rasmi"
                                         class="w-full h-32 object-cover rounded-lg border cursor-pointer hover:opacity-75 transition-opacity"
                                         onclick="openImageModal('{{ Storage::url($image) }}')">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Act File -->
                    @if($property->act_file)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Akt fayli
                            </h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <a href="{{ Storage::url($property->act_file) }}"
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Faylni yuklab olish
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Meta information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Qo'shimcha ma'lumotlar
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Yaratilgan sana:</span>
                                <span class="text-gray-900 font-medium">{{ $property->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Oxirgi yangilanish:</span>
                                <span class="text-gray-900">{{ $property->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Yaratuvchi:</span>
                                <span class="text-gray-900">{{ $property->creator->name }}</span>
                            </div>
                            @if($property->creator->district)
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Yaratuvchi tumani:</span>
                                    <span class="text-gray-900">{{ $property->creator->district->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium">Rasm</h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <img id="modalImage" src="" alt="Rasm" class="w-full h-auto max-h-96 object-contain mx-auto">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize map for property location
    @if($property->latitude && $property->longitude)
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('propertyMap').setView([{{ $property->latitude }}, {{ $property->longitude }}], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            L.marker([{{ $property->latitude }}, {{ $property->longitude }}])
                .addTo(map)
                .bindPopup('{{ $property->object_name }}')
                .openPopup();
        });
    @endif

    // Image modal functions
    function openImageModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    // Close modal on background click
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
</script>
@endsection

