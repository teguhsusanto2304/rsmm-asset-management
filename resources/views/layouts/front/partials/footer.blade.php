<footer class="bg-gray-50 dark:bg-gray-900 pt-16 pb-8 border-t border-gray-200 dark:border-gray-800">
    <div class="max-w-[1280px] mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-2 text-primary">
                    <span class="material-symbols-outlined text-3xl">inventory_2</span>
                    <span class="text-xl font-extrabold text-gray-900 dark:text-white">{{ config('app.name', 'AssetMinds') }}</span>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Platform manajemen aset terdepan untuk perusahaan modern. Sederhanakan pelacakan dan tingkatkan efisiensi bisnis Anda.
                </p>
            </div>

            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Produk</h4>
                <ul class="flex flex-col gap-3 text-sm text-gray-600 dark:text-gray-400">
                    <li><a class="hover:text-primary transition-colors" href="#features">Fitur</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Integrasi</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#pricing">Harga</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Pembaruan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Perusahaan</h4>
                <ul class="flex flex-col gap-3 text-sm text-gray-600 dark:text-gray-400">
                    <li><a class="hover:text-primary transition-colors" href="#about">Tentang Kami</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Karir</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Blog</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#contact">Kontak</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-4">Dukungan</h4>
                <ul class="flex flex-col gap-3 text-sm text-gray-600 dark:text-gray-400">
                    <li><a class="hover:text-primary transition-colors" href="#">Pusat Bantuan</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Dokumentasi API</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Status Server</a></li>
                </ul>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center pt-8 border-t border-gray-200 dark:border-gray-800 gap-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} {{ config('app.name', 'AssetMinds') }} Inc. Hak Cipta Dilindungi.
            </p>
            
            <div class="flex gap-6">
                <a class="text-gray-400 hover:text-primary transition-colors" href="#" aria-label="Twitter">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                    </svg>
                </a>
                <a class="text-gray-400 hover:text-primary transition-colors" href="#" aria-label="LinkedIn">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>