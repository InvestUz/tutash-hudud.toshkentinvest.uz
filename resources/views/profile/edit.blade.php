@extends('layouts.app')

@section('title', 'Profil sozlamalari')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Profil ma'lumotlari</h2>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="px-6 py-4">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Ism <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($user->district)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tuman</label>
                        <input type="text" value="{{ $user->district->name }}" readonly
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                    <input type="text" value="{{ ucfirst(str_replace('_', ' ', $user->role)) }}" readonly
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                    Yangilash
                </button>
            </div>
        </form>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            Profil muvaffaqiyatli yangilandi!
        </div>
    @endif
</div>
@endsection
