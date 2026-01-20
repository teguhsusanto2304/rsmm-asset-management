@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">Detail Permintaan Transfer</h1>
            <p class="text-[#617989]">Kelola permintaan transfer asset</p>
        </div>
        <a href="{{ route('asset-transfers.index') }}"
           class="text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined text-2xl">close</span>
        </a>
    </div>

    <x-alert />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Asset Information --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Asset</h3>
                <div class="space-y-3">
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Nama Asset</span>
                        <span class="font-semibold">{{ $assetTransfer->asset->name }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Barcode</span>
                        <span class="font-mono text-sm">{{ $assetTransfer->asset->barcode }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Serial Number</span>
                        <span class="font-mono text-sm">{{ $assetTransfer->asset->serial_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kategori</span>
                        <span class="font-semibold">{{ $assetTransfer->asset->category->name ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Transfer Information --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
                <h3 class="text-lg font-semibold mb-4">Informasi Transfer</h3>
                <div class="space-y-3">
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Jenis Transfer</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $assetTransfer->type == 'borrow' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            {{ $assetTransfer->getTypeLabel() }}
                        </span>
                    </div>
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Dari</span>
                        <span class="font-semibold">{{ $assetTransfer->requestedFromUser->name }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Ke</span>
                        <span class="font-semibold">{{ $assetTransfer->requestedToUser->name }}</span>
                    </div>
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Diminta Oleh</span>
                        <span class="font-semibold">{{ $assetTransfer->requestedByUser->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $assetTransfer->getStatusBadgeClass() }}">
                            {{ $assetTransfer->getStatusLabel() }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Dates Information --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
                <h3 class="text-lg font-semibold mb-4">Tanggal</h3>
                <div class="space-y-3">
                    <div class="flex justify-between pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Tanggal Permintaan</span>
                        <span class="font-semibold">{{ $assetTransfer->request_date->format('d/m/Y') }}</span>
                    </div>
                    @if($assetTransfer->expected_return_date)
                        <div class="flex justify-between pb-3 border-b border-gray-200">
                            <span class="text-gray-600">Tanggal Pengembalian Diharapkan</span>
                            <span class="font-semibold">{{ $assetTransfer->expected_return_date->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    @if($assetTransfer->actual_return_date)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Pengembalian Aktual</span>
                            <span class="font-semibold">{{ $assetTransfer->actual_return_date->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Reason --}}
            @if($assetTransfer->reason)
                <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
                    <h3 class="text-lg font-semibold mb-3">Alasan Permintaan</h3>
                    <p class="text-gray-700">{{ $assetTransfer->reason }}</p>
                </div>
            @endif

            {{-- Rejection Reason --}}
            @if($assetTransfer->rejection_reason)
                <div class="bg-red-50 rounded-xl border border-red-200 p-6">
                    <h3 class="text-lg font-semibold mb-3 text-red-900">Alasan Penolakan</h3>
                    <p class="text-red-700">{{ $assetTransfer->rejection_reason }}</p>
                </div>
            @endif

            {{-- Notes --}}
            @if($assetTransfer->notes)
                <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
                    <h3 class="text-lg font-semibold mb-3">Catatan</h3>
                    <p class="text-gray-700">{{ $assetTransfer->notes }}</p>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-6">
                <div class="flex flex-wrap gap-3">
                    @if($assetTransfer->status == 'pending' && $assetTransfer->requested_from == auth()->id())
                        <form method="POST" action="{{ route('asset-transfers.approve', $assetTransfer) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-bold hover:bg-green-700"
                                    onclick="return confirm('Setujui permintaan transfer ini?')">
                                <span class="material-symbols-outlined">check_circle</span>
                                Setujui
                            </button>
                        </form>

                        <button @click="openRejectModal = true" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700">
                            <span class="material-symbols-outlined">cancel</span>
                            Tolak
                        </button>
                    @endif

                    @if($assetTransfer->status == 'approved' && ($assetTransfer->requested_from == auth()->id() || $assetTransfer->requested_by == auth()->id()))
                        <form method="POST" action="{{ route('asset-transfers.complete', $assetTransfer) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-bold hover:bg-blue-700"
                                    onclick="return confirm('Transfer asset ini sekarang?')">
                                <span class="material-symbols-outlined">done_all</span>
                                Transfer Sekarang
                            </button>
                        </form>
                    @endif

                    @if($assetTransfer->status == 'completed' && $assetTransfer->type == 'borrow' && $assetTransfer->requested_to == auth()->id())
                        <form method="POST" action="{{ route('asset-transfers.mark-returned', $assetTransfer) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-bold hover:bg-purple-700"
                                    onclick="return confirm('Kembalikan asset ini?')">
                                <span class="material-symbols-outlined">undo</span>
                                Kembalikan Asset
                            </button>
                        </form>
                    @endif

                    @if($assetTransfer->status == 'pending' && $assetTransfer->requested_by == auth()->id())
                        <form method="POST" action="{{ route('asset-transfers.cancel', $assetTransfer) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-gray-600 text-white rounded-lg text-sm font-bold hover:bg-gray-700"
                                    onclick="return confirm('Batalkan permintaan ini?')">
                                <span class="material-symbols-outlined">clear</span>
                                Batalkan
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('asset-transfers.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                        <span class="material-symbols-outlined">arrow_back</span>
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        {{-- Status Timeline --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-6 sticky top-6">
                <h4 class="font-semibold text-gray-900 mb-4">Status</h4>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="mt-1">
                            <div class="w-3 h-3 bg-primary rounded-full"></div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ $assetTransfer->getStatusLabel() }}</div>
                            <div class="text-sm text-gray-500">Status Saat Ini</div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-900">
                    @if($assetTransfer->status == 'pending')
                        <strong>Menunggu persetujuan</strong> dari pemilik asset saat ini ({{ $assetTransfer->requestedFromUser->name }})
                    @elseif($assetTransfer->status == 'approved')
                        <strong>Permintaan telah disetujui.</strong> Transfer dapat dilakukan kapan saja.
                    @elseif($assetTransfer->status == 'completed')
                        @if($assetTransfer->type == 'borrow')
                            <strong>Asset sedang dipinjam.</strong> Tunggu pengembalian dari {{ $assetTransfer->requestedToUser->name }}
                        @else
                            <strong>Asset telah dipindahkan.</strong> Kepemilikan sekarang adalah {{ $assetTransfer->requestedToUser->name }}
                        @endif
                    @elseif($assetTransfer->status == 'returned')
                        <strong>Asset telah dikembalikan</strong> kepada {{ $assetTransfer->requestedFromUser->name }} pada {{ $assetTransfer->actual_return_date->format('d/m/Y') }}
                    @elseif($assetTransfer->status == 'rejected')
                        <strong>Permintaan telah ditolak.</strong> Hubungi pemilik untuk informasi lebih lanjut.
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div x-data="{ openRejectModal: false }" x-show="openRejectModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" style="display: none;">
        <div class="bg-white rounded-xl p-6 max-w-md mx-4" @click.outside="openRejectModal = false">
            <h3 class="text-lg font-semibold mb-4">Tolak Permintaan</h3>
            <form method="POST" action="{{ route('asset-transfers.reject', $assetTransfer) }}">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium mb-2">
                        Alasan Penolakan
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea id="rejection_reason"
                              name="rejection_reason"
                              rows="3"
                              placeholder="Jelaskan alasan penolakan..."
                              class="w-full form-input rounded-lg"
                              required></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700">
                        Tolak
                    </button>
                    <button type="button" @click="openRejectModal = false" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
