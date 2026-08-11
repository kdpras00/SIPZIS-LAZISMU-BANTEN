<footer class="bg-gradient-to-r from-orange-800 via-orange-900 to-orange-900 text-white">
    <div class="container mx-auto px-6 py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            
            <div class="flex flex-col">
                <div class="flex items-start mb-2 -mt-6 md:-mt-8">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo Lazismu Banten" class="h-24 w-auto object-contain">
                </div>
                <p class="text-orange-100 leading-relaxed text-sm text-justify hyphens-auto -mt-4 mb-5" lang="id">
                    Plat&shy;form di&shy;gi&shy;tal za&shy;kat, in&shy;faq, dan se&shy;de&shy;kah yang trans&shy;pa&shy;ran, aman, dan se&shy;suai sya&shy;ri&shy;at Is&shy;lam.
                </p>
                <div class="flex space-x-4">
                    <a href="https://www.facebook.com/LazismuOfficial" class="text-orange-300 hover:text-white transition-colors" aria-label="Facebook"><i class="fab fa-facebook-f text-lg"></i></a>
                    <a href="https://x.com/lazismu" class="text-orange-300 hover:text-white transition-colors" aria-label="Twitter"><i class="fab fa-twitter text-lg"></i></a>
                    <a href="https://www.instagram.com/lazismupusat/#" class="text-orange-300 hover:text-white transition-colors" aria-label="Instagram"><i class="fab fa-instagram text-lg"></i></a>
                    <a href="https://www.youtube.com/@LazismuPusat62" class="text-orange-300 hover:text-white transition-colors" aria-label="YouTube"><i class="fab fa-youtube text-lg"></i></a>
                </div>
            </div>

            

            <div>
                <h3 class="text-lg font-bold mb-5 border-b border-orange-700 pb-2 uppercase tracking-wide">Tautan Cepat</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-orange-200 hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('program', ['tab' => 'infaq']) }}" class="text-orange-200 hover:text-white transition-colors">Program Infaq</a></li>
                    <li><a href="{{ route('program', ['tab' => 'zakat']) }}" class="text-orange-200 hover:text-white transition-colors">Program Zakat</a></li>
                    <li><a href="{{ route('program', ['tab' => 'shadaqah']) }}" class="text-orange-200 hover:text-white transition-colors">Program Shadaqah</a></li>
                    <li><a href="{{ route('program', ['tab' => 'pilar']) }}" class="text-orange-200 hover:text-white transition-colors">Program Pilar</a></li>
                    <li><a href="{{ route('berita.index') }}" class="text-orange-200 hover:text-white transition-colors">Berita</a></li>
                    <li><a href="{{ route('artikel.index') }}" class="text-orange-200 hover:text-white transition-colors">Artikel</a></li>
                    <li><a href="{{ route('calculator.index') }}" class="text-orange-200 hover:text-white transition-colors">Kalkulator Zakat</a></li>
                    
                </ul>
            </div>

            
            <div>
                <h3 class="text-lg font-bold mb-5 border-b border-orange-700 pb-2 uppercase tracking-wide">Kontak Kami</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-orange-300"></i>
                        <span class="text-orange-100 text-justify hyphens-auto" lang="id">Jl. Ki&shy;ai Ju&shy;rum No. 002, Ci&shy;po&shy;cok Ja&shy;ya, Kom&shy;plek De&shy;pag, Se&shy;rang, Ban&shy;ten 42121, In&shy;do&shy;ne&shy;sia</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt mr-3 text-orange-300"></i>
                        <span class="text-orange-100">0856-1626-222</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-3 text-orange-300"></i>
                        <span class="text-orange-100">info@lazismu.org</span>
                    </li>
                </ul>
            </div>

            
            <div>
                <h3 class="text-lg font-bold mb-5 border-b border-orange-700 pb-2 uppercase tracking-wide">Tentang</h3>
                <p class="text-orange-100 text-sm leading-relaxed text-justify hyphens-auto" lang="id">
                    SIPZIS ber&shy;ko&shy;mit&shy;men un&shy;tuk me&shy;mu&shy;dah&shy;kan umat da&shy;lam ber&shy;za&shy;kat, ber&shy;in&shy;faq, dan ber&shy;se&shy;de&shy;kah se&shy;ca&shy;ra di&shy;gi&shy;tal de&shy;ngan pe&shy;nuh tang&shy;gung ja&shy;wab dan trans&shy;pa&shy;ran&shy;si.
                </p>
            </div>
        </div>

        <div class="border-t border-orange-700 mt-12 pt-6 text-center">
            <p class="text-orange-300 text-sm">
                &copy; <span class="font-semibold text-white">Created By : </span> — Kurniawan Dwi Prasetyo<br>
                <span class="text-orange-400">Hak Cipta Dilindungi.</span> 
            </p>
        </div>
    </div>
</footer>