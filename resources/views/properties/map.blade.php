@extends('layouts.app')

@section('title', 'Mulklar Xaritasi')

@section('content')
<div class="h-screen flex flex-col bg-gray-50">
    <!-- Header with Filters -->
    <div class="bg-white border-b border-gray-300 shadow-sm">
        <div class="px-4 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <!-- Title -->
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                        </svg>
                        Mulklar Xaritasi
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">Jami: <span id="propertyCount" class="font-semibold text-[#3561db]">{{ $properties->count() }}</span> ta mulk</p>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-3">
                    <!-- Map Style Selector -->
                    <select id="mapStyleSelector" onchange="changeMapStyle()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3561db] focus:border-[#3561db]">
                        <option value="streets">Ko'chalar</option>
                        <option value="satellite">Sun'iy yo'ldosh</option>
                        <option value="hybrid">Gibrid</option>
                        <option value="terrain">Relyef</option>
                    </select>

                    <!-- District Filter -->
                    <select id="districtFilter" onchange="applyFilters()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3561db] focus:border-[#3561db]">
                        <option value="">Barcha tumanlar</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ $districtId == $district->id ? 'selected' : '' }}>
                                {{ $district->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Verified Filter -->
                    <select id="verifiedFilter" onchange="applyFilters()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3561db] focus:border-[#3561db]">
                        <option value="">Barchasi</option>
                        <option value="1" {{ $hasVerified ? 'selected' : '' }}>Tasdiqlangan</option>
                    </select>

                    <!-- Tenant Filter -->
                    <select id="tenantFilter" onchange="applyFilters()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#3561db] focus:border-[#3561db]">
                        <option value="">Hammasi</option>
                        <option value="1" {{ $hasTenant === '1' ? 'selected' : '' }}>Ijarachi bor</option>
                        <option value="0" {{ $hasTenant === '0' ? 'selected' : '' }}>Ijarachi yo'q</option>
                    </select>

                    <!-- Reset Button -->
                    <button onclick="resetFilters()"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-300 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Tozalash
                    </button>

                    <!-- View Buttons -->
                    <a href="{{ route('properties.index') }}"
                       class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        Ro'yxat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Map and Sidebar Container -->
    <div class="flex-1 flex overflow-hidden relative">
        <!-- Loading Overlay -->
        <div id="mapLoadingOverlay" class="absolute inset-0 bg-white bg-opacity-90 z-50 flex items-center justify-center">
            <div class="text-center">
                <div class="spinner mx-auto mb-4" style="width: 40px; height: 40px;"></div>
                <p class="text-gray-700 font-medium">Xarita yuklanmoqda...</p>
                <p class="text-sm text-gray-500 mt-2">Iltimos, kuting</p>
            </div>
        </div>

        <!-- Map Container -->
        <div id="mapContainer" class="flex-1 relative transition-all duration-300">
            <div id="propertyMap" class="w-full h-full"></div>

            <!-- Map Legend -->
            <div class="absolute bottom-6 left-6 bg-white rounded-lg shadow-lg border border-gray-300 p-4 z-10">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Belgilar</h3>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full bg-blue-500 mr-2"></div>
                        <span class="text-xs text-gray-700">Oddiy mulk</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full bg-green-500 mr-2"></div>
                        <span class="text-xs text-gray-700">Tasdiqlangan</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full bg-orange-500 mr-2"></div>
                        <span class="text-xs text-gray-700">Ijarachi bor</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (Hidden by default) -->
        <div id="propertySidebar"
             class="hidden w-full lg:w-[480px] bg-white border-l border-gray-300 overflow-y-auto transition-all duration-300 shadow-2xl">
            <!-- Sidebar Header -->
            <div class="sticky top-0 bg-white border-b border-gray-300 p-4 flex items-center justify-between z-10">
                <h2 class="text-lg font-bold text-gray-900">Mulk tafsilotlari</h2>
                <button onclick="closeSidebar()"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar Content -->
            <div id="sidebarContent" class="p-6">
                <div class="text-center py-12">
                    <div class="spinner mx-auto mb-4"></div>
                    <p class="text-gray-600">Yuklanmoqda...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#propertyMap {
    z-index: 0;
}

.leaflet-popup-content-wrapper {
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.leaflet-popup-content {
    margin: 12px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Custom marker cluster styles */
.marker-cluster-small {
    background-color: rgba(53, 97, 219, 0.6);
}
.marker-cluster-small div {
    background-color: rgba(53, 97, 219, 0.8);
}

.marker-cluster-medium {
    background-color: rgba(241, 128, 23, 0.6);
}
.marker-cluster-medium div {
    background-color: rgba(241, 128, 23, 0.8);
}

.marker-cluster-large {
    background-color: rgba(253, 156, 115, 0.6);
}
.marker-cluster-large div {
    background-color: rgba(253, 156, 115, 0.8);
}

.marker-cluster {
    background-clip: padding-box;
    border-radius: 20px;
}
.marker-cluster div {
    width: 30px;
    height: 30px;
    margin-left: 5px;
    margin-top: 5px;
    text-align: center;
    border-radius: 15px;
    font: 12px "Helvetica Neue", Arial, Helvetica, sans-serif;
    font-weight: bold;
}
.marker-cluster span {
    line-height: 30px;
    color: white;
}

/* Lazy load image placeholder */
.lazy-image {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.gallery-container {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 8px;
}

.gallery-image {
    aspect-ratio: 1;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.2s, opacity 0.3s;
    opacity: 0;
}

.gallery-image.loaded {
    opacity: 1;
}

.gallery-image:hover {
    transform: scale(1.05);
}

.lightbox {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.lightbox.active {
    display: flex;
}

.lightbox-content {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
}

/* Custom marker cluster styles */
.marker-cluster-small {
    background-color: rgba(53, 97, 219, 0.6);
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.marker-cluster-small div {
    background-color: rgba(53, 97, 219, 0.9);
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 12px;
}
</style>

<script>
let propertyMap = null;
let markers = [];
let currentLayer = null;
let propertiesData = {!! json_encode($properties->map(function($p) {
    return [
        'id' => $p->id,
        'latitude' => $p->latitude,
        'longitude' => $p->longitude,
        'owner_name' => $p->owner_name,
        'district_name' => optional($p->district)->name ?? '',
        'owner_verified' => $p->owner_verified,
        'has_tenant' => $p->has_tenant,
    ];
})) !!};
let currentProperty = null;

// Map layer configurations
const mapLayers = {
    streets: {
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        attribution: '© OpenStreetMap contributors'
    },
    satellite: {
        url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        attribution: '© Esri'
    },
    hybrid: {
        url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        attribution: '© Esri',
        overlay: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'
    },
    terrain: {
        url: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
        attribution: '© OpenTopoMap contributors'
    }
};

// Initialize map on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing map...');

    // Defer map initialization slightly for better performance
    setTimeout(() => {
        initPropertyMap();
    }, 100);
});

function initPropertyMap() {
    console.log('Initializing optimized map...');

    // Initialize map
    propertyMap = L.map('propertyMap', {
        preferCanvas: true, // Better performance for many markers
        zoomControl: true,
        attributionControl: true
    }).setView([41.2995, 69.2401], 11);

    // Add default tile layer
    currentLayer = L.tileLayer(mapLayers.streets.url, {
        attribution: mapLayers.streets.attribution,
        maxZoom: 18,
        minZoom: 9
    }).addTo(propertyMap);

    // Add markers in batches for better performance
    addPropertyMarkersBatched();

    // Hide loading overlay
    setTimeout(() => {
        document.getElementById('mapLoadingOverlay').style.display = 'none';
    }, 500);

    // Invalidate size
    setTimeout(() => {
        propertyMap.invalidateSize();
    }, 200);
}

function addPropertyMarkersBatched() {
    const batchSize = 100; // Process 100 markers at a time
    let currentIndex = 0;
    const bounds = [];

    function processBatch() {
        const endIndex = Math.min(currentIndex + batchSize, propertiesData.length);

        for (let i = currentIndex; i < endIndex; i++) {
            const property = propertiesData[i];
            const lat = parseFloat(property.latitude);
            const lng = parseFloat(property.longitude);

            if (isNaN(lat) || isNaN(lng)) continue;

            bounds.push([lat, lng]);

            // Create simple circle marker for better performance
            const marker = L.circleMarker([lat, lng], {
                radius: 8,
                fillColor: property.owner_verified ? '#10b981' : property.has_tenant ? '#f97316' : '#3561db',
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(propertyMap);

            marker.propertyId = property.id;

            // Bind simple popup
            marker.bindPopup(`
                <div class="text-sm">
                    <strong class="text-gray-900">${property.owner_name}</strong>
                    <p class="text-gray-600 text-xs mt-1">${property.district_name}</p>
                    <p class="text-[#3561db] text-xs mt-1 cursor-pointer hover:underline">
                        Ko'proq ma'lumot →
                    </p>
                </div>
            `);

            // Add click event
            marker.on('click', function() {
                loadPropertyDetails(property.id);
            });

            markers.push(marker);
        }

        currentIndex = endIndex;

        // Update progress
        const progress = Math.round((currentIndex / propertiesData.length) * 100);
        console.log(`Loading markers: ${progress}%`);

        // Continue processing if there are more markers
        if (currentIndex < propertiesData.length) {
            setTimeout(processBatch, 10); // Small delay between batches
        } else {
            // All markers added, fit bounds
            if (bounds.length > 0) {
                setTimeout(() => {
                    propertyMap.fitBounds(bounds, {
                        padding: [50, 50],
                        maxZoom: 15
                    });
                }, 100);
            }
            console.log(`All ${markers.length} markers loaded successfully!`);
        }
    }

    processBatch();
}

function changeMapStyle() {
    const style = document.getElementById('mapStyleSelector').value;
    const layerConfig = mapLayers[style];

    // Remove current layer
    if (currentLayer) {
        propertyMap.removeLayer(currentLayer);
    }

    // Add new layer
    currentLayer = L.tileLayer(layerConfig.url, {
        attribution: layerConfig.attribution,
        maxZoom: 18,
        minZoom: 9
    }).addTo(propertyMap);

    // Add overlay for hybrid mode
    if (style === 'hybrid' && layerConfig.overlay) {
        L.tileLayer(layerConfig.overlay, {
            opacity: 0.5,
            maxZoom: 18
        }).addTo(propertyMap);
    }
}

function loadPropertyDetails(propertyId) {
    const sidebar = document.getElementById('propertySidebar');
    const content = document.getElementById('sidebarContent');

    // Show sidebar
    sidebar.classList.remove('hidden');

    // Show loading state
    content.innerHTML = `
        <div class="text-center py-12">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-gray-600">Yuklanmoqda...</p>
        </div>
    `;

    // Fetch full property details via AJAX
    fetch(`/properties/${propertyId}/details`)
        .then(response => response.json())
        .then(property => {
            currentProperty = property;
            content.innerHTML = generateSidebarContent(property);
            initLazyImages();
        })
        .catch(error => {
            console.error('Error loading property:', error);
            content.innerHTML = `
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-600 font-medium">Ma'lumotlarni yuklashda xatolik</p>
                    <button onclick="loadPropertyDetails(${propertyId})" class="mt-4 px-4 py-2 bg-[#3561db] text-white rounded-lg hover:bg-opacity-90">
                        Qayta urinish
                    </button>
                </div>
            `;
        });
}

function generateSidebarContent(property) {
    const images = property.images || [];
    const createdAt = new Date(property.created_at).toLocaleDateString('uz-UZ', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    return `
        <!-- Status Badge -->
        <div class="flex items-center gap-2 mb-4">
            ${property.owner_verified ?
                '<span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">✓ Tasdiqlangan</span>' :
                '<span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-medium rounded-full">Tasdiqlanmagan</span>'
            }
            ${property.has_tenant ?
                '<span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">Ijarachi bor</span>' :
                ''
            }
        </div>

        <!-- Images Gallery -->
        ${images.length > 0 ? `
            <div class="mb-6">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Rasmlar (${images.length})</h3>
                <div class="gallery-container">
                    ${images.slice(0, 4).map((img, idx) => `
                        <div class="lazy-image rounded-lg" style="aspect-ratio: 1;">
                            <img data-src="/storage/${img}"
                                 alt="Property image ${idx + 1}"
                                 class="gallery-image w-full h-full"
                                 onclick="openLightbox('${img}')">
                        </div>
                    `).join('')}
                </div>
                ${images.length > 4 ? `
                    <button onclick="showAllImages()" class="mt-2 text-sm text-[#3561db] hover:underline">
                        +${images.length - 4} ta rasm ko'rish
                    </button>
                ` : ''}
            </div>
        ` : ''}

        <!-- Owner Information -->
        <div class="mb-6">
            <h3 class="text-sm font-bold text-gray-900 mb-3">Mulk egasi ma'lumotlari</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Egasi:</span>
                    <span class="text-sm font-medium text-gray-900">${property.owner_name}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">STIR/PINFL:</span>
                    <span class="text-sm font-medium text-gray-900">${property.owner_stir_pinfl}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Rahbar:</span>
                    <span class="text-sm font-medium text-gray-900">${property.director_name}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Telefon:</span>
                    <span class="text-sm font-medium text-gray-900">${property.phone_number}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Kadastr:</span>
                    <span class="text-sm font-medium text-gray-900">${property.building_cadastr_number}</span>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="mb-6">
            <h3 class="text-sm font-bold text-gray-900 mb-3">Manzil</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Tuman:</span>
                    <span class="text-sm font-medium text-gray-900">${property.district?.name || '-'}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Mahalla:</span>
                    <span class="text-sm font-medium text-gray-900">${property.mahalla?.name || '-'}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Ko'cha:</span>
                    <span class="text-sm font-medium text-gray-900">${property.street?.name || '-'}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Uy raqami:</span>
                    <span class="text-sm font-medium text-gray-900">${property.house_number}</span>
                </div>
            </div>
        </div>

        <!-- Property Details -->
        <div class="mb-6">
            <h3 class="text-sm font-bold text-gray-900 mb-3">Mulk haqida</h3>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Faoliyat turi:</span>
                    <span class="text-sm font-medium text-gray-900">${property.activity_type}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Umumiy maydon:</span>
                    <span class="text-sm font-medium text-gray-900">${property.total_area} m²</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Fasad uzunligi:</span>
                    <span class="text-sm font-medium text-gray-900">${property.building_facade_length} m</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-200">
                    <span class="text-sm text-gray-600">Foydalanish maqsadi:</span>
                    <span class="text-sm font-medium text-gray-900">${property.usage_purpose}</span>
                </div>
            </div>
        </div>

        <!-- Tenant Information -->
        ${property.has_tenant && property.tenant_name ? `
            <div class="mb-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                <h3 class="text-sm font-bold text-orange-900 mb-3">Ijarachi ma'lumotlari</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-orange-700">Nomi:</span>
                        <span class="text-sm font-medium text-orange-900">${property.tenant_name}</span>
                    </div>
                    ${property.tenant_stir_pinfl ? `
                        <div class="flex justify-between">
                            <span class="text-sm text-orange-700">STIR/PINFL:</span>
                            <span class="text-sm font-medium text-orange-900">${property.tenant_stir_pinfl}</span>
                        </div>
                    ` : ''}
                    ${property.tenant_activity_type ? `
                        <div class="flex justify-between">
                            <span class="text-sm text-orange-700">Faoliyati:</span>
                            <span class="text-sm font-medium text-orange-900">${property.tenant_activity_type}</span>
                        </div>
                    ` : ''}
                </div>
            </div>
        ` : ''}

        <!-- Created By -->
        <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <h3 class="text-sm font-bold text-gray-900 mb-3">Tizim ma'lumotlari</h3>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Yaratuvchi:</span>
                    <span class="text-sm font-medium text-gray-900">${property.creator?.name || '-'}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Yaratilgan:</span>
                    <span class="text-sm font-medium text-gray-900">${createdAt}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <a href="/properties/${property.id}"
               class="flex-1 px-4 py-3 bg-[#3561db] text-white text-center rounded-lg hover:bg-opacity-90 transition-colors font-medium">
                Batafsil ko'rish
            </a>
            ${property.google_maps_url ? `
                <a href="${property.google_maps_url}"
                   target="_blank"
                   class="px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </a>
            ` : ''}
        </div>
    `;
}

function initLazyImages() {
    const images = document.querySelectorAll('.gallery-image');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                const src = img.getAttribute('data-src');

                if (src) {
                    img.src = src;
                    img.onload = () => {
                        img.classList.add('loaded');
                        img.parentElement.classList.remove('lazy-image');
                    };
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            }
        });
    }, {
        rootMargin: '50px'
    });

    images.forEach(img => imageObserver.observe(img));
}

function closeSidebar() {
    document.getElementById('propertySidebar').classList.add('hidden');
    currentProperty = null;
}

function applyFilters() {
    const districtId = document.getElementById('districtFilter').value;
    const verified = document.getElementById('verifiedFilter').value;
    const tenant = document.getElementById('tenantFilter').value;

    const params = new URLSearchParams();
    if (districtId) params.append('district_id', districtId);
    if (verified) params.append('verified_only', verified);
    if (tenant) params.append('has_tenant', tenant);

    window.location.href = `/properties/map?${params.toString()}`;
}

function resetFilters() {
    window.location.href = '/properties/map';
}

function openLightbox(imagePath) {
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox active';
    lightbox.innerHTML = `
        <button onclick="this.parentElement.remove()"
                class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-70 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img src="/storage/${imagePath}" class="lightbox-content" alt="Property image">
    `;

    lightbox.onclick = function(e) {
        if (e.target === lightbox) {
            lightbox.remove();
        }
    };

    document.body.appendChild(lightbox);
}

function showAllImages() {
    if (!currentProperty || !currentProperty.images) return;

    const images = currentProperty.images;
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox active';

    let currentIndex = 0;

    function updateImage() {
        const img = lightbox.querySelector('.lightbox-content');
        img.src = `/storage/${images[currentIndex]}`;
        lightbox.querySelector('.image-counter').textContent = `${currentIndex + 1} / ${images.length}`;
    }

    lightbox.innerHTML = `
        <button onclick="this.parentElement.remove()"
                class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-70 z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <button class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-70 prev-btn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <button class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-70 next-btn">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm image-counter">
            1 / ${images.length}
        </div>

        <img src="/storage/${images[0]}" class="lightbox-content" alt="Property image">
    `;

    lightbox.querySelector('.prev-btn').onclick = function(e) {
        e.stopPropagation();
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateImage();
    };

    lightbox.querySelector('.next-btn').onclick = function(e) {
        e.stopPropagation();
        currentIndex = (currentIndex + 1) % images.length;
        updateImage();
    };

    lightbox.onclick = function(e) {
        if (e.target === lightbox) {
            lightbox.remove();
        }
    };

    const keyHandler = function(e) {
        if (!document.body.contains(lightbox)) {
            document.removeEventListener('keydown', keyHandler);
            return;
        }

        if (e.key === 'ArrowLeft') {
            lightbox.querySelector('.prev-btn').click();
        } else if (e.key === 'ArrowRight') {
            lightbox.querySelector('.next-btn').click();
        } else if (e.key === 'Escape') {
            lightbox.remove();
        }
    };

    document.addEventListener('keydown', keyHandler);
    document.body.appendChild(lightbox);
}

// Handle window resize
let resizeTimeout;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
        if (propertyMap) {
            propertyMap.invalidateSize();
        }
    }, 250);
});

// Close sidebar on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const sidebar = document.getElementById('propertySidebar');
        if (sidebar && !sidebar.classList.contains('hidden')) {
            closeSidebar();
        }
    }
});
</script>

@endsection
