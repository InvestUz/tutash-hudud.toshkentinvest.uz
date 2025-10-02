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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    @yield('styles')
</head>

<body class="bg-gray-50 uzbek-font antialiased">

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
                                <svg class="w-5 h-5 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
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
                        @if(auth()->user()->hasPermission('create'))
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
                        @if(auth()->user()->email === 'admin@tutashhudud.uz')
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
                                    @if(auth()->user()->district)
                                    <p class="text-xs text-gray-600">{{ auth()->user()->district->name }}</p>
                                    @endif
                                </div>
                                <div class="w-10 h-10 bg-[#3561db] bg-opacity-10 rounded-full flex items-center justify-center">
                                    <span class="text-[#3561db] text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open"
                                 @click.away="open = false"
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
                @if(auth()->user()->hasPermission('create'))
                <a href="{{ route('properties.create') }}"
                   class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('properties.create') ? 'bg-[#3561db] bg-opacity-10 text-[#3561db]' : 'text-gray-700' }}">
                    <i class="fas fa-plus-circle mr-3"></i>
                    Yangi mulk
                </a>
                @endif
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center justify-between" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium">Xatolar mavjud:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-7">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-300 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-center md:text-left">
                    <p class="text-sm text-gray-600">© {{ date('Y') }} Tutash Hudud Tizimi. Barcha huquqlar himoyalangan.</p>
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
    @if(auth()->check() && auth()->user()->email === 'admin@tutashhudud.uz')
    <div id="exportModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="border-b border-gray-300 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Ma'lumotlarni eksport qilish</h3>
                    <button onclick="toggleExportModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <form action="{{ route('properties.export') }}" method="POST" class="p-6">
                @csrf
                <div class="flex flex-wrap gap-3 mb-4">
                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Dan:</label>
                        <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#3561db] focus:border-[#3561db] transition-all duration-200">
                    </div>
                    <div class="flex-1 min-w-[140px]">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Gacha:</label>
                        <input type="date" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#3561db] focus:border-[#3561db] transition-all duration-200">
                    </div>
                </div>

                <div class="bg-gray-50 border border-gray-300 rounded-lg p-3 mb-4">
                    <p class="text-xs text-gray-600">
                        <svg class="w-4 h-4 inline mr-1 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Agar sana tanlanmasa, barcha ma'lumotlar eksport qilinadi
                    </p>
                </div>

                <div class="flex space-x-3">
                    <button type="button" onclick="toggleExportModal()" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Bekor qilish
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-[#3561db] text-white text-sm font-medium rounded-lg hover:bg-opacity-90 transition-all duration-200 shadow-sm">
                        Eksport qilish
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        // CSRF Token setup for AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

            // Auto-hide flash messages after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('[role="alert"]').forEach(alert => {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                });
            }, 5000);
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

        // Check if coordinates are within Tashkent
        function isWithinTashkent(lat, lng) {
            return lat >= TASHKENT_CONFIG.bounds.south &&
                lat <= TASHKENT_CONFIG.bounds.north &&
                lng >= TASHKENT_CONFIG.bounds.west &&
                lng <= TASHKENT_CONFIG.bounds.east;
        }

        // Initialize map
        function initMap(containerId = 'map', lat = null, lng = null) {
            if (map) {
                map.remove();
                map = null;
                marker = null;
            }

            const defaultLat = lat || TASHKENT_CONFIG.center.lat;
            const defaultLng = lng || TASHKENT_CONFIG.center.lng;
            const zoomLevel = (lat && lng) ? 16 : 11;

            const mapContainer = document.getElementById(containerId);
            if (!mapContainer) return null;

            map = L.map(containerId, {
                center: [defaultLat, defaultLng],
                zoom: zoomLevel,
                zoomControl: true,
                scrollWheelZoom: true,
                doubleClickZoom: true,
                maxZoom: 18,
                minZoom: 9
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            if (lat && lng && isWithinTashkent(lat, lng)) {
                addMarker(lat, lng);
            }

            map.on('click', function(e) {
                const clickedLat = e.latlng.lat;
                const clickedLng = e.latlng.lng;

                if (isWithinTashkent(clickedLat, clickedLng)) {
                    addMarker(clickedLat, clickedLng);
                    updateCoordinateInputs(clickedLat, clickedLng);
                } else {
                    alert('Iltimos, Toshkent shahri chegaralarida joy tanlang!');
                }
            });

            map.whenReady(function() {
                setTimeout(() => map.invalidateSize(), 100);
            });

            window.map = map;
            return map;
        }

        // Add marker to map
        function addMarker(lat, lng) {
            if (!map) return;

            if (marker) {
                map.removeLayer(marker);
                marker = null;
            }

            marker = L.marker([lat, lng], {
                draggable: true,
                title: 'Tanlangan joylashuv'
            }).addTo(map);

            marker.bindPopup(`
                <div style="text-align: center;">
                    <strong>Tanlangan joylashuv</strong><br>
                    <small>Kenglik: ${lat.toFixed(6)}<br>
                    Uzunlik: ${lng.toFixed(6)}</small>
                </div>
            `).openPopup();

            marker.on('dragend', function(e) {
                const position = e.target.getLatLng();
                if (isWithinTashkent(position.lat, position.lng)) {
                    updateCoordinateInputs(position.lat, position.lng);
                } else {
                    alert('Iltimos, Toshkent shahri chegaralarida joy tanlang!');
                    marker.setLatLng([lat, lng]);
                }
            });

            window.marker = marker;
        }

        // Update coordinate inputs
        function updateCoordinateInputs(lat, lng) {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');

            if (latInput) latInput.value = lat.toFixed(8);
            if (lngInput) lngInput.value = lng.toFixed(8);
        }

        // Get current location
        function getCurrentLocation() {
            if (!navigator.geolocation || !map) {
                alert('Geolokatsiya qo\'llab-quvvatlanmaydi!');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    if (isWithinTashkent(lat, lng)) {
                        map.setView([lat, lng], 16);
                        addMarker(lat, lng);
                        updateCoordinateInputs(lat, lng);
                    } else {
                        alert('Siz Toshkent shahri tashqarisida turibsiz.');
                        map.setView([TASHKENT_CONFIG.center.lat, TASHKENT_CONFIG.center.lng], 11);
                    }
                },
                function(error) {
                    alert('Lokatsiya aniqlanmadi. Xaritadan qo\'lda joy tanlang.');
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 60000
                }
            );
        }

        // Load mahallas
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

        // Load streets
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

        // Window resize handler
        window.addEventListener('resize', function() {
            if (map) {
                setTimeout(() => map.invalidateSize(), 100);
            }
        });

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

    @yield('scripts')
</body>

</html>
