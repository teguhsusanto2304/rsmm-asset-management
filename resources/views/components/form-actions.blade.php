<div class="flex justify-end gap-3">
    
    {{-- Cancel button --}}
    <a href="{{ $cancelRoute ?? url()->previous() }}"
       class="px-4 py-2 rounded-lg border bg-red-400 border-gray-300 text-white hover:bg-red-700">
        {{ $cancelLabel ?? 'Batal' }}
    </a>

    {{-- Submit / Update button --}}
    <button type="submit"
        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 transition">
        {{ $label ?? 'Simpan' }}
    </button>

</div><div>
    <!-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh -->
</div>