@extends('layouts.front.app')

@section('title', 'Solusi Manajemen Aset No. 1')

@section('content')
<section class="relative w-full py-12 md:py-20 lg:py-24 px-4 bg-white dark:bg-background-dark">
    <div class="max-w-[1280px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
        <div class="flex flex-col gap-6 max-w-2xl">
            <div class="inline-flex w-fit items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                <span>🚀 Solusi Manajemen Aset No. 1</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight tracking-tight text-gray-900 dark:text-white">
                Mengoptimalkan Manajemen <span class="text-primary">Aset Rumah Sakit</span> Mulya Medika
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                Lacak, laporkan, dan kelola semua aset fisik dan digital dalam satu platform terintegrasi.
            </p>
            
        </div>
        <div class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-800 bg-gray-50">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCeu6xJws1Fqvtlso3fI6WeKSiiMTSdu5rXJb-6w5ZnkXDzQa4PXtVUGKyxTsvQ8sHeHLnc9Ft-hbi7BCxhiHVGF-4OhWlVVQGjrTbYBejZSmv0iP-95oui6Uwr9MjYfDONnZquUbEr0uypA493gShHJPva5iWPZKqMRWRvC3HGzwpjFAbayBZ7p06dUT0gqLkDrsIKAa6dlZmNMjt-fFmzQuVVnCoqwnrnbyEAeHpCKaRUbX_BbN_4K1dhIVCYNZxyOa4TDKnUPrBt');"></div>
        </div>
    </div>
</section>
<!-- Stats Section -->
<section class="w-full bg-background-light dark:bg-gray-900/50 py-10 border-y border-gray-100 dark:border-gray-800">
<div class="max-w-[1280px] mx-auto px-4">
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="flex flex-col items-center md:items-start p-6 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700">
<div class="mb-2 p-3 rounded-full bg-blue-50 dark:bg-blue-900/20 text-primary">
<span class="material-symbols-outlined text-3xl">inventory</span>
</div>
<p class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">10,000+</p>
<p class="text-gray-500 dark:text-gray-400 font-medium">Aset Dikelola</p>
</div>
<div class="flex flex-col items-center md:items-start p-6 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700">
<div class="mb-2 p-3 rounded-full bg-blue-50 dark:bg-blue-900/20 text-primary">
<span class="material-symbols-outlined text-3xl">group</span>
</div>
<p class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">500+</p>
<p class="text-gray-500 dark:text-gray-400 font-medium">Pengguna Aktif</p>
</div>
<div class="flex flex-col items-center md:items-start p-6 rounded-xl bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700">
<div class="mb-2 p-3 rounded-full bg-blue-50 dark:bg-blue-900/20 text-primary">
<span class="material-symbols-outlined text-3xl">verified</span>
</div>
<p class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">50+</p>
<p class="text-gray-500 dark:text-gray-400 font-medium">Perusahaan Terpercaya</p>
</div>
</div>
</div>
</section>
<!-- Feature Grid Section -->
<section class="w-full py-20 px-4 bg-white dark:bg-background-dark" id="features">
<div class="max-w-[1280px] mx-auto flex flex-col gap-12">
<div class="flex flex-col items-center text-center gap-4 max-w-3xl mx-auto">
<h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white tracking-tight">Fitur Unggulan</h2>
<p class="text-lg text-gray-600 dark:text-gray-300">
                            Solusi lengkap untuk kebutuhan inventaris perusahaan Anda, dari pelacakan hingga pelaporan mendalam.
                        </p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
<!-- Feature 1 -->
<div class="flex flex-col p-8 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
<div class="w-14 h-14 mb-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-3xl">location_on</span>
</div>
<h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Pelacakan Real-time</h3>
<p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                Monitor lokasi dan status aset kapan saja dengan pembaruan langsung. Ketahui posisi aset berharga Anda detik ini juga.
                            </p>
</div>
<!-- Feature 2 -->
<div class="flex flex-col p-8 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
<div class="w-14 h-14 mb-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-3xl">bar_chart</span>
</div>
<h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Laporan Otomatis</h3>
<p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                Dapatkan wawasan penggunaan aset dan depresiasi nilai secara instan. Buat keputusan berdasarkan data yang akurat.
                            </p>
</div>
<!-- Feature 3 -->
<div class="flex flex-col p-8 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
<div class="w-14 h-14 mb-6 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
<span class="material-symbols-outlined text-3xl">manage_accounts</span>
</div>
<h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Manajemen Pengguna</h3>
<p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                Atur hak akses tim dengan mudah dan amankan data perusahaan Anda. Kontrol siapa yang bisa melihat dan mengedit.
                            </p>
</div>
</div>
<div class="flex justify-center mt-4">
<button class="h-12 px-8 rounded-lg border-2 border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white font-bold hover:border-primary hover:text-primary dark:hover:border-primary dark:hover:text-primary transition-colors">
                            Lihat Semua Fitur
                        </button>
</div>
</div>
</section>
<!-- Detailed Feature Section (Zig-Zag) -->
<section class="w-full py-20 px-4 bg-background-light dark:bg-gray-900/30">
<div class="max-w-[1280px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
<div class="order-2 lg:order-1 relative rounded-2xl overflow-hidden shadow-2xl">
<div class="aspect-video bg-cover bg-center" data-alt="Warehouse worker scanning inventory boxes with a handheld barcode scanner" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBxaoiwPG_YS9vUyA7Pr4DAabRU2kWJ91ZWQcqxqkwHtIxjHGsIdKXlFqYMGGA6hgARoaNmO7wJbb5UICB5-5GCX9s8EIjZTzx_gTDrdqiInY5hR049JrgkH3ocVTRWniv_j0mdbI06J5tLdBJwtt9h9TyKFVQ8lIeQ8rcz6rRcheo8GFdZb3CUSyA-e0aZQnVTodgkZdwen2NPaEjTyI_2WqgFExdxxKNwW-y0PyV9FrBYrJn47XnRfDjr5kzfc-LwihJp_D-0ChwI');">
</div>
<div class="absolute bottom-4 right-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg max-w-[200px] border border-gray-100 dark:border-gray-700">
<div class="flex items-center gap-2 mb-1">
<span class="material-symbols-outlined text-green-500">check_circle</span>
<span class="text-xs font-bold text-gray-900 dark:text-white">Audit Selesai</span>
</div>
<div class="h-1.5 w-full bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
<div class="h-full bg-green-500 w-full"></div>
</div>
</div>
</div>
<div class="order-1 lg:order-2 flex flex-col gap-6">
<h2 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white leading-tight">
                            Kendali Penuh di Tangan Anda
                        </h2>
<p class="text-lg text-gray-600 dark:text-gray-300">
                            Sistem kami dirancang untuk memudahkan audit dan pemeliharaan aset tanpa kerumitan spreadsheet manual yang rentan kesalahan.
                        </p>
<div class="flex flex-col gap-4 mt-4">
<div class="flex gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm">
<div class="shrink-0 mt-1">
<span class="material-symbols-outlined text-primary text-2xl">qr_code_scanner</span>
</div>
<div>
<h4 class="font-bold text-gray-900 dark:text-white text-lg">Audit Mudah &amp; Cepat</h4>
<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Lakukan stock opname dengan barcode scanner terintegrasi dan selesaikan audit aset dalam hitungan jam, bukan hari.
                                    </p>
</div>
</div>
<div class="flex gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow-sm">
<div class="shrink-0 mt-1">
<span class="material-symbols-outlined text-primary text-2xl">history</span>
</div>
<div>
<h4 class="font-bold text-gray-900 dark:text-white text-lg">Riwayat Lengkap</h4>
<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Telusuri perjalanan setiap aset dari pembelian hingga pembuangan dengan log aktivitas yang tidak dapat diubah.
                                    </p>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- CTA Section -->
<section class="w-full py-20 px-4 bg-white dark:bg-background-dark">
<div class="max-w-4xl mx-auto text-center bg-primary rounded-3xl p-8 md:p-16 relative overflow-hidden shadow-2xl shadow-primary/30">
<!-- Decorative Background Circles -->
<div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
<div class="absolute bottom-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2 pointer-events-none"></div>
<div class="relative z-10 flex flex-col items-center gap-6">
<h2 class="text-3xl md:text-5xl font-black text-white leading-tight">
                            Siap Mengelola Aset Anda Lebih Cerdas?
                        </h2>
<p class="text-lg text-blue-100 max-w-2xl">
                            Bergabunglah dengan ratusan perusahaan yang telah beralih ke AssetMinds. Coba gratis selama 14 hari, tanpa risiko.
                        </p>
<div class="flex flex-col sm:flex-row gap-4 mt-4 w-full justify-center">
<button class="h-14 px-8 rounded-xl bg-white text-primary font-bold text-lg hover:bg-gray-50 transition-colors shadow-lg">
                                Mulai Trial Gratis
                            </button>
<button class="h-14 px-8 rounded-xl bg-primary border-2 border-white text-white font-bold text-lg hover:bg-white/10 transition-colors">
                                Hubungi Sales
                            </button>
</div>
<p class="text-sm text-blue-100 mt-4 opacity-80">
                            Tidak perlu kartu kredit untuk memulai.
                        </p>
</div>
</div>
</section>

@endsection