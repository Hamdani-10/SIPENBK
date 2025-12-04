@props([
    'id',
    'action'
])

<button type="button" data-id="{{ $id }}" data-action="{{ $action }}" onclick="handleDelete(this)" class="flex items-center space-x-2 bg-red-500 hover:bg-red-600 text-white px-3 py-2.5 rounded-md text-xs font-medium shadow-sm cursor-pointer">
    <i data-feather="trash-2" class="w-4 h-4"></i>
    <span>Hapus</span>
</button>
