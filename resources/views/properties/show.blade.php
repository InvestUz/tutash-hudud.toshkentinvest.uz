@extends('layouts.app')

@section('title', 'Mulk ma\'lumotlari - ' . $property->object_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">{{ $property->object_name }}</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('properties.index') }}"
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Orqaga
                    </a>
                    @if(auth()->user()->hasPermission('edit'))
                        <a href="{{ route('properties.edit', $property) }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Tahrirlash
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Bino egasi ma'lumotlari -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Bino egasi ma'lumotlari</h3>
                        <div class="bg-gray-50 p-4 rounded-lg space-y-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">F.I.SH:</span>
                                    <p class="text-gray-900">{{ $property->owner_name }}</p>
                                </div>
                             
                                <div>
                                    <span class="text-sm font-medium text-gray-500">STIR/PINFL:</span>
                                    <p class="text-gray-900">{{ $property->owner_stir_pinfl }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Kadastr raqami:</span>
                                    <p class="text-gray-900">{{ $property->building_cadastr_number }}</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-sm font-medium text-gray-500">Faoliyat turi:</span>
                                    <p class="text-gray-900">{{ $property->activity_type }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ijarachi ma'lumotlari -->
                    @if($property->has_tenant)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ijarachi ma'lumotlari</h3>
                            <div class="bg-blue-50 p-4 rounded-lg space-y-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">F.I.SH:</span>
                                        <p class="text-gray-900">{{ $property->tenant_name }}</p>
                                    </div>
                                  
                                    @if($property->tenant_stir_pinfl)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">STIR/PINFL:</span>
                                            <p class="text-gray-900">{{ $property->tenant_stir_pinfl }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ijarachi ma'lumotlari</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-500 text-center">Ijarachi mavjud emas</p>
                            </div>
                        </div>
                    @endif

                    <!-- Manzil ma'lumotlari -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Manzil ma'lumotlari</h3>
                        <div class="bg-green-50 p-4 rounded-lg space-y-3">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Tuman:</span>
                                    <p class="text-gray-900">{{ $property->district->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Mahalla:</span>
                                    <p class="text-gray-900">{{ $property->mahalla->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Ko'cha:</span>
                                    <p class="text-gray-900">{{ $property->street->name }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Uy raqami:</span>
                                    <p class="text-gray-900">{{ $property->house_number }}</p>
                                </div>
                                @if($property->additional_info)
                                    <div class="col-span-2">
                                        <span class="text-sm font-medium text-gray-500">Qo'shimcha ma'lumot:</span>
                                        <p class="text-gray-900">{{ $property->additional_info }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tutash hudud ma'lumotlari -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tutash hudud ma'lumotlari</h3>
                        <div class="bg-purple-50 p-4 rounded-lg space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Faoliyat turi:</span>
                                <p class="text-gray-900">{{ $property->adjacent_activity_type }}</p>
                            </div>
                              <div>
                                <span class="text-sm font-medium text-gray-500">Maydoni:</span>
                                <p class="text-gray-900">{{ $property->adjacent_activity_land }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Qurilmalar:</span>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @php
                                        $facilityLabels = [
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
                                    @foreach($property->adjacent_facilities as $facility)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $facilityLabels[$facility] ?? $facility }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Map -->
                    @if($property->latitude && $property->longitude)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Joylashuv</h3>
                            <div class="bg-white border rounded-lg p-4">
                                <div id="propertyMap" style="height: 300px;" class="rounded-lg"></div>
                                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-500">Kenglik:</span>
                                        <span class="text-gray-900">{{ $property->latitude }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-500">Uzunlik:</span>
                                        <span class="text-gray-900">{{ $property->longitude }}</span>
                                    </div>
                                </div>
                                @if($property->google_maps_url)
                                    <div class="mt-4">
                                        <a href="{{ $property->google_maps_url }}" target="_blank"
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Google Maps'da ochish
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Images -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Rasmlar</h3>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($property->images as $image)
                                <div class="aspect-w-1 aspect-h-1">
                                    <img src="{{ Storage::url($image) }}"
                                         alt="Mulk rasmi"
                                         class="w-full h-32 object-cover rounded-lg border cursor-pointer hover:opacity-75"
                                         onclick="openImageModal('{{ Storage::url($image) }}')">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Act File -->
                    @if($property->act_file)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Akt fayli</h3>
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
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Qo'shimcha ma'lumotlar</h3>
                        <div class="bg-gray-50 p-4 rounded-lg space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Yaratilgan sana:</span>
                                <span class="text-gray-900">{{ $property->created_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Oxirgi yangilanish:</span>
                                <span class="text-gray-900">{{ $property->updated_at->format('d.m.Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Yaratuvchi:</span>
                                <span class="text-gray-900">{{ $property->creator->name }}</span>
                            </div>
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
</script>
@endsection
