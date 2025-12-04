@props([
    'id' => null,
    'action' => '#',
    'title' => 'Hapus Data?',
    'message' => 'Apakah Anda yakin ingin menghapus data ini?',
    'buttonText' => 'Hapus',
    'buttonClass' => 'bg-red-500 hover:bg-red-600',
])

<div x-data="{ open: false }" class="inline-block">
    {{-- Trigger --}}
    <button @click="open = true" {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-1 text-xs text-white bg-red-500 hover:bg-red-600 font-semibold px-3 py-2 rounded-full shadow-md transition-all hover:scale-105 ' . $buttonClass
    ]) }}>
        <i data-feather="trash-2" class="w-4 h-4"></i> {{ $buttonText }}
    </button>

    {{-- Modal --}}
    <div x-show="open" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/10 backdrop-blur-sm">
        <div @click.outside="open = false" x-transition.scale.80 class="p-6 rounded-xl shadow-xl w-full max-w-md mx-4 space-y-5 border border-gray-200 bg-white">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-100 rounded-full">
                    <i data-feather="trash-2" class="w-6 h-6 text-red-600"></i>
                </div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $title }}</h2>
            </div>
            <p class="text-gray-600">
                {{ $message }}
            </p>
            <div class="flex justify-end gap-3 pt-2">
                <form action="{{ $action }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm font-medium transition">
                        Ya, Hapus
                    </button>
                </form>
                <button @click="open = false" class="px-4 py-2 rounded-lg text-gray-600 border border-gray-300 hover:bg-gray-100 text-sm font-medium transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
