@extends('layouts.app')

@section('title', 'Boshqaruv paneli')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h1 class="text-2xl font-bold text-gray-900">
                Xush kelibsiz, {{ auth()->user()->name }}!
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                @if(auth()->user()->district)
                    {{ auth()->user()->district->name }} tumani uchun boshqaruv paneli
                @else
                    Umumiy boshqaruv paneli
                @endif
            </p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H7m2 0v-6m10 6v-6M7 7h10M7 11h10"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Jami mulklar
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $totalProperties }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Ijarachilar
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $propertiesWithTenants }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Faoliyat turlari
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $activityTypes->count() }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Ijara foizi
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $totalProperties > 0 ? round(($propertiesWithTenants / $totalProperties) * 100, 1) : 0 }}%
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Properties -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">So'nggi qo'shilgan mulklar</h3>
            </div>
            <div class="px-6 py-4">
                @forelse($recentProperties as $property)
                    <div class="flex items-center py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $property->object_name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $property->district->name }}, {{ $property->mahalla->name }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $property->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Hech qanday mulk topilmadi</p>
                @endforelse
            </div>
            @if($recentProperties->count() > 0)
                <div class="px-6 py-3 bg-gray-50 border-t">
                    <a href="{{ route('properties.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Barchasini ko'rish →
                    </a>
                </div>
            @endif
        </div>

        <!-- Activity Types Chart -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Faoliyat turlari statistikasi</h3>
            </div>
            <div class="px-6 py-4">
                @forelse($activityTypes as $type)
                    <div class="flex items-center justify-between py-2">
                        <span class="text-sm text-gray-600">{{ $type->activity_type }}</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-blue-600 h-2 rounded-full"
                                     style="width: {{ $totalProperties > 0 ? ($type->count / $totalProperties) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">{{ $type->count }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Ma'lumot mavjud emas</p>
                @endforelse
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'super_admin' && isset($districtStats))
        <!-- District Statistics -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Tumanlar bo'yicha statistika</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tuman
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mulklar soni
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Foiz
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($districtStats as $district)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $district->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $district->properties_count }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $totalProperties > 0 ? round(($district->properties_count / $totalProperties) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
