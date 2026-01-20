@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Buat Permintaan Transfer Asset</h1>
            <p class="text-[#617989]">Ajukan permintaan untuk meminjam atau memindahkan asset</p>
        </div>
        <a href="{{ route('asset-transfers.index') }}"
           class="text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined text-2xl">close</span>
        </a>
    </div>

    <x-alert />

    <form method="POST" action="{{ route('asset-transfers.store') }}" class="space-y-6">
        @csrf

        {{-- Asset Selection --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-6 space-y-5">
            <h3 class="text-lg font-semibold">Pilih Asset</h3>

            @if($asset)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm mb-3">Asset yang dipilih:</p>
                    <div class="bg-white rounded p-3 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-semibold">{{ $asset->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Barcode:</span>
                            <span class="font-mono text-sm">{{ $asset->barcode }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Pemilik Saat Ini:</span>
                            <span class="font-semibold">{{ $asset->assignedUser->name ?? 'Tidak Ditugaskan' }}</span>
                        </div>
                    </div>
                    <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                </div>
            @else
                <div>
                    <label for="asset_id" class="block text-sm font-medium mb-2">
                        Pilih Asset
                        <span class="text-red-500">*</span>
                    </label>
                    <select id="asset_id"
                            name="asset_id"
                            class="w-full form-input rounded-lg @error('asset_id') border-red-500 @enderror"
                            required>
                        <option value="">-- Pilih Asset --</option>
                        @foreach($myAssets as $myAsset)
                            <option value="{{ $myAsset->id }}" {{ old('asset_id') == $myAsset->id ? 'selected' : '' }}>
                                {{ $myAsset->name }} - {{ $myAsset->category->name ?? '-' }} ({{ $myAsset->barcode }})
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif
        </div>

        {{-- Transfer Details --}}
        <div class="bg-white rounded-xl border border-[#dbe1e6] p-6 space-y-5">
            <h3 class="text-lg font-semibold">Detail Transfer</h3>

            {{-- Transfer Type --}}
            <div>
                <label class="block text-sm font-medium mb-3">
                    Jenis Transfer
                    <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="type" value="borrow" class="mr-3" 
                            {{ old('type', 'borrow') == 'borrow' ? 'checked' : '' }} required>
                        <span class="font-medium">Pinjam</span>
                        <span class="text-sm text-gray-500 ml-2">(Asset akan dikembalikan ke pemilik asli)</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="type" value="move" class="mr-3"
                            {{ old('type') == 'move' ? 'checked' : '' }} required>
                        <span class="font-medium">Pindah</span>
                        <span class="text-sm text-gray-500 ml-2">(Kepemilikan asset berubah secara permanen)</span>
                    </label>
                </div>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- From User --}}
            <div>
                <label for="requested_from" class="block text-sm font-medium mb-2">
                    Dari (Pemilik Saat Ini)
                    <span class="text-red-500">*</span>
                </label>
                <select id="requested_from"
                        name="requested_from"
                        class="w-full form-input rounded-lg @error('requested_from') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Pemilik Asset --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('requested_from', $asset?->assigned_to) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('requested_from')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- To User --}}
            <div>
                <label for="requested_to" class="block text-sm font-medium mb-2">
                    Ke (Penerima)
                    <span class="text-red-500">*</span>
                </label>
                <select id="requested_to"
                        name="requested_to"
                        class="w-full form-input rounded-lg @error('requested_to') border-red-500 @enderror"
                        required>
                    <option value="">-- Pilih Penerima --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('requested_to') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('requested_to')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Expected Return Date (for borrow) --}}
            <div id="expected_return_date_field">
                <label for="expected_return_date" class="block text-sm font-medium mb-2">
                    Tanggal Pengembalian (Untuk Peminjaman)
                    <span class="text-red-500">*</span>
                </label>
                <input type="date"
                       id="expected_return_date"
                       name="expected_return_date"
                       value="{{ old('expected_return_date') }}"
                       class="w-full form-input rounded-lg @error('expected_return_date') border-red-500 @enderror">
                @error('expected_return_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Reason --}}
            <div>
                <label for="reason" class="block text-sm font-medium mb-2">
                    Alasan Permintaan
                    <span class="text-red-500">*</span>
                </label>
                <textarea id="reason"
                          name="reason"
                          rows="3"
                          placeholder="Jelaskan alasan permintaan transfer asset..."
                          class="w-full form-input rounded-lg resize-none @error('reason') border-red-500 @enderror"
                          required>{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div>
                <label for="notes" class="block text-sm font-medium mb-2">
                    Catatan Tambahan (Opsional)
                </label>
                <textarea id="notes"
                          name="notes"
                          rows="2"
                          placeholder="Tambahkan catatan atau informasi lainnya..."
                          class="w-full form-input rounded-lg resize-none">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">send</span>
                Ajukan Permintaan
            </button>
            <a href="{{ route('asset-transfers.index') }}"
               class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                <span class="material-symbols-outlined">cancel</span>
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeRadios = document.querySelectorAll('input[name="type"]');
        const expectedReturnDateField = document.getElementById('expected_return_date_field');
        const expectedReturnDateInput = document.getElementById('expected_return_date');

        function updateReturnDateRequired() {
            const selectedType = document.querySelector('input[name="type"]:checked').value;
            if (selectedType === 'borrow') {
                expectedReturnDateField.style.display = 'block';
                expectedReturnDateInput.required = true;
            } else {
                expectedReturnDateField.style.display = 'none';
                expectedReturnDateInput.required = false;
                expectedReturnDateInput.value = '';
            }
        }

        typeRadios.forEach(radio => {
            radio.addEventListener('change', updateReturnDateRequired);
        });

        // Initial state
        updateReturnDateRequired();
    });
</script>
@endsection
