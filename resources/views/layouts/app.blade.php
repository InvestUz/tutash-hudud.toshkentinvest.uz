<!DOCTYPE html>
<html lang="uz" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tutash Hudud Tizimi')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .uzbek-font {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Custom Tailwind Configuration */
        @layer utilities {
            .text-primary {
                color: #3561db;
            }

            .bg-primary {
                background-color: #3561db;
            }

            .border-primary {
                border-color: #3561db;
            }

            .hover\:bg-primary-light:hover {
                background-color: rgba(53, 97, 219, 0.05);
            }
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
            transition-duration: 200ms;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Map container */
        #map {
            height: 400px;
            width: 100%;
            z-index: 0;
        }

        /* Modal backdrop */
        .modal-backdrop {
            backdrop-filter: blur(2px);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Loading spinner */
        .spinner {
            border: 2px solid #f3f4f6;
            border-top: 2px solid #3561db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body class="bg-gray-50 uzbek-font">

    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-300 shadow-sm sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left side -->
                <div class="flex items-center space-x-8">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-[#3561db] bg-opacity-10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#3561db]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-lg font-bold text-gray-900">Tutash Hudud</span>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-[#3561db] bg-opacity-10 text-[#3561db]' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-home mr-2"></i>
                            Bosh sahifa
                        </a>
                        <a href="{{ route('properties.index') }}"
                            class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('properties.*') ? 'bg-[#3561db] bg-opacity-10 text-[#3561db]' : 'text-gray-700 hover:bg-gray-100' }}">
                            <i class="fas fa-building mr-2"></i>
                            Mulklar
                        </a>
                        @if (auth()->user()->hasPermission('create'))
                            <a href="{{ route('properties.create') }}"
                                class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('properties.create') ? 'bg-[#3561db] bg-opacity-10 text-[#3561db]' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Yangi mulk
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Export Button (Admin Only) -->
                        @if (auth()->user()->email === 'admin@tutashhudud.uz')
                            <button onclick="toggleExportModal()"
                                class="hidden md:flex items-center px-4 py-2 bg-[#3561db] text-white text-sm font-medium rounded-lg hover:bg-opacity-90 shadow-sm">
                                <i class="fas fa-file-export mr-2"></i>
                                Export
                            </button>
                        @endif

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                <div class="text-right hidden sm:block">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    @if (auth()->user()->district)
                                        <p class="text-xs text-gray-600">{{ auth()->user()->district->name }}</p>
                                    @endif
                                </div>
                                <div
                                    class="w-10 h-10 bg-[#3561db] bg-opacity-10 rounded-full flex items-center justify-center">
                                    <span
                                        class="text-[#3561db] text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-300 py-1 z-50"
                                style="display: none;">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-3 text-gray-500"></i>
                                    Profil
                                </a>
                                <div class="border-t border-gray-300 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        Chiqish
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="md:hidden border-t border-gray-300">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-[#3561db] bg-opacity-10 text-[#3561db]' : 'text-gray-700' }}">
                    <i class="fas fa-home mr-3"></i>
                    Bosh sahifa
                </a>
                <a href="{{ route('properties.index') }}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('properties.*') ? 'bg-[#3561db] bg-opacity-10 text-[#3561db]' : 'text-gray-700' }}">
                    <i class="fas fa-building mr-3"></i>
                    Mulklar
                </a>

    {{-- <a href="{{ route('properties.map') }}"
                    class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('properties.map') ? 'bg-[#3561db] bg-opacity-10 text-[#3561db]' : 'text-gray-700' }}">
                    <i class="fas fa-map-marked-alt mr-3"></i>
                    Xarita
                </a> --}}
                @if (auth()->user()->hasPermission('create'))
                    <a href="{{ route('properties.create') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('properties.create') ? 'bg-[#3561db] bg-opacity-10 text-[#3561db]' : 'text-gray-700' }}">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Yangi mulk
                    </a>
                @endif
            </div>
        </div>
    </nav>


    @include('partials.flash-messages')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-center md:text-left">
                    <p class="text-sm text-gray-600">© {{ date('Y') }} Tutash Hudud Tizimi. Barcha huquqlar
                        himoyalangan.</p>
                </div>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-sm text-gray-600 hover:text-[#3561db]">Yordam</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-[#3561db]">Aloqa</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-[#3561db]">Maxfiylik</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Export Modal (Admin Only) -->
    @if (auth()->check() && auth()->user()->email === 'admin@tutashhudud.uz')
        <div id="exportModal"
            class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="border-b border-gray-300 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Ma'lumotlarni eksport
                            qilish</h3>
                        <button onclick="toggleExportModal()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <form action="{{ route('properties.export') }}" method="POST" class="p-6">
                    @csrf
                    <div class="flex flex-wrap gap-3 mb-4">
                        <div class="flex-1 min-w-[140px]">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Dan:</label>
                            <input type="date" name="date_from"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#3561db] focus:border-[#3561db] transition-all duration-200">
                        </div>
                        <div class="flex-1 min-w-[140px]">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Gacha:</label>
                            <input type="date" name="date_to"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#3561db] focus:border-[#3561db] transition-all duration-200">
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 mb-4">
                        <p class="text-xs text-gray-600">
                            <svg class="w-4 h-4 inline mr-1 text-[#3561db]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Agar sana tanlanmasa, barcha ma'lumotlar eksport qilinadi
                        </p>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" onclick="toggleExportModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Bekor qilish
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-[#3561db] text-white text-sm font-medium rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-sm">
                            Eksport qilish
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Toggle Export Modal
            function toggleExportModal() {
                const modal = document.getElementById('exportModal');
                if (modal) {
                    modal.classList.toggle('hidden');
                }
            }

            // Close modal on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('exportModal');
                    if (modal && !modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                    }
                }
            });

            // Close modal on outside click
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('exportModal');
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            this.classList.add('hidden');
                        }
                    });
                }
            });
        </script>
    @endif

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>



    <script>
        // =============== GLOBAL VARIABLES AND CONFIG ===============

        // CSRF Token setup for AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Setup AJAX headers
            if (window.fetch) {
                const originalFetch = window.fetch;
                window.fetch = function(url, options = {}) {
                    options.headers = {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        ...options.headers
                    };
                    return originalFetch(url, options);
                };
            }
        });

        // Global map variables
        let map = null;
        let marker = null;

        // Toshkent shahrining koordinatalari va chegaralari
        const TASHKENT_CONFIG = {
            center: {
                lat: 41.2995,
                lng: 69.2401
            },
            bounds: {
                north: 41.4,
                south: 41.2,
                east: 69.4,
                west: 69.1
            }
        };

        // =============== MAP FUNCTIONS ===============

        // Toshkent chegaralarini tekshirish
        function isWithinTashkent(lat, lng) {
            return lat >= TASHKENT_CONFIG.bounds.south &&
                lat <= TASHKENT_CONFIG.bounds.north &&
                lng >= TASHKENT_CONFIG.bounds.west &&
                lng <= TASHKENT_CONFIG.bounds.east;
        }

        // Xaritani initialize qilish
        function initMap(containerId = 'map', lat = null, lng = null) {
            console.log('Initializing map...', {
                containerId,
                lat,
                lng
            });

            // Eski xaritani olib tashlash
            if (map) {
                map.remove();
                map = null;
                marker = null;
            }

            // Default koordinatalar
            const defaultLat = lat || TASHKENT_CONFIG.center.lat;
            const defaultLng = lng || TASHKENT_CONFIG.center.lng;
            const zoomLevel = (lat && lng) ? 16 : 11;

            try {
                // Map container mavjudligini tekshirish
                const mapContainer = document.getElementById(containerId);
                if (!mapContainer) {
                    console.error('Map container not found:', containerId);
                    return null;
                }

                console.log('Creating map at:', {
                    defaultLat,
                    defaultLng,
                    zoomLevel
                });

                // Yangi xarita yaratish
                map = L.map(containerId, {
                    center: [defaultLat, defaultLng],
                    zoom: zoomLevel,
                    zoomControl: true,
                    scrollWheelZoom: true,
                    doubleClickZoom: true,
                    maxZoom: 18,
                    minZoom: 9
                });

                // Tile layer qo'shish
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 18
                }).addTo(map);

                console.log('Map created successfully');

                // Agar aniq koordinata berilgan bo'lsa, marker qo'yish
                if (lat && lng && isWithinTashkent(lat, lng)) {
                    addMarker(lat, lng);
                }

                // Click event qo'shish
                map.on('click', function(e) {
                    const clickedLat = e.latlng.lat;
                    const clickedLng = e.latlng.lng;

                    console.log('Map clicked at:', clickedLat, clickedLng);

                    if (isWithinTashkent(clickedLat, clickedLng)) {
                        addMarker(clickedLat, clickedLng);
                        updateCoordinateInputs(clickedLat, clickedLng);
                    } else {
                        alert('Iltimos, Toshkent shahri chegaralarida joy tanlang!');
                    }
                });

                // Map ready event
                map.whenReady(function() {
                    console.log('Map is ready');
                    setTimeout(() => {
                        map.invalidateSize();
                    }, 100);
                });

                // Expose to global scope
                window.map = map;

            } catch (error) {
                console.error('Map initialization error:', error);
                alert('Xarita yuklanishda xatolik yuz berdi. Sahifani yangilab ko\'ring.');
            }

            return map;
        }

        // Marker qo'shish funksiyasi
        function addMarker(lat, lng) {
            console.log('Adding marker at:', lat, lng);

            if (!map) {
                console.error('Map is not initialized');
                return;
            }

            try {
                // Eski markerni olib tashlash
                if (marker) {
                    map.removeLayer(marker);
                    marker = null;
                }

                // Yangi marker yaratish
                marker = L.marker([lat, lng], {
                    draggable: true,
                    title: 'Tanlangan joylashuv'
                }).addTo(map);

                console.log('Marker added successfully');

                // Popup qo'shish
                marker.bindPopup(`
            <div style="text-align: center; font-family: Arial, sans-serif;">
                <strong>Tanlangan joylashuv</strong><br>
                <small>Kenglik: ${lat.toFixed(6)}<br>
                Uzunlik: ${lng.toFixed(6)}</small>
            </div>
        `).openPopup();

                // Drag event
                marker.on('dragend', function(e) {
                    const position = e.target.getLatLng();
                    const newLat = position.lat;
                    const newLng = position.lng;

                    console.log('Marker dragged to:', newLat, newLng);

                    if (isWithinTashkent(newLat, newLng)) {
                        updateCoordinateInputs(newLat, newLng);
                        // Update popup
                        marker.getPopup().setContent(`
                    <div style="text-align: center; font-family: Arial, sans-serif;">
                        <strong>Tanlangan joylashuv</strong><br>
                        <small>Kenglik: ${newLat.toFixed(6)}<br>
                        Uzunlik: ${newLng.toFixed(6)}</small>
                    </div>
                `);
                    } else {
                        alert('Iltimos, Toshkent shahri chegaralarida joy tanlang!');
                        // Markerni eski joyiga qaytarish
                        marker.setLatLng([lat, lng]);
                    }
                });

                // Expose to global scope
                window.marker = marker;

            } catch (error) {
                console.error('Error adding marker:', error);
                alert('Marker qo\'yishda xatolik yuz berdi.');
            }
        }

        // Koordinata inputlarini yangilash
        function updateCoordinateInputs(lat, lng) {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            console.log('Updating coordinate inputs:', lat, lng);

            if (latInput) {
                latInput.value = lat.toFixed(8);
                latInput.classList.remove('border-red-500');
                latInput.classList.add('border-green-500');
            }
            if (lngInput) {
                lngInput.value = lng.toFixed(8);
                lngInput.classList.remove('border-red-500');
                lngInput.classList.add('border-green-500');
            }
        }

        // Coordinate inputlarini sozlash
        function setupCoordinateInputs() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            if (latInput && lngInput) {
                [latInput, lngInput].forEach(input => {
                    input.addEventListener('change', function() {
                        const lat = parseFloat(latInput.value);
                        const lng = parseFloat(lngInput.value);

                        console.log('Manual coordinate input:', lat, lng);

                        if (!isNaN(lat) && !isNaN(lng)) {
                            if (isWithinTashkent(lat, lng)) {
                                if (map) {
                                    map.setView([lat, lng], 16);
                                    addMarker(lat, lng);
                                }
                            } else {
                                alert('Kiritilgan koordinatalar Toshkent shahri chegaralarida emas!');
                                latInput.value = '';
                                lngInput.value = '';
                            }
                        }
                    });

                    input.addEventListener('input', function() {
                        if (this.value.trim()) {
                            this.classList.remove('border-red-500');
                        }
                    });
                });
            }
        }

        // Joriy joylashuvni aniqlash - GLOBAL FUNCTION
        function getCurrentLocation() {
            console.log('Getting current location...');

            if (!navigator.geolocation) {
                alert('Brauzeringiz geolokatsiyani qo\'llab-quvvatlamaydi!');
                return;
            }

            if (!map) {
                console.error('Map is not initialized!');
                alert('Xarita hali yuklanmagan. Sahifani yangilab ko\'ring.');
                return;
            }

            const button = event ? event.target : null;
            const originalText = button ? button.textContent : '';

            if (button) {
                button.textContent = 'Aniqlanmoqda...';
                button.disabled = true;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy;

                    console.log(`Location found: ${lat}, ${lng}, Accuracy: ${accuracy}m`);

                    if (isWithinTashkent(lat, lng)) {
                        console.log('Location is within Tashkent bounds, adding marker...');

                        // Xaritani joylashuvga yo'naltirish
                        map.setView([lat, lng], 16);

                        // Marker qo'yish
                        addMarker(lat, lng);

                        // Input maydonlarini yangilash
                        updateCoordinateInputs(lat, lng);

                        // Success notification
                        showNotification(
                            `Joylashuv aniqlandi! (${Math.round(accuracy)}m aniqlik)`,
                            'success'
                        );

                    } else {
                        console.log('Location is outside Tashkent bounds');
                        alert(
                            'Siz Toshkent shahri tashqarisida turibsiz. Iltimos, xaritadan Toshkent shahridagi joyni tanlang.'
                        );
                        map.setView([TASHKENT_CONFIG.center.lat, TASHKENT_CONFIG.center.lng], 11);
                    }
                },
                function(error) {
                    console.error('Geolocation error:', error);

                    let errorMessage = 'Lokatsiya aniqlanmadi: ';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage += 'Lokatsiya ruxsati berilmagan.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage += 'Lokatsiya xizmati mavjud emas.';
                            break;
                        case error.TIMEOUT:
                            errorMessage += 'Vaqt tugadi.';
                            break;
                        default:
                            errorMessage += 'Noma\'lum xatolik.';
                    }

                    alert(errorMessage + ' Xaritadan qo\'lda joy tanlang.');
                }, {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 60000
                }
            ).finally(() => {
                if (button) {
                    button.textContent = originalText;
                    button.disabled = false;
                }
            });
        }

        // =============== LOCATION MANAGEMENT ===============

        function loadMahallas(districtId, targetSelect, selectedValue = null) {
            const select = document.getElementById(targetSelect);
            if (!select) return;

            select.innerHTML = '<option value="">Yuklanmoqda...</option>';
            select.disabled = true;

            if (!districtId) {
                select.innerHTML = '<option value="">Mahallani tanlang</option>';
                select.disabled = false;
                return;
            }

            fetch(`/api/mahallas?district_id=${districtId}`)
                .then(response => response.json())
                .then(data => {
                    select.innerHTML = '<option value="">Mahallani tanlang</option>';
                    data.forEach(mahalla => {
                        const option = document.createElement('option');
                        option.value = mahalla.id;
                        option.textContent = mahalla.name;
                        if (selectedValue && mahalla.id == selectedValue) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                    select.disabled = false;

                    // Clear streets
                    const streetSelect = document.getElementById('street_id');
                    if (streetSelect) {
                        streetSelect.innerHTML = '<option value="">Ko\'chani tanlang</option>';
                    }
                })
                .catch(error => {
                    console.error('Mahallalar yuklanmadi:', error);
                    select.innerHTML = '<option value="">Xatolik yuz berdi</option>';
                    select.disabled = false;
                });
        }

        function loadStreets(mahallaId, targetSelect, selectedValue = null) {
            const select = document.getElementById(targetSelect);
            if (!select) return;

            select.innerHTML = '<option value="">Yuklanmoqda...</option>';
            select.disabled = true;

            if (!mahallaId) {
                select.innerHTML = '<option value="">Ko\'chani tanlang</option>';
                select.disabled = false;
                return;
            }

            fetch(`/api/streets?mahalla_id=${mahallaId}`)
                .then(response => response.json())
                .then(data => {
                    select.innerHTML = '<option value="">Ko\'chani tanlang</option>';
                    data.forEach(street => {
                        const option = document.createElement('option');
                        option.value = street.id;
                        option.textContent = street.name;
                        if (selectedValue && street.id == selectedValue) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                    select.disabled = false;
                })
                .catch(error => {
                    console.error('Ko\'chalar yuklanmadi:', error);
                    select.innerHTML = '<option value="">Xatolik yuz berdi</option>';
                    select.disabled = false;
                });


        }

        // =============== MODAL FUNCTIONS ===============

        function showAddMahallaModal(districtId) {
            if (!districtId) {
                alert('Avval tumanni tanlang!');
                return;
            }

            const modal = document.getElementById('addMahallaModal');
            if (modal) {
                document.getElementById('newMahallaDistrictId').value = districtId;
                document.getElementById('newMahallaName').value = '';
                modal.classList.remove('hidden');

                setTimeout(() => {
                    document.getElementById('newMahallaName').focus();
                }, 100);
            }
        }

        function showAddStreetModal(mahallaId) {
            if (!mahallaId) {
                alert('Avval mahallani tanlang!');
                return;
            }

            const modal = document.getElementById('addStreetModal');
            if (modal) {
                document.getElementById('newStreetMahallaId').value = mahallaId;
                document.getElementById('newStreetName').value = '';
                modal.classList.remove('hidden');

                setTimeout(() => {
                    document.getElementById('newStreetName').focus();
                }, 100);
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
            const name = document.getElementById('newMahallaName').value.trim();

            if (!name) {
                alert('Mahalla nomini kiriting!');
                return;
            }

            const button = event.target;
            button.disabled = true;
            button.textContent = 'Qo\'shilmoqda...';

            fetch('/api/mahallas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        district_id: districtId,
                        name: name
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        hideModal('addMahallaModal');
                        loadMahallas(districtId, 'mahalla_id', data.mahalla.id);
                        showNotification('Mahalla muvaffaqiyatli qo\'shildi!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Xatolik:', error);
                    alert('Mahalla qo\'shishda xatolik yuz berdi!');
                })
                .finally(() => {
                    button.disabled = false;
                    button.textContent = 'Qo\'shish';
                });
        }

        function addNewStreet() {
            const mahallaId = document.getElementById('newStreetMahallaId').value;
            const name = document.getElementById('newStreetName').value.trim();

            if (!name) {
                alert('Ko\'cha nomini kiriting!');
                return;
            }

            const button = event.target;
            button.disabled = true;
            button.textContent = 'Qo\'shilmoqda...';

            fetch('/api/streets', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        mahalla_id: mahallaId,
                        name: name
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        hideModal('addStreetModal');
                        loadStreets(mahallaId, 'street_id', data.street.id);
                        showNotification('Ko\'cha muvaffaqiyatli qo\'shildi!', 'success');
                    }
                })
                .catch(error => {
                    console.error('Xatolik:', error);
                    alert('Ko\'cha qo\'shishda xatolik yuz berdi!');
                })
                .finally(() => {
                    button.disabled = false;
                    button.textContent = 'Qo\'shish';
                });
        }

        // =============== UTILITY FUNCTIONS ===============

        // Show notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;

            notification.innerHTML = `
        <div class="flex items-center">
            ${type === 'success' ?
                '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>' :
                type === 'error' ?
                '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>' :
                '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
            }
            <span>${message}</span>
        </div>
    `;

            document.body.appendChild(notification);

            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Image preview functionality
        function previewImages(input) {
            const preview = document.getElementById('imagePreview');
            if (!preview) return;

            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                    <img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg border">
                    <button type="button" onclick="removeImage(${index})"
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs">×</button>
                `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        }

        function removeImage(index) {
            const input = document.getElementById('images');
            if (!input) return;

            const dt = new DataTransfer();

            Array.from(input.files).forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });

            input.files = dt.files;
            previewImages(input);
        }

        // Form validation
        function validateForm() {
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

            const images = document.getElementById('images');
            if (images && images.files.length > 0 && images.files.length < 4) {
                alert('Kamida 4 ta rasm yuklang!');
                isValid = false;
            }

            return isValid;
        }

        // =============== GLOBAL INITIALIZATION ===============

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Global DOM loaded, setting up...');

            // Setup coordinate inputs if they exist
            setupCoordinateInputs();

            // Close modals with ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    ['addMahallaModal', 'addStreetModal'].forEach(modalId => {
                        hideModal(modalId);
                    });
                }
            });

            // Close modals when clicking outside
            ['addMahallaModal', 'addStreetModal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            hideModal(modalId);
                        }
                    });
                }
            });
        });

        // Resize map when window resizes
        window.addEventListener('resize', function() {
            if (map) {
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);
            }
        });



        // =============== DEBUG FUNCTIONS ===============

        function debugMapState() {
            console.log('=== Map Debug Info ===');
            console.log('Map object:', map);
            console.log('Marker object:', marker);
            console.log('Map container:', document.getElementById('map'));
            console.log('Latitude input:', document.getElementById('latitude'));
            console.log('Longitude input:', document.getElementById('longitude'));

            if (map) {
                console.log('Map center:', map.getCenter());
                console.log('Map zoom:', map.getZoom());
            }

            console.log('TASHKENT_CONFIG:', TASHKENT_CONFIG);
            console.log('======================');
        }

        function testAddMarker() {
            if (!map) {
                console.error('Map not initialized');
                return;
            }

            const testLat = 41.3103153;
            const testLng = 69.2484859;

            console.log(`Testing marker at: ${testLat}, ${testLng}`);

            if (isWithinTashkent(testLat, testLng)) {
                addMarker(testLat, testLng);
                updateCoordinateInputs(testLat, testLng);
                map.setView([testLat, testLng], 16);
                console.log('Test marker added successfully');
            } else {
                console.error('Test coordinates are outside Tashkent bounds');
            }
        }

        // Expose to global scope for debugging
        window.debugMapState = debugMapState;
        window.testAddMarker = testAddMarker;
        window.TASHKENT_CONFIG = TASHKENT_CONFIG;
        window.isWithinTashkent = isWithinTashkent;
        window.addMarker = addMarker;
        window.updateCoordinateInputs = updateCoordinateInputs;
    </script>
    @yield('scripts')
</body>

</html>
