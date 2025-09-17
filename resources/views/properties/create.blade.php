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
                        <input type="text" id="building_cadastr_number" name="building_cadastr_number"
                            value="{{ old('building_cadastr_number') }}" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('building_cadastr_number') border-red-500 @enderror"
                            placeholder="10:08:07:02:03:0174" maxlength="100">
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
                                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('district_id') border-red-500 @enderror" required>
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
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('mahalla_id') border-red-500 @enderror" required>
                                <option value="">Mahallani tanlang yoki yarating</option>
                                @if(old('district_id'))
                                    @foreach($mahallas ?? [] as $mahalla)
                                        <option value="{{ $mahalla->id }}" {{ old('mahalla_id') == $mahalla->id ? 'selected' : '' }}>
                                            {{ $mahalla->name }}
                                        </option>
                                    @endforeach
                                @endif
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
                                    class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('street_id') border-red-500 @enderror" required>
                                <option value="">Ko'chani tanlang yoki yarating</option>
                                @if(old('district_id'))
                                    @foreach($streets ?? [] as $street)
                                        <option value="{{ $street->id }}" {{ old('street_id') == $street->id ? 'selected' : '' }}>
                                            {{ $street->name }}
                                        </option>
                                    @endforeach
                                @endif
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

            <!-- Area Calculation Section -->
            <div class="mb-8 bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Tutash hudud yuzasi (Uzunlik x Kenglik)
                </h3>

                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Oson hisoblash!</strong> Faqat uzunlik va kenglikni kiriting - yuzasi avtomatik hisoblanadi.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Input method selection -->
                <div class="mb-6">
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="input_method" value="rectangle"
                                {{ old('input_method', 'rectangle') == 'rectangle' ? 'checked' : '' }}
                                onchange="switchInputMethod('rectangle')"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Uzunlik x Kenglik</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="input_method" value="coordinates"
                                {{ old('input_method') == 'coordinates' ? 'checked' : '' }}
                                onchange="switchInputMethod('coordinates')"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">GPS koordinatalari orqali</span>
                        </label>
                    </div>
                </div>

                <!-- Rectangle method -->
                <div id="rectangle_method" class="mb-6 {{ old('input_method', 'rectangle') != 'rectangle' ? 'hidden' : '' }}">
                    <h4 class="text-md font-medium text-gray-800 mb-3">Hududning o'lchamlari:</h4>

                    <!-- Visual diagram -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <div class="flex justify-center" style="padding: 20px">
                            <div class="relative">
                                <!-- Rectangle representation -->
                                <div class="w-32 h-20 border-2 border-blue-500 bg-blue-100 relative">
                                    <!-- Length arrow (top) -->
                                    <div class="absolute -top-6 left-0 right-0 flex items-center justify-center">
                                        <div class="flex items-center">
                                            <div class="w-2 h-0.5 bg-red-500"></div>
                                            <div class="flex-1 border-t border-red-500 mx-1"></div>
                                            <div class="text-xs text-red-600 mx-2" id="length_display">Uzunlik</div>
                                            <div class="flex-1 border-t border-red-500 mx-1"></div>
                                            <div class="w-2 h-0.5 bg-red-500"></div>
                                        </div>
                                    </div>

                                    <!-- Width arrow (right) -->
                                    <div class="absolute -right-8 top-0 bottom-0 flex items-center justify-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-0.5 h-2 bg-green-500"></div>
                                            <div class="flex-1 border-l border-green-500 my-1"></div>
                                            <div class="text-xs text-green-600 mx-2 transform -rotate-90 whitespace-nowrap" id="width_display">Kenglik</div>
                                            <div class="flex-1 border-l border-green-500 my-1"></div>
                                            <div class="w-0.5 h-2 bg-green-500"></div>
                                        </div>
                                    </div>

                                    <!-- Center text -->
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="text-xs text-blue-800 font-medium" id="area_display">Hudud</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Uzunlik (m) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="area_length" id="area_length"
                                value="{{ old('area_length', '0') }}"
                                oninput="calculateFromRectangle()"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masalan: 15.0">
                            <p class="text-xs text-gray-500 mt-1">Hududning eng uzun tomoni</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kenglik (m) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="area_width" id="area_width"
                                value="{{ old('area_width', '0') }}"
                                oninput="calculateFromRectangle()"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masalan: 8.5">
                            <p class="text-xs text-gray-500 mt-1">Hududning keng tomoni</p>
                        </div>
                    </div>
                </div>

                <!-- GPS coordinates method -->
                <div id="coordinates_method" class="mb-6 {{ old('input_method') != 'coordinates' ? 'hidden' : '' }}">
                    <h4 class="text-md font-medium text-gray-800 mb-3">GPS koordinatalarini kiriting (kamida 3 ta nuqta):</h4>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div id="coordinate_inputs">
                            @if(old('input_method') == 'coordinates' && old('coordinate_lat'))
                                @foreach(old('coordinate_lat') as $index => $lat)
                                    <div id="coord_{{ $index }}" class="flex items-center space-x-2 mb-2">
                                        <div class="w-8 text-sm font-medium text-gray-600">P{{ $index + 1 }}:</div>
                                        <div class="flex-1">
                                            <input type="number" step="0.0000001" name="coordinate_lat[]"
                                                value="{{ $lat }}"
                                                placeholder="41.3111 (Kenglik)"
                                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm coordinate-lat"
                                                oninput="calculateFromCoordinates()">
                                        </div>
                                        <div class="flex-1">
                                            <input type="number" step="0.0000001" name="coordinate_lng[]"
                                                value="{{ old('coordinate_lng')[$index] ?? '' }}"
                                                placeholder="69.2797 (Uzunlik)"
                                                class="w-full border border-gray-300 rounded px-2 py-1 text-sm coordinate-lng"
                                                oninput="calculateFromCoordinates()">
                                        </div>
                                        <button type="button" onclick="removeCoordinateInput('coord_{{ $index }}')"
                                            class="text-red-500 hover:text-red-700 text-sm px-2">×</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" onclick="addCoordinateInput()"
                            class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 text-sm rounded">
                            + Nuqta qo'shish
                        </button>
                    </div>
                </div>

                <!-- Calculation result -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-medium text-green-800">Avtomatik hisoblash natijasi</h4>
                        <div class="text-sm text-green-600">
                            <span id="calculation_method">To'rtburchak formulasi</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Result -->
                        <div>
                            <div class="text-sm text-green-700 mb-1">Yuzasi</div>
                            <div id="calculated_area" class="text-3xl font-bold text-green-900 mb-2">0.00 m²</div>
                            <div class="text-sm text-green-600">
                                Perimetr: <span id="calculated_perimeter">0.00 m</span>
                            </div>
                        </div>

                        <!-- Formula -->
                        <div class="bg-white rounded-lg p-4 border">
                            <div class="text-sm font-medium text-gray-800 mb-2">Hisoblash jarayoni:</div>
                            <div id="calculation_formula" class="text-xs text-gray-600 space-y-1">
                                Uzunlik va kenglikni kiriting...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden inputs for database -->
                <input type="hidden" name="calculated_land_area" id="calculated_land_area" value="{{ old('calculated_land_area') }}">
                <input type="hidden" name="area_calculation_method" id="area_calculation_method" value="{{ old('area_calculation_method') }}">

                <!-- Manual area input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Umumiy maydon (tekshirish uchun) (m²) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" name="total_area" id="total_area"
                        value="{{ old('total_area') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('total_area') border-red-500 @enderror"
                        placeholder="Yuqorida hisoblangan qiymat bilan taqqoslang">
                    <p class="text-xs text-gray-500 mt-1">Bu maydon yuqoridagi avtomatik hisoblash bilan taqqoslash uchun</p>
                    @error('total_area')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Other measurements section -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Qolgan o'lchamlari
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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
                            onchange="toggleTenantFields(this)"
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
                        <button type="button" onclick="getCurrentLocation()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex-1">
                            Joylashuvni aniqlash
                        </button>
                        <button type="button" onclick="toggleDrawingMode()" id="drawButton"
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
                            <button type="button" onclick="addImageField()"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-3 py-1 rounded">
                                + Qo'shish
                            </button>
                        </div>

                        <!-- Image Upload Fields Container -->
                        <div id="imageFieldsContainer" class="space-y-3">
                            <!-- Image fields will be populated by JavaScript, considering old values -->
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

                            @if(session('temp_files.act_file'))
                                <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center text-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-sm">Fayl saqlandi: {{ session('temp_files.act_file.original_name') }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ number_format(session('temp_files.act_file.size') / 1024, 1) }} KB</div>
                                    <input type="hidden" name="temp_act_file" value="{{ session('temp_files.act_file.path') }}">
                                </div>
                            @endif

                            <input type="file" name="act_file" accept=".pdf,.doc,.docx"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('act_file') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX formatida, maksimal 10MB @if(session('temp_files.act_file'))(Yangi fayl tanlash ixtiyoriy)@endif</p>
                            @error('act_file')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Design Code File -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Loyiha kodi fayli (ixtiyoriy)
                            </label>

                            @if(session('temp_files.design_code_file'))
                                <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center text-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-sm">Fayl saqlandi: {{ session('temp_files.design_code_file.original_name') }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ number_format(session('temp_files.design_code_file.size') / 1024, 1) }} KB</div>
                                    <input type="hidden" name="temp_design_code_file" value="{{ session('temp_files.design_code_file.path') }}">
                                </div>
                            @endif

                            <input type="file" name="design_code_file" accept=".pdf,.doc,.docx,.dwg,.zip"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('design_code_file') border-red-500 @enderror">
                            <p class="text-sm text-gray-500 mt-1">PDF, DOC, DOCX, DWG, ZIP formatida, maksimal 10MB @if(session('temp_files.design_code_file'))(Yangi fayl tanlash ixtiyoriy)@endif</p>
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
                    <h3 class="text-lg font-medium text-gray-900">Yangi mahalla qo'shish</h3>
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
                    <h3 class="text-lg font-medium text-gray-900">Yangi ko'cha qo'shish</h3>
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
// Fixed JavaScript - All errors corrected
(function() {
    'use strict';

    // Global variables
    let propertyMap = null;
    let propertyMarker = null;
    let drawnItems = null;
    let drawControl = null;
    let isDrawingMode = false;
    let imageFieldIndex = 0;
    let totalImages = 0;
    let coordinateIndex = 0;

    // Tashkent bounds
    const TASHKENT_BOUNDS = {
        center: [41.2995, 69.2401],
        bounds: [
            [40.9, 68.8], // Southwest
            [41.6, 69.8]  // Northeast
        ]
    };

    // PropertyForm object
    window.PropertyForm = {
        // Initialize everything
        init: function() {
            console.log('PropertyForm initializing...');

            // Initialize image fields from old values or create default ones
            this.initializeImageFields();
            this.updateImageCounter();

            // Initialize map
            this.initializeMap();

            // Load locations if district is already selected
            const districtSelect = document.getElementById('district_id');
            if (districtSelect && districtSelect.value) {
                this.onDistrictChange(districtSelect);
            }

            // Initialize area calculation based on old values
            this.initializeAreaCalculation();

            console.log('PropertyForm initialized successfully');
        },

        // Initialize image fields preserving old values and temp files
        initializeImageFields: function() {
            const container = document.getElementById('imageFieldsContainer');
            if (!container) return;

            // Check for temporary files from session
            const tempFiles = @json(session('temp_files', []));

            if (tempFiles && tempFiles.images && tempFiles.images.length > 0) {
                // Display temporary files
                tempFiles.images.forEach((tempFile, index) => {
                    this.addImageFieldWithTempFile(tempFile, index);
                });

                // Add remaining fields to reach minimum of 4
                const remainingFields = Math.max(0, 4 - tempFiles.images.length);
                for (let i = 0; i < remainingFields; i++) {
                    this.addImageField();
                }
            } else {
                // Add default 4 fields for new form
                for (let i = 0; i < 4; i++) {
                    this.addImageField();
                }
            }
        },

        // Add image field with temporary file data
        addImageFieldWithTempFile: function(tempFile, index) {
            const container = document.getElementById('imageFieldsContainer');
            if (!container) return;

            const fieldId = 'image_field_' + imageFieldIndex;

            const fieldHtml = `
                <div id="${fieldId}" class="image-field border border-green-200 rounded-lg p-3 bg-green-50">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-medium text-gray-700">Rasm ${imageFieldIndex + 1}</label>
                        <button type="button" onclick="PropertyForm.removeImageField('${fieldId}')"
                                class="text-red-500 hover:text-red-700 text-sm">× O'chirish</button>
                    </div>
                    <div class="bg-white border border-green-300 rounded px-3 py-2">
                        <div class="flex items-center text-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Fayl saqlandi: ${tempFile.original_name}</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">${(tempFile.size / 1024).toFixed(1)} KB</div>
                    </div>
                    <input type="hidden" name="temp_image_paths[]" value="${tempFile.path}">
                    <div class="mt-3">
                        <img src="/storage/${tempFile.path}" alt="Preview" class="w-24 h-24 object-cover rounded border">
                    </div>
                    <div class="mt-2">
                        <input type="file" name="images[]" accept="image/*" onchange="PropertyForm.handleImageChange(this, '${fieldId}')"
                               class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <div class="text-xs text-gray-500 mt-1">Yangi fayl tanlash uchun (ixtiyoriy)</div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', fieldHtml);
            imageFieldIndex++;
            totalImages++; // Count temp files as uploaded
            this.updateImageCounter();
        },

        // Initialize area calculation based on old values
        initializeAreaCalculation: function() {
            const inputMethod = document.querySelector('input[name="input_method"]:checked');
            if (inputMethod) {
                window.switchInputMethod(inputMethod.value);
            }

            // Restore coordinate inputs if coordinates method was selected
            if (inputMethod && inputMethod.value === 'coordinates') {
                const coordinateInputs = document.getElementById('coordinate_inputs');
                if (coordinateInputs && coordinateInputs.children.length === 0) {
                    // Add default coordinate inputs if none exist
                    for (let i = 0; i < 4; i++) {
                        window.addCoordinateInput();
                    }
                }
                window.calculateFromCoordinates();
            } else {
                window.calculateFromRectangle();
            }
        },

        // Initialize map
        initializeMap: function() {
            if (typeof L === 'undefined') {
                console.error('Leaflet is not loaded');
                return;
            }

            propertyMap = L.map('propertyMap').setView(TASHKENT_BOUNDS.center, 12);

            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri, Maxar, Earthstar Geographics'
            });

            satelliteLayer.addTo(propertyMap);

            // Initialize drawn items layer
            drawnItems = new L.FeatureGroup();
            propertyMap.addLayer(drawnItems);

            // Map click event for marker placement
            const self = this;
            propertyMap.on('click', function(e) {
                if (!isDrawingMode) {
                    self.placeMarker(e.latlng.lat, e.latlng.lng);
                }
            });

            // Restore marker if coordinates exist
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            if (latInput && lngInput && latInput.value && lngInput.value) {
                this.placeMarker(parseFloat(latInput.value), parseFloat(lngInput.value));
                propertyMap.setView([parseFloat(latInput.value), parseFloat(lngInput.value)], 16);
            }

            console.log('Map initialized successfully');
        },

        // Place marker on map
        placeMarker: function(lat, lng) {
            // Remove existing marker
            if (propertyMarker) {
                propertyMap.removeLayer(propertyMarker);
            }

            // Add new marker
            propertyMarker = L.marker([lat, lng]).addTo(propertyMap);

            // Update coordinate inputs
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            if (latInput) latInput.value = lat.toFixed(8);
            if (lngInput) lngInput.value = lng.toFixed(8);
        },

        // Toggle drawing mode
        toggleDrawingMode: function() {
            const button = document.getElementById('drawButton');
            if (!button) return;

            if (!isDrawingMode) {
                if (typeof L !== 'undefined' && L.Control && L.Control.Draw) {
                    drawControl = new L.Control.Draw({
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
                            featureGroup: drawnItems,
                            remove: true
                        }
                    });
                    propertyMap.addControl(drawControl);

                    // Handle drawn polygons
                    propertyMap.on(L.Draw.Event.CREATED, function(event) {
                        const layer = event.layer;
                        drawnItems.addLayer(layer);

                        // Save polygon coordinates
                        const coordinates = layer.getLatLngs()[0].map(latlng => [latlng.lng, latlng.lat]);
                        document.getElementById('polygon_coordinates').value = JSON.stringify([coordinates]);
                    });
                }
                button.textContent = 'Chizishni to\'xtatish';
                button.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex-1';
                isDrawingMode = true;
            } else {
                if (drawControl) {
                    propertyMap.removeControl(drawControl);
                }
                button.textContent = 'Poligon chizish';
                button.className = 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex-1';
                isDrawingMode = false;
            }
        },

        // Get current location
        getCurrentLocation: function() {
            if (!propertyMap) {
                alert('Xarita hali yuklanmagan!');
                return;
            }

            if (navigator.geolocation) {
                const self = this;
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        self.placeMarker(lat, lng);
                        propertyMap.setView([lat, lng], 16);
                        alert('Joylashuv muvaffaqiyatli aniqlandi!');
                    },
                    function(error) {
                        alert('Joylashuvni aniqlab bo\'lmadi');
                    }
                );
            } else {
                alert('Brauzer geolokatsiyani qo\'llab-quvvatlamaydi!');
            }
        },

        // District change handler
        onDistrictChange: function(selectElement) {
            const districtId = typeof selectElement === 'object' ? selectElement.value : selectElement;

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
            if (!mahallaSelect) return;

            const oldValue = mahallaSelect.value; // Preserve selected value
            mahallaSelect.innerHTML = '<option value="">Yuklanmoqda...</option>';
            mahallaSelect.disabled = true;

            try {
                const response = await fetch(`/api/mahallas?district_id=${districtId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                mahallaSelect.innerHTML = '<option value="">Mahallani tanlang yoki yarating</option>';

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(mahalla => {
                        const option = new Option(mahalla.name, mahalla.id);
                        if (mahalla.id == oldValue) {
                            option.selected = true;
                        }
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
            if (!streetSelect) return;

            const oldValue = streetSelect.value; // Preserve selected value
            streetSelect.innerHTML = '<option value="">Yuklanmoqda...</option>';
            streetSelect.disabled = true;

            try {
                const response = await fetch(`/api/streets?district_id=${districtId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                streetSelect.innerHTML = '<option value="">Ko\'chani tanlang yoki yarating</option>';

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(street => {
                        const option = new Option(street.name, street.id);
                        if (street.id == oldValue) {
                            option.selected = true;
                        }
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

            const districtIdInput = document.getElementById('newMahallaDistrictId');
            const modal = document.getElementById('addMahallaModal');
            const nameInput = document.getElementById('newMahallaName');

            if (districtIdInput) districtIdInput.value = districtId;
            if (modal) modal.classList.remove('hidden');
            if (nameInput) nameInput.focus();
        },

        showAddStreetModal: function() {
            const districtSelect = document.getElementById('district_id');
            const districtId = districtSelect ? districtSelect.value : null;

            if (!districtId) {
                alert('Avval tumanni tanlang!');
                return;
            }

            const districtIdInput = document.getElementById('newStreetDistrictId');
            const modal = document.getElementById('addStreetModal');
            const nameInput = document.getElementById('newStreetName');

            if (districtIdInput) districtIdInput.value = districtId;
            if (modal) modal.classList.remove('hidden');
            if (nameInput) nameInput.focus();
        },

        hideModal: function(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                if (modalId === 'addMahallaModal') {
                    const nameInput = document.getElementById('newMahallaName');
                    if (nameInput) nameInput.value = '';
                } else if (modalId === 'addStreetModal') {
                    const nameInput = document.getElementById('newStreetName');
                    if (nameInput) nameInput.value = '';
                }
            }
        },

        // Add new mahalla
        addNewMahalla: async function() {
            const districtIdInput = document.getElementById('newMahallaDistrictId');
            const nameInput = document.getElementById('newMahallaName');

            if (!districtIdInput || !nameInput) return;

            const districtId = districtIdInput.value;
            const name = nameInput.value.trim();

            if (!name) {
                alert('Mahalla nomini kiriting!');
                return;
            }

            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!tokenElement) {
                alert('CSRF token topilmadi!');
                return;
            }

            try {
                const response = await fetch('/api/mahallas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': tokenElement.getAttribute('content'),
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
                    if (select) {
                        const option = new Option(result.mahalla.name, result.mahalla.id, true, true);
                        select.add(option);
                    }
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
            const districtIdInput = document.getElementById('newStreetDistrictId');
            const nameInput = document.getElementById('newStreetName');

            if (!districtIdInput || !nameInput) return;

            const districtId = districtIdInput.value;
            const name = nameInput.value.trim();

            if (!name) {
                alert('Ko\'cha nomini kiriting!');
                return;
            }

            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!tokenElement) {
                alert('CSRF token topilmadi!');
                return;
            }

            try {
                const response = await fetch('/api/streets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': tokenElement.getAttribute('content'),
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
                    if (select) {
                        const option = new Option(result.street.name, result.street.id, true, true);
                        select.add(option);
                    }
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
            if (!container) return;

            const fieldId = 'image_field_' + imageFieldIndex;

            const fieldHtml = `
                <div id="${fieldId}" class="image-field border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-medium text-gray-700">Rasm ${imageFieldIndex + 1}</label>
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
            imageFieldIndex++;
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
                    if (preview) preview.classList.add('hidden');
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    alert('Faqat rasm fayllarini tanlang!');
                    input.value = '';
                    if (preview) preview.classList.add('hidden');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (img) img.src = e.target.result;
                    if (info) info.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
                    if (preview) preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);

                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
            } else {
                if (preview) preview.classList.add('hidden');
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

            totalImages = filledFields;
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

    // Area calculation functions
    window.switchInputMethod = function(method) {
        const rectangleMethod = document.getElementById('rectangle_method');
        const coordinatesMethod = document.getElementById('coordinates_method');

        if (rectangleMethod) {
            rectangleMethod.classList.toggle('hidden', method !== 'rectangle');
        }
        if (coordinatesMethod) {
            coordinatesMethod.classList.toggle('hidden', method !== 'coordinates');
        }

        if (method === 'coordinates') {
            const coordinateInputs = document.getElementById('coordinate_inputs');
            if (coordinateInputs && coordinateInputs.children.length === 0) {
                for (let i = 0; i < 4; i++) window.addCoordinateInput();
            }
            window.calculateFromCoordinates();
        } else if (method === 'rectangle') {
            window.calculateFromRectangle();
        }
    };

    window.addCoordinateInput = function() {
        const container = document.getElementById('coordinate_inputs');
        if (!container) return;

        const inputId = 'coord_' + coordinateIndex;

        const inputHtml = `
            <div id="${inputId}" class="flex items-center space-x-2 mb-2">
                <div class="w-8 text-sm font-medium text-gray-600">P${coordinateIndex + 1}:</div>
                <div class="flex-1">
                    <input type="number" step="0.0000001" name="coordinate_lat[]"
                        placeholder="41.3111 (Kenglik)"
                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm coordinate-lat"
                        oninput="calculateFromCoordinates()">
                </div>
                <div class="flex-1">
                    <input type="number" step="0.0000001" name="coordinate_lng[]"
                        placeholder="69.2797 (Uzunlik)"
                        class="w-full border border-gray-300 rounded px-2 py-1 text-sm coordinate-lng"
                        oninput="calculateFromCoordinates()">
                </div>
                <button type="button" onclick="removeCoordinateInput('${inputId}')"
                    class="text-red-500 hover:text-red-700 text-sm px-2">×</button>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', inputHtml);
        coordinateIndex++;
    };

    window.removeCoordinateInput = function(inputId) {
        const element = document.getElementById(inputId);
        if (element) {
            element.remove();
            window.calculateFromCoordinates();
        }
    };

    window.calculateFromRectangle = function() {
        const lengthInput = document.getElementById('area_length');
        const widthInput = document.getElementById('area_width');

        const length = lengthInput ? parseFloat(lengthInput.value) || 0 : 0;
        const width = widthInput ? parseFloat(widthInput.value) || 0 : 0;

        // Update visual display
        const lengthDisplay = document.getElementById('length_display');
        const widthDisplay = document.getElementById('width_display');
        const areaDisplay = document.getElementById('area_display');

        if (lengthDisplay) lengthDisplay.textContent = length > 0 ? `${length}m` : 'Uzunlik';
        if (widthDisplay) widthDisplay.textContent = width > 0 ? `${width}m` : 'Kenglik';

        if (!length || !width) {
            window.displayResult(0, 0, 'Uzunlik va kenglikni kiriting...', 'To\'rtburchak formulasi');
            if (areaDisplay) areaDisplay.textContent = 'Hudud';
            return;
        }

        // Calculate area and perimeter
        const area = length * width;
        const perimeter = 2 * (length + width);

        // Update visual display
        if (areaDisplay) areaDisplay.textContent = `${area.toFixed(1)}m²`;

        const formula = `
            <div><strong>To'rtburchak formulasi:</strong></div>
            <div>Uzunlik = ${length} m</div>
            <div>Kenglik = ${width} m</div>
            <div><strong>Yuzasi = ${length} × ${width} = ${area.toFixed(2)} m²</strong></div>
            <div><strong>Perimetr = 2 × (${length} + ${width}) = ${perimeter.toFixed(2)} m</strong></div>
        `;

        window.displayResult(area, perimeter, formula, 'To\'rtburchak formulasi');
    };

    window.calculateFromCoordinates = function() {
        const latInputs = document.querySelectorAll('.coordinate-lat');
        const lngInputs = document.querySelectorAll('.coordinate-lng');

        const coordinates = [];
        for (let i = 0; i < latInputs.length; i++) {
            const lat = parseFloat(latInputs[i].value);
            const lng = parseFloat(lngInputs[i].value);
            if (!isNaN(lat) && !isNaN(lng)) {
                coordinates.push([lng, lat]);
            }
        }

        if (coordinates.length < 3) {
            window.displayResult(0, 0, 'Kamida 3 ta GPS koordinata kiriting...', 'Shoelace formulasi');
            return;
        }

        // Local projection: convert to meters relative to first point
        const lat0 = coordinates[0][1] * Math.PI / 180;
        const lon0 = coordinates[0][0] * Math.PI / 180;
        const R = 6371000;

        const projected = coordinates.map(coord => {
            const lat = coord[1] * Math.PI / 180;
            const lon = coord[0] * Math.PI / 180;
            const x = (lon - lon0) * Math.cos((lat + lat0) / 2) * R;
            const y = (lat - lat0) * R;
            return [x, y];
        });
        projected.push(projected[0]); // Close polygon

        // Shoelace formula in meters
        let area = 0;
        for (let i = 0; i < projected.length - 1; i++) {
            const [x1, y1] = projected[i];
            const [x2, y2] = projected[i + 1];
            area += (x1 * y2 - x2 * y1);
        }
        area = Math.abs(area) / 2;

        // Perimeter (haversine)
        let perimeter = 0;
        for (let i = 0; i < coordinates.length; i++) {
            const lat1 = coordinates[i][1] * Math.PI / 180;
            const lon1 = coordinates[i][0] * Math.PI / 180;
            const lat2 = coordinates[(i + 1) % coordinates.length][1] * Math.PI / 180;
            const lon2 = coordinates[(i + 1) % coordinates.length][0] * Math.PI / 180;

            const dLat = lat2 - lat1;
            const dLon = lon2 - lon1;
            const a = Math.sin(dLat/2)**2 + Math.cos(lat1) * Math.cos(lat2) * Math.sin(dLon/2)**2;
            perimeter += 2 * R * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        }

        const formula = `
            <div><strong>Shoelace formulasi (metrlarda):</strong></div>
            <div>${coordinates.length} ta nuqta ishlatildi</div>
            <div>Yuzasi = ${area.toFixed(2)} m²</div>
        `;

        window.displayResult(area, perimeter, formula, 'Shoelace formulasi');
    };

    window.displayResult = function(area, perimeter, formula, method) {
        const calculatedArea = document.getElementById('calculated_area');
        const calculatedPerimeter = document.getElementById('calculated_perimeter');
        const calculationFormula = document.getElementById('calculation_formula');
        const calculationMethod = document.getElementById('calculation_method');

        if (calculatedArea) calculatedArea.textContent = area > 0 ? `${area.toFixed(2)} m²` : '0.00 m²';
        if (calculatedPerimeter) calculatedPerimeter.textContent = perimeter > 0 ? `${perimeter.toFixed(2)} m` : '0.00 m';
        if (calculationFormula) calculationFormula.innerHTML = formula;
        if (calculationMethod) calculationMethod.textContent = method;

        const calculatedLandArea = document.getElementById('calculated_land_area');
        const areaCalculationMethod = document.getElementById('area_calculation_method');

        if (calculatedLandArea) calculatedLandArea.value = area.toFixed(2);
        if (areaCalculationMethod) areaCalculationMethod.value = method;

        const totalAreaInput = document.getElementById('total_area');
        if (area > 0 && totalAreaInput && !totalAreaInput.value) {
            totalAreaInput.value = area.toFixed(2);
        }
    };

    // STIR/PINFL validation functions
    window.validateOwnerStirPinfl = async function() {
        const stirPinflInput = document.getElementById('owner_stir_pinfl');
        const resultDiv = document.getElementById('owner_validation_result');
        const nameInput = document.getElementById('owner_name');

        if (!stirPinflInput || !resultDiv) return;

        const stirPinfl = stirPinflInput.value.trim();

        if (!stirPinfl) {
            resultDiv.innerHTML = '<span class="text-red-600">STIR/PINFL ni kiriting</span>';
            return;
        }

        resultDiv.innerHTML = '<span class="text-blue-600">Tekshirilmoqda...</span>';

        try {
            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!tokenElement) {
                throw new Error('CSRF token topilmadi');
            }

            const response = await fetch('/api/validate-stir-pinfl', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': tokenElement.getAttribute('content')
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
    };

    window.validateTenantStirPinfl = async function() {
        const stirPinflInput = document.getElementById('tenant_stir_pinfl');
        const resultDiv = document.getElementById('tenant_validation_result');
        const nameInput = document.getElementById('tenant_name');

        if (!stirPinflInput || !resultDiv) return;

        const stirPinfl = stirPinflInput.value.trim();

        if (!stirPinfl) {
            resultDiv.innerHTML = '<span class="text-red-600">STIR/PINFL ni kiriting</span>';
            return;
        }

        resultDiv.innerHTML = '<span class="text-blue-600">Tekshirilmoqda...</span>';

        try {
            const tokenElement = document.querySelector('meta[name="csrf-token"]');
            if (!tokenElement) {
                throw new Error('CSRF token topilmadi');
            }

            const response = await fetch('/api/validate-stir-pinfl', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': tokenElement.getAttribute('content')
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
    };

    // Form validation
    window.validateForm = function() {
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
        if (totalImages < 4) {
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
    };

    // Global function wrappers for onclick handlers
    window.toggleDrawingMode = function() {
        PropertyForm.toggleDrawingMode();
    };

    window.getCurrentLocation = function() {
        PropertyForm.getCurrentLocation();
    };

    window.addImageField = function() {
        PropertyForm.addImageField();
    };

    window.toggleTenantFields = function(checkbox) {
        PropertyForm.toggleTenantFields(checkbox);
    };

    window.hideModal = function(modalId) {
        PropertyForm.hideModal(modalId);
    };

    window.addNewMahalla = function() {
        PropertyForm.addNewMahalla();
    };

    window.addNewStreet = function() {
        PropertyForm.addNewStreet();
    };

    // Cadastral number formatting
    function formatCadastralNumber() {
        const cadastralInput = document.getElementById("building_cadastr_number");
        if (!cadastralInput) return;

        cadastralInput.addEventListener("input", function (e) {
            let value = e.target.value;

            // Remove all colons first
            value = value.replace(/:/g, "");

            // Split into first 6 parts (max 5 colons)
            let parts = [];
            let rest = value;

            for (let i = 0; i < 5 && rest.length > 0; i++) {
                // Take up to 2 digits before each colon
                parts.push(rest.substring(0, 2));
                rest = rest.substring(2);
            }

            // Push whatever is left (can be long, with /, etc.)
            if (rest.length > 0) {
                parts.push(rest);
            }

            // Join with colons
            e.target.value = parts.join(":");
        });
    }

    // Initialize everything when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing...');

        // Wait for other scripts to load
        setTimeout(function() {
            try {
                // Initialize PropertyForm
                PropertyForm.init();

                // Format cadastral number
                formatCadastralNumber();

                console.log('All initialization completed successfully');
            } catch (error) {
                console.error('Initialization error:', error);
            }
        }, 100);
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            PropertyForm.hideModal('addMahallaModal');
            PropertyForm.hideModal('addStreetModal');
        }
    });

})();
</script>
@endsection
