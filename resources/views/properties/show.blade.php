@extends('layouts.app')

@section('title', 'Mulk ma\'lumotlari - ' . $property->owner_name)

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <a href="{{ route('properties.index') }}" class="mr-4 text-gray-400 hover:text-gray-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $property->owner_name }}</h1>
                                <p class="text-sm text-gray-500">Kadastr: {{ $property->building_cadastr_number }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <!-- Status badges -->
                            @if($property->owner_verified)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Tasdiqlangan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    Tasdiqlanmagan
                                </span>
                            @endif

                            @if($property->has_tenant)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                    </svg>
                                    Ijarachi mavjud
                                </span>
                            @endif

                            <!-- Action buttons -->
                            {{-- @can('update', $property)
                                <a href="{{ route('properties.edit', $property) }}"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Tahrirlash
                                </a>
                            @endcan --}}

                            @if($property->google_maps_url)
                                <a href="{{ $property->google_maps_url }}" target="_blank"
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Xaritada ko'rish
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Basic Information -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Asosiy ma'lumotlar
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Korxona nomi / F.I.SH</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $property->owner_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">STIR/PINFL</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->owner_stir_pinfl }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kadastr raqami</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $property->building_cadastr_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Faoliyat turi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->activity_type }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Foydalanish maqsadi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->usage_purpose }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ruxsatnoma</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->has_permit === 'Xa' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $property->has_permit === 'Xa' ? 'Mavjud' : 'Mavjud emas' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Manzil ma'lumotlari
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tuman</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->district->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Mahalla</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->mahalla->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ko'cha</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->street->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Uy raqami</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->house_number }}</dd>
                                </div>
                                @if($property->latitude && $property->longitude)
                                    <div class="sm:col-span-2">
                                        <dt class="text-sm font-medium text-gray-500">GPS koordinatalar</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-mono">
                                            {{ number_format($property->latitude, 6) }}, {{ number_format($property->longitude, 6) }}
                                        </dd>
                                    </div>
                                @endif
                            </dl>

                            @if($property->additional_info)
                                <div class="mt-6">
                                    <dt class="text-sm font-medium text-gray-500">Qo'shimcha ma'lumot</dt>
                                    <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $property->additional_info }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Aloqa ma'lumotlari
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Rahbar F.I.SH</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->director_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Telefon raqami</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="tel:{{ $property->phone_number }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $property->phone_number }}
                                        </a>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Tenant Information -->
                    @if($property->has_tenant)
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Ijarachi ma'lumotlari
                                </h2>
                            </div>
                            <div class="px-6 py-4">
                                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                    @if($property->tenant_name)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Ijarachi nomi / F.I.SH</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $property->tenant_name }}</dd>
                                        </div>
                                    @endif
                                    @if($property->tenant_stir_pinfl)
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">STIR/PINFL</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ $property->tenant_stir_pinfl }}
                                                @if($property->tenant_verified)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Tasdiqlangan
                                                    </span>
                                                @endif
                                            </dd>
                                        </div>
                                    @endif
                                    @if($property->tenant_activity_type)
                                        <div class="sm:col-span-2">
                                            <dt class="text-sm font-medium text-gray-500">Faoliyat turi</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ $property->tenant_activity_type }}</dd>
                                        </div>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    @endif

                    <!-- Area and Measurements -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                O'lchamlari va maydon
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <!-- Calculated Area Section -->
                            @if($property->calculated_land_area)
                                <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                                    <h3 class="text-sm font-medium text-green-800 mb-3">Avtomatik hisoblangan maydon</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-2xl font-bold text-green-900">{{ number_format($property->calculated_land_area, 2) }} m²</span>
                                            <p class="text-xs text-green-700">
                                                {{ $property->area_calculation_method ?? 'Hisoblash usuli noma\'lum' }}
                                            </p>
                                        </div>
                                        @if($property->calculated_perimeter)
                                            <div>
                                                <span class="text-lg font-semibold text-green-800">{{ number_format($property->calculated_perimeter, 2) }} m</span>
                                                <p class="text-xs text-green-700">Perimetr</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if($property->area_length && $property->area_width)
                                        <div class="mt-3 text-xs text-green-700">
                                            Uzunlik: {{ $property->area_length }}m × Kenglik: {{ $property->area_width }}m
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2 lg:grid-cols-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Umumiy maydon</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ number_format($property->total_area, 2) }} m²</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fasad uzunligi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($property->building_facade_length, 2) }} m</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Yozgi terassa</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($property->summer_terrace_sides, 2) }} m</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Yo'lgacha masofa</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($property->distance_to_roadway, 2) }} m</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Trotuargacha masofa</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($property->distance_to_sidewalk, 2) }} m</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terassada qurilmalar</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $property->terrace_buildings_available === 'Xa' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $property->terrace_buildings_available === 'Xa' ? 'Mavjud' : 'Mavjud emas' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Adjacent Area Information -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H7m2 0v-6m10 6v-6M7 7h10M7 11h10"/>
                                </svg>
                                Tutash hudud ma'lumotlari
                            </h2>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Faoliyat turi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->adjacent_activity_type }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Maydon</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $property->adjacent_activity_land }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Qurilmalar</dt>
                                    <dd class="mt-1">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($property->formatted_adjacent_facilities as $facility)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $facility }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Images Gallery -->
                    @if($property->images_exist->count() > 0)
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h2 class="text-lg font-medium text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Rasmlar ({{ $property->images_exist->count() }})
                                </h2>
                            </div>
                            <div class="px-6 py-4">
                                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                                    @foreach($property->images_exist as $index => $image)
                                        <div class="relative group">
<a target="_blank" href="{{ asset('storage/' . $image) }}">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                 alt="Property Image {{ $index + 1 }}"
                                                 class="h-24 w-full object-cover rounded-lg cursor-pointer transition-transform duration-200 group-hover:scale-105"
                                                 {{-- onclick="openModal('{{ asset('storage/' . $image) }}')" --}}
                                                 loading="lazy">
</a>
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-lg transition-all duration-200 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Quick Stats -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900">Tezkor statistika</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Umumiy maydon</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ number_format($property->total_area, 1) }} m²</span>
                                </div>
                                @if($property->calculated_land_area)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500">Hisoblangan maydon</span>
                                        <span class="text-sm font-semibold text-green-700">{{ number_format($property->calculated_land_area, 1) }} m²</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Rasmlar soni</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $property->images_exist->count() }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Holat</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $property->owner_verified ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $property->owner_verified ? 'Tasdiqlangan' : 'Kutilmoqda' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Files -->
                    @if($property->act_file_exists || $property->design_code_file_exists)
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Biriktirtilgan fayllar
                                </h3>
                            </div>
                            <div class="px-6 py-4">
                                <div class="space-y-3">
                                    @if($property->act_file_exists)
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Akt fayli</p>
                                                    <p class="text-xs text-gray-500">{{ number_format($property->act_file_size / 1024, 1) }} KB</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $property->act_file) }}"
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Ochish
                                            </a>
                                        </div>
                                    @endif

                                    @if($property->design_code_file_exists)
                                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Loyiha kodi</p>
                                                    <p class="text-xs text-gray-500">{{ number_format($property->design_code_file_size / 1024, 1) }} KB</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $property->design_code_file) }}"
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Ochish
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Map -->
                    @if($property->latitude && $property->longitude)
                        <div class="bg-white shadow rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900 flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Joylashuv
                                </h3>
                            </div>
                            <div class="px-6 py-4">
                                <div id="propertyMap" class="h-48 rounded-lg border border-gray-300 mb-3"></div>
                                <div class="text-center">
                                    <a href="{{ $property->google_maps_url }}"
                                       target="_blank"
                                       class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Google Maps da ochish
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Meta Information -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900">Tizim ma'lumotlari</h3>
                        </div>
                        <div class="px-6 py-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Yaratildi</dt>
                                    <dd class="text-sm text-gray-900">{{ $property->formatted_created_at }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Yangilandi</dt>
                                    <dd class="text-sm text-gray-900">{{ $property->formatted_updated_at }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">Yaratgan</dt>
                                    <dd class="text-sm text-gray-900">{{ $property->creator->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500">ID</dt>
                                    <dd class="text-sm text-gray-900 font-mono">#{{ $property->id }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal - Simplified -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative max-w-6xl max-h-screen w-full">
                <!-- Close button -->
                <button onclick="closeModal()"
                        class="absolute -top-10 right-0 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Image -->
                <img id="modalImage"
                     src=""
                     alt="Property Image"
                     class="max-h-screen max-w-full mx-auto block rounded-lg shadow-2xl">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Image Modal Functions
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const loader = document.getElementById('imageLoader');

            if (modal && modalImage && loader) {
                // Show modal and loader
                modal.classList.remove('hidden');
                loader.style.display = 'flex';
                modalImage.style.display = 'none';
                document.body.style.overflow = 'hidden';

                // Add fade in animation
                setTimeout(() => {
                    modal.style.opacity = '1';
                }, 10);

                // Load image
                modalImage.onload = function() {
                    loader.style.display = 'none';
                    modalImage.style.display = 'block';
                };

                modalImage.onerror = function() {
                    loader.style.display = 'none';
                    modalImage.alt = 'Rasm yuklanmadi';
                    modalImage.style.display = 'block';
                };

                modalImage.src = imageSrc;
            }
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            if (modal) {
                modal.style.opacity = '0';
                setTimeout(() => {
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';

                    // Reset image
                    if (modalImage) {
                        modalImage.src = '';
                        modalImage.onload = null;
                        modalImage.onerror = null;
                    }
                }, 300);
            }
        }

        // Initialize modal functionality when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                }
            });

            // Close modal when clicking outside the image
            const modal = document.getElementById('imageModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeImageModal();
                    }
                });
            }

            // Add click event to all gallery images
            const galleryImages = document.querySelectorAll('.gallery-image');
            galleryImages.forEach(function(img) {
                img.addEventListener('click', function() {
                    const imageSrc = this.src;
                    openImageModal(imageSrc);
                });
            });

            // Initialize Map if coordinates exist
            @if($property->latitude && $property->longitude)
                if (typeof L !== 'undefined') {
                    const lat = {{ $property->latitude }};
                    const lng = {{ $property->longitude }};

                    const map = L.map('propertyMap', {
                        center: [lat, lng],
                        zoom: 16,
                        zoomControl: false,
                        scrollWheelZoom: false,
                        doubleClickZoom: false,
                        dragging: false
                    });

                    // Add satellite tiles
                    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        maxZoom: 19,
                        attribution: '© Esri, Maxar, Earthstar Geographics'
                    }).addTo(map);

                    // Add marker
                    const marker = L.marker([lat, lng]).addTo(map);

                    // Add popup with property info
                    marker.bindPopup(`
                        <div class="text-sm">
                            <strong>{{ $property->owner_name }}</strong><br>
                            {{ $property->district->name }}, {{ $property->mahalla->name }}<br>
                            {{ $property->street->name }}, {{ $property->house_number }}
                        </div>
                    `).openPopup();

                    // Add polygon if available
                    @if($property->polygon_coordinates)
                        const polygonCoords = @json($property->polygon_coordinates);
                        if (polygonCoords && polygonCoords.length > 0) {
                            // Convert coordinates to Leaflet format [lat, lng]
                            const leafletCoords = polygonCoords[0].map(coord => [coord[1], coord[0]]);

                            L.polygon(leafletCoords, {
                                color: '#3B82F6',
                                fillColor: '#3B82F6',
                                fillOpacity: 0.2,
                                weight: 2
                            }).addTo(map);
                        }
                    @endif

                    // Make map clickable to open in Google Maps
                    map.on('click', function() {
                        window.open('{{ $property->google_maps_url }}', '_blank');
                    });
                }
            @endif
        });
    </script>
@endsection
