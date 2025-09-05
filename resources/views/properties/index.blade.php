@extends('layouts.app')

@section('title', 'Mulklar ro\'yxati')

@section('content')
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Mulklar ro'yxati</h2>
            @if(auth()->user()->hasPermission('create'))
                <a href="{{ route('properties.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Yangi mulk qo'shish
                </a>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="px-6 py-4 bg-gray-50 border-b">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" placeholder="Qidirish (ism, obyekt, kadastr)..."
                       value="{{ request('search') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <select name="district_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">Barcha tumanlar</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}"
                                {{ request('district_id') == $district->id ? 'selected' : '' }}>
                            {{ $district->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <input type="text" name="activity_type" placeholder="Faoliyat turi"
                       value="{{ request('activity_type') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                    Qidirish
                </button>
                <a href="{{ route('properties.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm">
                    Tozalash
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Mulk egasi
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Obyekt nomi
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Manzil
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Faoliyat turi
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ijarachi
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Harakatlar
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($properties as $property)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $property->owner_name }}</div>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $property->object_name }}</div>
                            <div class="text-sm text-gray-500">{{ $property->building_cadastr_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $property->district->name }}, {{ $property->mahalla->name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $property->street->name }}, {{ $property->house_number }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $property->activity_type }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($property->has_tenant)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Mavjud
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Yo'q
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('properties.show', $property) }}"
                                   class="text-blue-600 hover:text-blue-900">Ko'rish</a>

                                {{-- @if(auth()->user()->hasPermission('edit'))
                                    <a href="{{ route('properties.edit', $property) }}"
                                       class="text-green-600 hover:text-green-900">Tahrirlash</a>
                                @endif --}}

                                @if(auth()->user()->hasPermission('delete'))
                                    <form method="POST" action="{{ route('properties.destroy', $property) }}"
                                          class="inline" onsubmit="return confirm('Rostdan ham o\'chirmoqchimisiz?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            O'chirish
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H7m2 0v-6m10 6v-6M7 7h10M7 11h10"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-500">Hech qanday mulk topilmadi</p>
                                <p class="text-sm text-gray-400">Qidiruv kriteriyalarini o'zgartiring yoki yangi mulk qo'shing</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($properties->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $properties->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
