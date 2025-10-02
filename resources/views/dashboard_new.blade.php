@extends('layouts.app')

@section('title', 'Boshqaruv paneli')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Page Header -->
    <div class="bg-white border-b border-gray-300 mb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Boshqaruv paneli</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ now()->format('d.m.Y, l') }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                    @if(auth()->user()->district)
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                            {{ auth()->user()->district->name }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Properties -->
            <div class="bg-white rounded-lg border border-gray-300 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="border-l-4 border-[#3561db] p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 w-12 h-12 bg-[#3561db] bg-opacity-10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">JAMI MULKLAR</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalProperties ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('properties.index') }}" class="text-xs text-[#3561db] hover:underline font-medium">
                            Batafsil ko'rish →
                        </a>
                    </div>
                </div>
            </div>

            <!-- With Tenants -->
            <div class="bg-white rounded-lg border border-gray-300 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="border-l-4 border-[#3561db] p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 w-12 h-12 bg-[#3561db] bg-opacity-10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">IJARACHILI</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $withTenants ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="inline-flex items-center px-2 py-1 bg-[#3561db] bg-opacity-10 text-[#3561db] text-xs font-medium rounded">
                            {{ $totalProperties > 0 ? round(($withTenants / $totalProperties) * 100) : 0 }}%
                        </span>
                        <a href="{{ route('properties.index', ['has_tenant' => 1]) }}" class="text-xs text-[#3561db] hover:underline font-medium">
                            Ro'yxat →
                        </a>
                    </div>
                </div>
            </div>

            <!-- This Month -->
            <div class="bg-white rounded-lg border border-gray-300 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="border-l-4 border-[#3561db] p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 w-12 h-12 bg-[#3561db] bg-opacity-10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">SHU OYDA</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $thisMonth ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-xs text-gray-600 font-medium">
                            Yangi qo'shilgan mulklar
                        </span>
                    </div>
                </div>
            </div>

            <!-- Total Area -->
            <div class="bg-white rounded-lg border border-gray-300 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="border-l-4 border-[#3561db] p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 w-12 h-12 bg-[#3561db] bg-opacity-10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                            </svg>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-gray-600 uppercase tracking-wide">UMUMIY MAYDON</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalArea ?? 0, 0, '.', ' ') }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span class="text-xs text-gray-600 font-medium">
                            Kvadrat metrda
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Period Statistics -->
        <div class="bg-white rounded-lg border border-gray-300 shadow-sm mb-6">
            <div class="bg-gradient-to-r from-[#3561db] from-opacity-10 to-[#3561db] to-opacity-5 px-6 py-4 border-b border-gray-300">
                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wide">DAVR BO'YICHA STATISTIKA</h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wide border border-gray-300">Davr</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wide border border-gray-300">Mulklar</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wide border border-gray-300">Ijarachili</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wide border border-gray-300">Foiz</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wide border border-gray-300">Maydon (m²)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-300">
                            @forelse($periodStats ?? [] as $stat)
                            <tr class="hover:bg-[#3561db] hover:bg-opacity-5 transition-colors duration-200 cursor-pointer">
                                <td class="px-4 py-3 text-sm text-gray-900 font-medium border border-gray-300">{{ $stat['period'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-right border border-gray-300">{{ $stat['total'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-right border border-gray-300">{{ $stat['with_tenants'] }}</td>
                                <td class="px-4 py-3 text-sm text-right border border-gray-300">
                                    <span class="inline-flex items-center px-2 py-1 bg-[#3561db] bg-opacity-10 text-[#3561db] text-xs font-medium rounded">
                                        {{ $stat['percentage'] }}%
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-right border border-gray-300">{{ number_format($stat['area'], 0, '.', ' ') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                                    Ma'lumot topilmadi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Line Chart -->
            <div class="bg-white rounded-lg border border-gray-300 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-300 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wide">OYLIK DINAMIKA</h3>
                    <div class="flex space-x-2">
                        <button onclick="updateChart('line', '3m')" class="px-3 py-1 text-xs font-medium border border-gray-300 rounded hover:bg-[#3561db] hover:bg-opacity-5 transition-colors duration-200 chart-period-btn active" data-period="3m">3 oy</button>
                        <button onclick="updateChart('line', '6m')" class="px-3 py-1 text-xs font-medium text-gray-700 border border-gray-300 rounded hover:bg-[#3561db] hover:bg-opacity-5 transition-colors duration-200 chart-period-btn" data-period="6m">6 oy</button>
                        <button onclick="updateChart('line', '1y')" class="px-3 py-1 text-xs font-medium text-gray-700 border border-gray-300 rounded hover:bg-[#3561db] hover:bg-opacity-5 transition-colors duration-200 chart-period-btn" data-period="1y">1 yil</button>
                    </div>
                </div>
                <div class="p-6">
                    <canvas id="lineChart" height="200"></canvas>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="bg-white rounded-lg border border-gray-300 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-300 flex items-center justify-between">
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wide">TUMANLAR BO'YICHA</h3>
                    <button onclick="refreshChart('bar')" class="text-xs text-[#3561db] hover:underline font-medium">
                        Yangilash
                    </button>
                </div>
                <div class="p-6">
                    <canvas id="barChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Recent Activity -->
            <div class="lg:col-span-2 bg-white rounded-lg border border-gray-300 shadow-sm">
                <div class="bg-gradient-to-r from-[#3561db] from-opacity-10 to-[#3561db] to-opacity-5 px-6 py-4 border-b border-gray-300">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wide">SO'NGGI FAOLIYAT</h2>
                </div>
                <div class="divide-y divide-gray-300">
                    @forelse($recentActivities ?? [] as $activity)
                    <div class="px-6 py-4 hover:bg-[#3561db] hover:bg-opacity-5 transition-colors duration-200 cursor-pointer">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-[#3561db] bg-opacity-10 rounded-full flex items-center justify-center">
                                <span class="text-[#3561db] text-sm font-bold">{{ substr($activity['user'], 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-900 font-medium">{{ $activity['action'] }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $activity['user'] }} • {{ $activity['district'] ?? 'Barcha tumanlar' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                            </div>
                            <a href="{{ $activity['url'] }}" class="text-[#3561db] hover:underline text-xs font-medium">
                                Ko'rish
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-500">
                        Faoliyat topilmadi
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border border-gray-300 shadow-sm">
                <div class="bg-gradient-to-r from-[#3561db] from-opacity-10 to-[#3561db] to-opacity-5 px-6 py-4 border-b border-gray-300">
                    <h2 class="text-xs font-bold text-gray-900 uppercase tracking-wide">TEZKOR AMALLAR</h2>
                </div>
                <div class="p-6 space-y-3">
                    @if(auth()->user()->hasPermission('create'))
                    <a href="{{ route('properties.create') }}" class="block w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-[#3561db] hover:bg-opacity-5 hover:border-[#3561db] transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-[#3561db] bg-opacity-10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Yangi mulk qo'shish</span>
                        </div>
                    </a>
                    @endif

                    <a href="{{ route('properties.index') }}" class="block w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-[#3561db] hover:bg-opacity-5 hover:border-[#3561db] transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-[#3561db] bg-opacity-10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Barcha mulklar</span>
                        </div>
                    </a>

                    @if(auth()->user()->email === 'admin@tutashhudud.uz')
                    <button onclick="document.getElementById('exportModal').classList.remove('hidden')" class="block w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-[#3561db] hover:bg-opacity-5 hover:border-[#3561db] transition-all duration-200 text-left">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-[#3561db] bg-opacity-10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Eksport qilish</span>
                        </div>
                    </button>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="block w-full px-4 py-3 border border-gray-300 rounded-lg hover:bg-[#3561db] hover:bg-opacity-5 hover:border-[#3561db] transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-[#3561db] bg-opacity-10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#3561db]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-900">Profil sozlamalari</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->email === 'admin@tutashhudud.uz')
<!-- Export Modal -->
<div id="exportModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="border-b border-gray-300 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Ma'lumotlarni eksport qilish</h3>
                <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
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
                <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors duration-200">
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Chart.js Configuration
const chartColors = {
    primary: '#3561db',
    primaryLight: 'rgba(53, 97, 219, 0.1)',
    gray: {
        300: '#d1d5db',
        500: '#6b7280',
        700: '#374151',
        900: '#111827'
    }
};

// Line Chart
const lineCtx = document.getElementById('lineChart');
const lineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($lineChartData['labels'] ?? []) !!},
        datasets: [{
            label: 'Mulklar soni',
            data: {!! json_encode($lineChartData['data'] ?? []) !!},
            borderColor: chartColors.primary,
            backgroundColor: chartColors.primaryLight,
            borderWidth: 2,
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: chartColors.gray[300]
                },
                ticks: {
                    color: chartColors.gray[700]
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: chartColors.gray[700]
                }
            }
        }
    }
});

// Bar Chart
const barCtx = document.getElementById('barChart');
const barChart = new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($barChartData['labels'] ?? []) !!},
        datasets: [{
            label: 'Mulklar soni',
            data: {!! json_encode($barChartData['data'] ?? []) !!},
            backgroundColor: chartColors.primary,
            borderColor: chartColors.primary,
            borderWidth: 1,
            borderRadius: 4,
            barThickness: 'flex',
            maxBarThickness: 50
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: chartColors.gray[900],
                padding: 12,
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: chartColors.gray[300],
                borderWidth: 1,
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return 'Mulklar: ' + context.parsed.y;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: chartColors.gray[300],
                    drawBorder: false
                },
                ticks: {
                    color: chartColors.gray[700],
                    precision: 0,
                    font: {
                        size: 11
                    }
                }
            },
            x: {
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    color: chartColors.gray[700],
                    font: {
                        size: 11
                    },
                    maxRotation: 45,
                    minRotation: 0
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});

// Chart update functions
function updateChart(chartType, period) {
    // Update button states
    document.querySelectorAll('.chart-period-btn').forEach(btn => {
        if (btn.dataset.period === period) {
            btn.classList.add('active', 'bg-[#3561db]', 'bg-opacity-10', 'text-[#3561db]', 'border-[#3561db]');
            btn.classList.remove('text-gray-700');
        } else {
            btn.classList.remove('active', 'bg-[#3561db]', 'bg-opacity-10', 'text-[#3561db]', 'border-[#3561db]');
            btn.classList.add('text-gray-700');
        }
    });

    // Fetch new data
    fetch(`/api/chart-data/${chartType}?period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (chartType === 'line') {
                lineChart.data.labels = data.labels;
                lineChart.data.datasets[0].data = data.data;
                lineChart.update();
            }
        })
        .catch(error => console.error('Chart update error:', error));
}

function refreshChart(chartType) {
    if (chartType === 'bar') {
        fetch('/api/chart-data/bar')
            .then(response => response.json())
            .then(data => {
                barChart.data.labels = data.labels;
                barChart.data.datasets[0].data = data.data;
                barChart.update();
            })
            .catch(error => console.error('Chart refresh error:', error));
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
document.getElementById('exportModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
</script>
@endsection
