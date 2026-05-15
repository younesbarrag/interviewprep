@extends('layouts.app')

@section('title', 'Modifier le Domaine')

@section('content')
<div class="py-8">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('domains.index') }}" class="inline-flex items-center gap-2 text-indigo-400 hover:text-indigo-300 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Retour aux domaines
            </a>
        </div>

        <div class="bg-slate-800/70 rounded-xl p-6 border border-slate-700">
            <h1 class="text-xl font-bold text-slate-100 mb-6">Modifier le Domaine</h1>

            <form action="{{ route('domains.update', $domain) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Nom</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $domain->name) }}"
                        class="w-full rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                        required>
                    @error('name')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="color" class="block text-sm font-medium text-slate-300 mb-2">Couleur</label>
                    <div class="flex gap-3 items-center">
                        <input type="color" id="color_picker" value="{{ old('color', $domain->color) }}"
                            class="h-10 w-14 rounded-lg border border-slate-600 cursor-pointer">
                        <input type="text" name="color" id="color" value="{{ old('color', $domain->color) }}"
                            pattern="^#[0-9A-Fa-f]{6}$"
                            class="flex-1 rounded-lg border border-slate-600 bg-slate-700 text-slate-100 px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('color') border-red-500 @enderror"
                            placeholder="#RRGGBB" required>
                    </div>
                    <p class="mt-1.5 text-xs text-slate-500">Format: #RRGGBB</p>
                    @error('color')
                        <p class="mt-1.5 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-lg transition">
                        Enregistrer
                    </button>
                    <a href="{{ route('domains.index') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-200 font-medium px-5 py-2.5 rounded-lg transition">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const colorPicker = document.getElementById('color_picker');
    const colorInput = document.getElementById('color');

    colorPicker.addEventListener('input', function() {
        colorInput.value = this.value;
    });

    colorInput.addEventListener('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            colorPicker.value = this.value;
        }
    });
</script>
@endpush
@endsection