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
    </style>
</head>
<body class="bg-gray-50 uzbek-font">

    <!-- Navigation -->
    <nav class="bg-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-white text-xl font-bold">Tutash Hudud Tizimi</h1>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a href="{{ route('dashboard') }}"
                               class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">
                                Bosh sahifa
                            </a>
                            <a href="{{ route('properties.index') }}"
                               class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">
                                Mulklar
                            </a>
                            @if(auth()->user()->hasPermission('create'))
                                <a href="{{ route('properties.create') }}"
                                   class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">
                                    Yangi mulk
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <span class="text-white">{{ auth()->user()->name }}</span>
                        @if(auth()->user()->district)
                            <span class="text-blue-200 text-sm">({{ auth()->user()->district->name }})</span>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:text-blue-200">
                                Chiqish
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
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

        // Location management functions
        function loadMahallas(districtId, targetSelect, selectedValue = null) {
            const select = document.getElementById(targetSelect);
            if (!select) return;

            select.innerHTML = '<option value="">Mahallani tanlang...</option>';

            if (!districtId) return;

            fetch(`/api/mahallas?district_id=${districtId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(mahalla => {
                        const option = document.createElement('option');
                        option.value = mahalla.id;
                        option.textContent = mahalla.name;
                        if (selectedValue && mahalla.id == selectedValue) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Mahallalar yuklanmadi:', error);
                });
        }

        function loadStreets(mahallaId, targetSelect, selectedValue = null) {
            const select = document.getElementById(targetSelect);
            if (!select) return;

            select.innerHTML = '<option value="">Ko\'chani tanlang...</option>';

            if (!mahallaId) return;

            fetch(`/api/streets?mahalla_id=${mahallaId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(street => {
                        const option = document.createElement('option');
                        option.value = street.id;
                        option.textContent = street.name;
                        if (selectedValue && street.id == selectedValue) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Ko\'chalar yuklanmadi:', error);
                });
        }

        // Add new location functions
        function showAddMahallaModal(districtId) {
            const modal = document.getElementById('addMahallaModal');
            if (modal && districtId) {
                document.getElementById('newMahallaDistrictId').value = districtId;
                modal.classList.remove('hidden');
            }
        }

        function showAddStreetModal(mahallaId) {
            const modal = document.getElementById('addStreetModal');
            if (modal && mahallaId) {
                document.getElementById('newStreetMahallaId').value = mahallaId;
                modal.classList.remove('hidden');
            }
        }

        function hideModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function addNewMahalla() {
            const districtId = document.getElementById('newMahallaDistrictId').value;
            const name = document.getElementById('newMahallaName').value;

            if (!name.trim()) {
                alert('Mahalla nomini kiriting!');
                return;
            }

            fetch('/api/mahallas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    district_id: districtId,
                    name: name.trim()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideModal('addMahallaModal');
                    document.getElementById('newMahallaName').value = '';
                    loadMahallas(districtId, 'mahalla_id', data.mahalla.id);
                    alert('Mahalla muvaffaqiyatli qo\'shildi!');
                }
            })
            .catch(error => {
                console.error('Xatolik:', error);
                alert('Mahalla qo\'shishda xatolik yuz berdi!');
            });
        }

        function addNewStreet() {
            const mahallaId = document.getElementById('newStreetMahallaId').value;
            const name = document.getElementById('newStreetName').value;

            if (!name.trim()) {
                alert('Ko\'cha nomini kiriting!');
                return;
            }

            fetch('/api/streets', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    mahalla_id: mahallaId,
                    name: name.trim()
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideModal('addStreetModal');
                    document.getElementById('newStreetName').value = '';
                    loadStreets(mahallaId, 'street_id', data.street.id);
                    alert('Ko\'cha muvaffaqiyatli qo\'shildi!');
                }
            })
            .catch(error => {
                console.error('Xatolik:', error);
                alert('Ko\'cha qo\'shishda xatolik yuz berdi!');
            });
        }

        // Map functionality
        let map = null;
        let marker = null;

        function initMap(containerId = 'map', lat = 41.2995, lng = 69.2401) {
            if (map) {
                map.remove();
            }

            map = L.map(containerId).setView([lat, lng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            if (lat !== 41.2995 || lng !== 69.2401) {
                marker = L.marker([lat, lng]).addTo(map);
            }

            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);

                const latInput = document.getElementById('latitude');
                const lngInput = document.getElementById('longitude');
                if (latInput) latInput.value = e.latlng.lat.toFixed(8);
                if (lngInput) lngInput.value = e.latlng.lng.toFixed(8);
            });

            return map;
        }

        function getCurrentLocation() {
            if (!navigator.geolocation) {
                alert('Brauzeringiz geolokatsiyani qo\'llab-quvvatlamaydi!');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    map.setView([lat, lng], 16);

                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map);

                    const latInput = document.getElementById('latitude');
                    const lngInput = document.getElementById('longitude');
                    if (latInput) latInput.value = lat.toFixed(8);
                    if (lngInput) lngInput.value = lng.toFixed(8);
                },
                function(error) {
                    alert('Lokatsiya aniqlanmadi: ' + error.message);
                }
            );
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

            // Check images
            const images = document.getElementById('images');
            if (images && images.files.length > 0 && images.files.length < 4) {
                alert('Kamida 4 ta rasm yuklang!');
                isValid = false;
            }

            return isValid;
        }
    </script>
    @yield('scripts')
</body>
</html>
