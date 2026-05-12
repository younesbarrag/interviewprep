@extends('layouts.app')

@section('title', 'Nouveau Domaine')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="mb-6">
            <a href="{{ route('domains.index') }}" class="text-indigo-600 hover:text-indigo-800">
                ← Retour aux domaines
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-lg">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Nouveau Domaine</h1>

            <form action="{{ route('domains.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Couleur</label>
                    <div class="flex gap-3 items-center">
                        <input type="color" name="color" id="color" value="{{ old('color', '#3B82F6') }}"
                            class="h-10 w-14 rounded border border-gray-300 cursor-pointer" required>
                        <input type="text" name="color" id="color_hex" value="{{ old('color', '#3B82F6') }}"
                            pattern="^#[0-9A-Fa-f]{6}$"
                            class="flex-1 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="#RRGGBB" required>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Format: #RRGGBB</p>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Créer
                    </button>
                    <a href="{{ route('domains.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const colorInput = document.getElementById('color');
    const colorHex = document.getElementById('color_hex');

    colorInput.addEventListener('input', function() {
        colorHex.value = this.value;
    });

    colorHex.addEventListener('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            colorInput.value = this.value;
        }
    });
</script>
@endpush
@endsection