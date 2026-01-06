@extends('layouts.admin.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl p-6 border">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold">Import Asset dari Spreadsheet</h2>
            <p class="text-sm text-gray-600 mt-1">Unggah file CSV untuk mengimpor asset secara massal.</p>
        </div>
        <a href="{{ route('assets.index') }}"
           class="text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined">close</span>
        </a>
    </div>

    <x-alert />

    {{-- Import Errors Display --}}
    @if(session('import_errors'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-red-600">error</span>
                <h4 class="font-bold text-red-700">Kesalahan Import</h4>
            </div>
            <div class="max-h-60 overflow-y-auto">
                <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Instructions --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
            <span class="material-symbols-outlined">info</span>
            Petunjuk Import
        </h3>
        <ol class="list-decimal list-inside text-sm text-blue-800 space-y-1">
            <li>Download template file CSV terlebih dahulu</li>
            <li>Isi data asset sesuai dengan format template</li>
            <li>Kolom wajib: <strong>name</strong>, <strong>serial_number</strong>, <strong>category_name</strong></li>
            <li>Kolom opsional: barcode (akan dibuat otomatis jika kosong), asset_tag, description, dll</li>
            <li>Pastikan nama kategori, departemen, dan lokasi sesuai dengan data yang ada di sistem</li>
            <li>Upload file CSV yang sudah diisi</li>
        </ol>
    </div>

    {{-- Download Template --}}
    <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-900 mb-1">Template File</h3>
                <p class="text-sm text-gray-600">Download template CSV untuk memudahkan pengisian data</p>
            </div>
            <a href="{{ route('assets.template.download') }}"
               class="flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">download</span>
                Download Template
            </a>
        </div>
    </div>

    {{-- Upload Form --}}
    <form method="POST" action="{{ route('assets.import.process') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-2">
                Pilih File CSV
                <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center gap-4">
                <input type="file"
                       name="file"
                       accept=".csv,.txt"
                       required
                       class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-semibold
                              file:bg-primary file:text-white
                              hover:file:bg-primary/90
                              cursor-pointer" />
            </div>
            <p class="mt-1 text-xs text-gray-500">Format yang didukung: CSV, TXT (Maks. 10MB)</p>
            @error('file')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary/90">
                <span class="material-symbols-outlined">upload</span>
                Upload & Import
            </button>
            <a href="{{ route('assets.index') }}"
               class="flex items-center gap-2 px-6 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-300">
                <span class="material-symbols-outlined">cancel</span>
                Batal
            </a>
        </div>
    </form>

    {{-- Template Columns Info --}}
    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <h3 class="font-semibold text-gray-900 mb-3">Kolom yang Tersedia di Template:</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">
            <div><span class="font-semibold">name</span> <span class="text-red-500">*</span></div>
            <div><span class="font-semibold">serial_number</span> <span class="text-red-500">*</span></div>
            <div><span class="font-semibold">category_name</span> <span class="text-red-500">*</span></div>
            <div>barcode</div>
            <div>asset_tag</div>
            <div>description</div>
            <div>department_name</div>
            <div>location_name</div>
            <div>manufacturer</div>
            <div>model</div>
            <div>model_number</div>
            <div>status</div>
            <div>condition</div>
            <div>purchase_date</div>
            <div>purchase_price</div>
            <div>current_value</div>
            <div>notes</div>
        </div>
        <p class="mt-3 text-xs text-gray-600">
            <span class="text-red-500">*</span> = Wajib diisi
        </p>
    </div>
</div>
@endsection

