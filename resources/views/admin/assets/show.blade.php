@extends('layouts.admin.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-black text-[#111518]">{{ $asset->name }}</h1>
            <p class="text-[#617989] text-sm">
                Barcode: <span class="font-mono">{{ $asset->barcode }}</span> ·
                Serial: <span class="font-mono">{{ $asset->serial_number }}</span>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('assets.edit', $asset) }}"
               class="flex items-center gap-1 px-4 py-2 rounded-lg bg-primary text-white text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined text-base">edit</span>
                Edit
            </a>
            <a href="{{ route('assets.label', $asset) }}"
               class="flex items-center gap-1 px-4 py-2 rounded-lg border border-[#dbe1e6] text-sm font-semibold text-[#111518] hover:bg-gray-50"
               target="_blank">
                <span class="material-symbols-outlined text-base">qr_code_2</span>
                Cetak Label
            </a>
            <a href="{{ route('assets.index') }}"
               class="flex items-center gap-1 px-4 py-2 rounded-lg border border-[#dbe1e6] text-sm font-semibold text-[#111518] hover:bg-gray-50">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Kembali
            </a>
        </div>
    </div>

    <x-alert />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-4">
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
                <h2 class="text-sm font-bold text-[#617989] mb-3">Informasi Utama</h2>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                    <div>
                        <dt class="text-[#617989]">Kategori</dt>
                        <dd class="font-semibold">{{ optional($asset->category)->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#617989]">Departemen</dt>
                        <dd class="font-semibold">{{ optional($asset->department)->department ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#617989]">Lokasi</dt>
                        <dd class="font-semibold">{{ optional($asset->location)->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#617989]">Status</dt>
                        <dd class="font-semibold capitalize">{{ $asset->status }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#617989]">Kondisi</dt>
                        <dd class="font-semibold capitalize">{{ $asset->condition }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#617989]">Pengguna</dt>
                        <dd class="font-semibold">{{ optional($asset->assignedUser)->name ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
                <h2 class="text-sm font-bold text-[#617989] mb-3">Informasi Pembelian</h2>
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                    <div>
                        <dt class="text-[#617989]">Tanggal Pembelian</dt>
                        <dd class="font-semibold">
                            {{ optional($asset->purchase_date)->format('d M Y') ?? '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[#617989]">Harga Beli</dt>
                        <dd class="font-semibold">
                            {{ $asset->purchase_price ? 'Rp '.number_format($asset->purchase_price,0,',','.') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[#617989]">Nilai Saat Ini</dt>
                        <dd class="font-semibold">
                            {{ $asset->current_value ? 'Rp '.number_format($asset->current_value,0,',','.') : '-' }}
                        </dd>
                    </div>
                </dl>
            </div>

            @if($asset->description)
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
                <h2 class="text-sm font-bold text-[#617989] mb-3">Deskripsi</h2>
                <p class="text-sm text-[#111518] whitespace-pre-line">
                    {{ $asset->description }}
                </p>
            </div>
            @endif
        </div>

        <div class="space-y-4">
            @if($qrSvg)
            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
                <h2 class="text-sm font-bold text-[#617989] mb-3">Label & QR</h2>
                <div class="flex flex-col items-center gap-3">
                    <div class="bg-white p-2 border rounded-lg">
                        {!! $qrSvg !!}
                    </div>
                    <div class="text-xs text-center text-[#111518]">
                        <div class="font-semibold">{{ $asset->name }}</div>
                        <div class="font-mono">{{ $asset->barcode }}</div>
                        <div class="text-[#617989] text-[11px]">Scan untuk detail asset</div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4">
                <h2 class="text-sm font-bold text-[#617989] mb-3">Identitas</h2>
                <dl class="space-y-2 text-sm">
                    <div>
                        <dt class="text-[#617989]">Barcode</dt>
                        <dd class="font-mono text-xs">{{ $asset->barcode }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#617989]">Asset Tag</dt>
                        <dd class="font-mono text-xs">{{ $asset->asset_tag ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[#617989]">Serial Number</dt>
                        <dd class="font-mono text-xs">{{ $asset->serial_number }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-xl border border-[#dbe1e6] p-4 text-xs text-[#617989] space-y-1">
                <div>Dibuat oleh: <span class="font-semibold text-[#111518]">{{ optional($asset->creator)->name ?? '-' }}</span></div>
                <div>Diperbarui oleh: <span class="font-semibold text-[#111518]">{{ optional($asset->updater)->name ?? '-' }}</span></div>
                <div>Dibuat: {{ optional($asset->created_at)->format('d M Y H:i') }}</div>
                <div>Diupdate: {{ optional($asset->updated_at)->format('d M Y H:i') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection


