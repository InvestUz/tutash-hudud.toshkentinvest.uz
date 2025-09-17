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

<script>
document.getElementById("building_cadastr_number").addEventListener("input", function (e) {
    let value = e.target.value;

    // remove all colons first
    value = value.replace(/:/g, "");

    // split into first 6 parts (max 5 colons)
    let parts = [];
    let rest = value;

    for (let i = 0; i < 5 && rest.length > 0; i++) {
        // take up to 2–3 digits before each colon, or leave it free
        parts.push(rest.substring(0, 2));
        rest = rest.substring(2);
    }

    // push whatever is left (can be long, with /, etc.)
    if (rest.length > 0) {
        parts.push(rest);
    }

    // join with colons
    e.target.value = parts.join(":");
});
</script>




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

         <!-- Tutash hudud yuzasi hisoblash (Google Maps usuli) -->
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

        <!-- Input usuli tanlash -->
        <div class="mb-6">
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="input_method" value="rectangle" checked onchange="switchInputMethod('rectangle')"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Uzunlik x Kenglik</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="input_method" value="coordinates" onchange="switchInputMethod('coordinates')"
                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">GPS koordinatalari orqali</span>
                </label>

            </div>
        </div>

        <!-- Uzunlik x Kenglik usuli -->
        <div id="rectangle_method" class="mb-6">
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
                            <div class="absolute -right-8 top-0 bottom-0 flex items-center justify-center" >
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
                    <input type="number" step="0.01" name="area_length" id="area_length" value="0"
                        oninput="calculateFromRectangle()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masalan: 15.0">
                    <p class="text-xs text-gray-500 mt-1">Hududning eng uzun tomoni</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kenglik (m) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" name="area_width" id="area_width" value="0"
                        oninput="calculateFromRectangle()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masalan: 8.5">
                    <p class="text-xs text-gray-500 mt-1">Hududning keng tomoni</p>
                </div>
            </div>
        </div>

        <!-- GPS koordinatalari usuli -->
        <div id="coordinates_method" class="mb-6 hidden">
            <h4 class="text-md font-medium text-gray-800 mb-3">GPS koordinatalarini kiriting (kamida 3 ta nuqta):</h4>
            <div class="bg-gray-50 rounded-lg p-4">
                <div id="coordinate_inputs">
                    <!-- JavaScript orqali qo'shiladi -->
                </div>
                <button type="button" onclick="addCoordinateInput()"
                    class="mt-3 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 text-sm rounded">
                    + Nuqta qo'shish
                </button>
            </div>
        </div>

        <!-- Poligon chizish usuli -->
        <div id="polygon_method" class="mb-6 hidden">
            <h4 class="text-md font-medium text-gray-800 mb-3">Xaritada poligon chizing:</h4>
            <p class="text-sm text-gray-600 mb-3">Pastdagi xaritada "Poligon chizish" tugmasini bosib, hududni belgilang</p>
        </div>

        <!-- Hisoblash natijasi -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-4">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-medium text-green-800">Avtomatik hisoblash natijasi</h4>
                <div class="text-sm text-green-600">
                    <span id="calculation_method">To'rtburchak formulasi</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Natija -->
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
        <input type="hidden" name="calculated_land_area" id="calculated_land_area" value="">
        <input type="hidden" name="area_calculation_method" id="area_calculation_method" value="">

        <!-- Manual maydon kiritish -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Umumiy maydon (tekshirish uchun) (m²) <span class="text-red-500">*</span>
            </label>
            <input type="number" step="0.01" name="total_area" id="total_area" value="" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Yuqorida hisoblangan qiymat bilan taqqoslang">
            <p class="text-xs text-gray-500 mt-1">Bu maydon yuqoridagi avtomatik hisoblash bilan taqqoslash uchun</p>
        </div>
    </div>

<script>
let coordinateIndex = 0;

// Input usulini o'zgartirish
function switchInputMethod(method) {
    document.getElementById('rectangle_method').classList.toggle('hidden', method !== 'rectangle');
    document.getElementById('coordinates_method').classList.toggle('hidden', method !== 'coordinates');

    if (method === 'coordinates' && document.getElementById('coordinate_inputs').children.length === 0) {
        for (let i = 0; i < 4; i++) addCoordinateInput();
        calculateFromCoordinates();
    } else if (method === 'rectangle') {
        calculateFromRectangle();
    }
}

// GPS koordinata input qo'shish
function addCoordinateInput() {
    const container = document.getElementById('coordinate_inputs');
    const inputId = 'coord_' + coordinateIndex;

const inputHtml = `
    <div id="${inputId}" class="flex items-center space-x-2 mb-2">
        <div class="w-8 text-sm font-medium text-gray-600">P${coordinateIndex + 1}:</div>
        <div class="flex-1">
            <input type="number" step="0.0000001"
                placeholder="41.3111 (Kenglik)"
                class="w-full border border-gray-300 rounded px-2 py-1 text-sm coordinate-lat "
                oninput="calculateFromCoordinates()">
        </div>
        <div class="flex-1">
            <input type="number" step="0.0000001"
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
}

// GPS koordinata input o'chirish
function removeCoordinateInput(inputId) {
    document.getElementById(inputId).remove();
    calculateFromCoordinates();
}

// Uzunlik x Kenglik orqali hisoblash
function calculateFromRectangle() {
    const length = parseFloat(document.getElementById('area_length').value) || 0;
    const width = parseFloat(document.getElementById('area_width').value) || 0;

    // Update visual display
    document.getElementById('length_display').textContent = length > 0 ? `${length}m` : 'Uzunlik';
    document.getElementById('width_display').textContent = width > 0 ? `${width}m` : 'Kenglik';

    if (!length || !width) {
        displayResult(0, 0, 'Uzunlik va kenglikni kiriting...', 'To\'rtburchak formulasi');
        document.getElementById('area_display').textContent = 'Hudud';
        return;
    }

    // Calculate area and perimeter
    const area = length * width;
    const perimeter = 2 * (length + width);

    // Update visual display
    document.getElementById('area_display').textContent = `${area.toFixed(1)}m²`;

    const formula = `
        <div><strong>To'rtburchak formulasi:</strong></div>
        <div>Uzunlik = ${length} m</div>
        <div>Kenglik = ${width} m</div>
        <div><strong>Yuzasi = ${length} × ${width} = ${area.toFixed(2)} m²</strong></div>
        <div><strong>Perimetr = 2 × (${length} + ${width}) = ${perimeter.toFixed(2)} m</strong></div>
    `;

    displayResult(area, perimeter, formula, 'To\'rtburchak formulasi');
}

// GPS koordinatalar orqali hisoblash (Shoelace formula)
function calculateFromCoordinates() {
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
        displayResult(0, 0, 'Kamida 3 ta GPS koordinata kiriting...', 'Shoelace formulasi');
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
    projected.push(projected[0]); // polygonni yopish

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

    displayResult(area, perimeter, formula, 'Shoelace formulasi');
}

// Natijani ko'rsatish
function displayResult(area, perimeter, formula, method) {
    document.getElementById('calculated_area').textContent = area > 0 ? `${area.toFixed(2)} m²` : '0.00 m²';
    document.getElementById('calculated_perimeter').textContent = perimeter > 0 ? `${perimeter.toFixed(2)} m` : '0.00 m';
    document.getElementById('calculation_formula').innerHTML = formula;
    document.getElementById('calculation_method').textContent = method;

    document.getElementById('calculated_land_area').value = area.toFixed(2);
    document.getElementById('area_calculation_method').value = method;

    const totalAreaInput = document.getElementById('total_area');
    if (area > 0 && !totalAreaInput.value) {
        totalAreaInput.value = area.toFixed(2);
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    calculateFromRectangle();
});
</script>



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
                <div class="px-6 py-4">
                    <input type="hidden" id="newStreetDistrictId">
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
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

    <script>
        // Global variables and functions for area calculation
        let propertyMap = null;
        let propertyMarker = null;
        let drawnItems = null;
        let drawControl = null;
        let isDrawingMode = false;
        let imageFieldIndex = 0;
        let totalImages = 0;

        // Tashkent bounds
        const TASHKENT_BOUNDS = {
            center: [41.2995, 69.2401],
            bounds: [
                [40.9, 68.8], // Southwest
                [41.6, 69.8]  // Northeast
            ]
        };

        // AREA CALCULATION FUNCTIONS
        function calculateAreaPreview(method = 'brahmagupta') {
            const sideA = parseFloat(document.getElementById('side_a_b').value) || 0;
            const sideB = parseFloat(document.getElementById('side_b_c').value) || 0;
            const sideC = parseFloat(document.getElementById('side_c_d').value) || 0;
            const sideD = parseFloat(document.getElementById('side_d_a').value) || 0;

            const displayElement = document.getElementById('calculated_area_display');
            const formulaElement = document.getElementById('calculation_formula');

            if (!sideA || !sideB || !sideC || !sideD) {
                displayElement.textContent = '-- m²';
                formulaElement.innerHTML = 'Barcha to\'rt tomonni kiriting...';
                return;
            }

            // Validate quadrilateral
            const validation = validateQuadrilateralSides(sideA, sideB, sideC, sideD);
            if (!validation.valid) {
                displayElement.textContent = 'Xato!';
                displayElement.className = 'text-2xl font-bold text-red-900 mb-2';
                formulaElement.innerHTML = `<span class="text-red-600">${validation.message}</span>`;
                return;
            }

            let area, formula;

            if (method === 'rectangle') {
                // Rectangle approximation method
                const width = (sideA + sideC) / 2;
                const length = (sideB + sideD) / 2;
                area = width * length;
                formula = `<strong>To'rtburchak yaqinlashuvi:</strong><br>
                          Kenglik = (${sideA} + ${sideC}) ÷ 2 = ${width.toFixed(2)}m<br>
                          Uzunlik = (${sideB} + ${sideD}) ÷ 2 = ${length.toFixed(2)}m<br>
                          <strong>Maydon = ${width.toFixed(2)} × ${length.toFixed(2)} = ${area.toFixed(2)} m²</strong>`;
            } else {
                // Brahmagupta's formula (default)
                const s = (sideA + sideB + sideC + sideD) / 2; // semi-perimeter
                const area_squared = (s - sideA) * (s - sideB) * (s - sideC) * (s - sideD);

                if (area_squared <= 0) {
                    displayElement.textContent = 'Xato!';
                    displayElement.className = 'text-2xl font-bold text-red-900 mb-2';
                    formulaElement.innerHTML = '<span class="text-red-600">Brahmagupta formulasi ishlamadi. To\'rtburchak yaqinlashuv usulini sinab ko\'ring.</span>';
                    return;
                }

                area = Math.sqrt(area_squared);
                formula = `<strong>Brahmagupta formulasi:</strong><br>
                          Yarim perimetr: s = (${sideA} + ${sideB} + ${sideC} + ${sideD}) ÷ 2 = ${s.toFixed(2)}m<br>
                          Maydon = √[(s-a)(s-b)(s-c)(s-d)]<br>
                          = √[(${s.toFixed(2)}-${sideA})(${s.toFixed(2)}-${sideB})(${s.toFixed(2)}-${sideC})(${s.toFixed(2)}-${sideD})]<br>
                          = √[${(s-sideA).toFixed(2)} × ${(s-sideB).toFixed(2)} × ${(s-sideC).toFixed(2)} × ${(s-sideD).toFixed(2)}]<br>
                          <strong>= √${area_squared.toFixed(2)} = ${area.toFixed(2)} m²</strong>`;
            }

            displayElement.textContent = `${area.toFixed(2)} m²`;
            displayElement.className = 'text-2xl font-bold text-green-900 mb-2';
            formulaElement.innerHTML = formula;

            // Visual feedback
            displayElement.classList.add('animate-pulse');
            setTimeout(() => {
                displayElement.classList.remove('animate-pulse');
            }, 500);

            // Update total_area input if it's empty
            const totalAreaInput = document.getElementById('total_area');
            if (totalAreaInput && !totalAreaInput.value) {
                totalAreaInput.value = area.toFixed(2);
                totalAreaInput.classList.add('border-green-500');
                setTimeout(() => {
                    totalAreaInput.classList.remove('border-green-500');
                }, 2000);
            }
        }

        function validateQuadrilateralSides(a, b, c, d) {
            if (!a || !b || !c || !d) {
                return {valid: false, message: 'Barcha to\'rt tomonni kiriting'};
            }

            // Check if sides can form a quadrilateral using triangle inequality
            const sides = [a, b, c, d];
            const perimeter = a + b + c + d;

            // Each side must be less than sum of other three sides
            for (let i = 0; i < 4; i++) {
                const otherSidesSum = perimeter - sides[i];
                if (sides[i] >= otherSidesSum) {
                    const sideNames = ['A-B', 'B-C', 'C-D', 'D-A'];
                    return {
                        valid: false,
                        message: `${sideNames[i]} tomon (${sides[i]}m) boshqa uch tomonning yig'indisidan (${otherSidesSum.toFixed(2)}m) katta yoki teng bo'lmasligi kerak`
                    };
                }
            }

            return {valid: true, message: 'To\'rtburchak yaratish mumkin'};
        }

        function showBothCalculations() {
            const sideA = parseFloat(document.getElementById('side_a_b').value) || 0;
            const sideB = parseFloat(document.getElementById('side_b_c').value) || 0;
            const sideC = parseFloat(document.getElementById('side_c_d').value) || 0;
            const sideD = parseFloat(document.getElementById('side_d_a').value) || 0;

            if (!sideA || !sideB || !sideC || !sideD) {
                alert('Barcha to\'rt tomonni kiriting!');
                return;
            }

            const validation = validateQuadrilateralSides(sideA, sideB, sideC, sideD);
            if (!validation.valid) {
                alert(validation.message);
                return;
            }

            // Calculate using Brahmagupta's formula
            const s = (sideA + sideB + sideC + sideD) / 2;
            const brahmaguptaAreaSquared = (s - sideA) * (s - sideB) * (s - sideC) * (s - sideD);

            // Calculate using rectangle approximation
            const width = (sideA + sideC) / 2;
            const length = (sideB + sideD) / 2;
            const rectangleArea = width * length;

            let message = `MAYDON HISOBLASH USULLARI TAQQOSLASH\n\n`;
            message += `Kiritilgan qiymatlar:\n`;
            message += `A-B tomon: ${sideA}m\n`;
            message += `B-C tomon: ${sideB}m\n`;
            message += `C-D tomon: ${sideC}m\n`;
            message += `D-A tomon: ${sideD}m\n\n`;

            if (brahmaguptaAreaSquared > 0) {
                const brahmaguptaArea = Math.sqrt(brahmaguptaAreaSquared);
                message += `1. BRAHMAGUPTA FORMULASI:\n`;
                message += `   Yarim perimetr: s = (${sideA}+${sideB}+${sideC}+${sideD})/2 = ${s.toFixed(2)}m\n`;
                message += `   Maydon = √[(s-a)(s-b)(s-c)(s-d)]\n`;
                message += `   = √[(${s.toFixed(2)}-${sideA})(${s.toFixed(2)}-${sideB})(${s.toFixed(2)}-${sideC})(${s.toFixed(2)}-${sideD})]\n`;
                message += `   = √[${(s-sideA).toFixed(2)} × ${(s-sideB).toFixed(2)} × ${(s-sideC).toFixed(2)} × ${(s-sideD).toFixed(2)}]\n`;
                message += `   = √${brahmaguptaAreaSquared.toFixed(2)} = ${brahmaguptaArea.toFixed(2)} m²\n\n`;

                message += `2. TO'RTBURCHAK YAQINLASHUVI:\n`;
                message += `   O'rtacha kenglik = (${sideA}+${sideC})/2 = ${width.toFixed(2)}m\n`;
                message += `   O'rtacha uzunlik = (${sideB}+${sideD})/2 = ${length.toFixed(2)}m\n`;
                message += `   Maydon = ${width.toFixed(2)} × ${length.toFixed(2)} = ${rectangleArea.toFixed(2)} m²\n\n`;

                const difference = Math.abs(brahmaguptaArea - rectangleArea);
                const percentDiff = (difference / brahmaguptaArea * 100).toFixed(2);
                message += `TAQQOSLASH:\n`;
                message += `Farq: ${difference.toFixed(2)} m² (${percentDiff}%)\n`;
                message += `${brahmaguptaArea > rectangleArea ? 'Brahmagupta formulasi katta natija berdi' : 'To\'rtburchak yaqinlashuvi katta natija berdi'}`;
            } else {
                message += `1. BRAHMAGUPTA FORMULASI: Ishlamadi (manfiy qiymat)\n\n`;
                message += `2. TO'RTBURCHAK YAQINLASHUVI:\n`;
                message += `   O'rtacha kenglik = (${sideA}+${sideC})/2 = ${width.toFixed(2)}m\n`;
                message += `   O'rtacha uzunlik = (${sideB}+${sideD})/2 = ${length.toFixed(2)}m\n`;
                message += `   Maydon = ${width.toFixed(2)} × ${length.toFixed(2)} = ${rectangleArea.toFixed(2)} m²\n\n`;
                message += `Tavsiya: To'rtburchak yaqinlashuvi usulini ishlating.`;
            }

            alert(message);
        }

        // STIR/PINFL validation functions
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
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing form...');
            initializeForm();
        });

        function initializeForm() {
            // Add default image fields
            for (let i = 0; i < 4; i++) {
                addImageField();
            }
            updateImageCounter();

            // Initialize map
            initializeMap();
        }

        // Initialize map
        function initializeMap() {
            propertyMap = L.map('propertyMap').setView(TASHKENT_BOUNDS.center, 12);

            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: '© Esri, Maxar, Earthstar Geographics'
            });

            satelliteLayer.addTo(propertyMap);

            // Initialize drawn items layer
            drawnItems = new L.FeatureGroup();
            propertyMap.addLayer(drawnItems);

            // Initialize draw control
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

            // Map click event for marker placement
            propertyMap.on('click', function(e) {
                if (!isDrawingMode) {
                    placeMarker(e.latlng.lat, e.latlng.lng);
                }
            });




        }

        function placeMarker(lat, lng) {
            if (propertyMarker) {
                propertyMap.removeLayer(propertyMarker);
            }

            propertyMarker = L.marker([lat, lng]).addTo(propertyMap);

            document.getElementById('latitude').value = lat.toFixed(8);
            document.getElementById('longitude').value = lng.toFixed(8);
        }

        function toggleDrawingMode() {
            const button = document.getElementById('drawButton');

            if (!isDrawingMode) {
                propertyMap.addControl(drawControl);
                button.textContent = 'Chizishni to\'xtatish';
                button.className = 'bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex-1';
                isDrawingMode = true;
            } else {
                propertyMap.removeControl(drawControl);
                button.textContent = 'Poligon chizish';
                button.className = 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex-1';
                isDrawingMode = false;
            }
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        placeMarker(lat, lng);
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
        }

        // Image field management
        function addImageField() {
            const container = document.getElementById('imageFieldsContainer');
            const fieldId = 'image_field_' + imageFieldIndex;

            const fieldHtml = `
                <div id="${fieldId}" class="image-field border border-gray-200 rounded-lg p-3 bg-gray-50">
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-medium text-gray-700">Rasm ${imageFieldIndex + 1}</label>
                        <button type="button" onclick="removeImageField('${fieldId}')"
                                class="text-red-500 hover:text-red-700 text-sm">× O'chirish</button>
                    </div>
                    <input type="file" name="images[]" accept="image/*" onchange="handleImageChange(this, '${fieldId}')"
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
            updateImageCounter();
        }

        function removeImageField(fieldId) {
            const field = document.getElementById(fieldId);
            if (field) {
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

        // Modal functions
        function hideModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                if (modalId === 'addMahallaModal') {
                    document.getElementById('newMahallaName').value = '';
                } else if (modalId === 'addStreetModal') {
                    document.getElementById('newStreetName').value = '';
                }
            }
        }

        // Other helper functions would go here...
        // (District/Mahalla/Street loading functions, etc.)

    </script>
@endsection
