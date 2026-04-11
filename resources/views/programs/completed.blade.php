@extends('layouts.main')

@section('title', 'Target Tercapai - ' . $program->name)

@section('navbar')
@include('partials.navbarHome')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-b from-emerald-50 via-white to-teal-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        {{-- Success Card --}}
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 border-t-4 border-emerald-500">
            {{-- Confetti Background --}}
            <div class="relative bg-gradient-to-br from-emerald-500 to-teal-600 p-8 text-center">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-32 h-32 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 right-0 w-32 h-32 bg-white rounded-full blur-3xl"></div>
                </div>
                
                <div class="relative z-10">
                    {{-- Success Icon --}}
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-4 shadow-lg">
                        <i class="fas fa-check-circle text-emerald-500 text-5xl"></i>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                        Alhamdulillah, Target Tercapai!
                    </h1>
                    <p class="text-emerald-100 text-lg">
                        Program ini telah mencapai target donasi
                    </p>
                </div>
            </div>

            {{-- Program Info --}}
            <div class="p-8">
                <div class="flex items-start gap-4 mb-6">
                    <img src="{{ $program->image_url }}" 
                         alt="{{ $program->name }}"
                         class="w-24 h-24 object-cover rounded-xl shadow-md"
                         onerror="this.src='{{ asset('img/masjid.webp') }}'">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $program->name }}</h2>
                        <p class="text-gray-600 text-sm">{{ Str::limit($program->description, 150) }}</p>
                    </div>
                </div>

                {{-- Statistics --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-emerald-50 rounded-xl p-4 text-center border border-emerald-100">
                        <i class="fas fa-hand-holding-heart text-emerald-600 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600 mb-1">Total Terkumpul</p>
                        <p class="text-xl font-bold text-emerald-700">{{ $program->formatted_total_collected }}</p>
                    </div>
                    
                    {{--
                    <div class="bg-teal-50 rounded-xl p-4 text-center border border-teal-100">
                        <i class="fas fa-bullseye text-teal-600 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600 mb-1">Target</p>
                        <p class="text-xl font-bold text-teal-700">{{ $program->formatted_total_target }}</p>
                    </div>
                    --}}
                    
                    <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-100">
                        <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600 mb-1">Jumlah Donatur</p>
                        @php
                            $donorCount = \App\Models\ZakatPayment::where('program_id', $program->id)
                                ->where('status', 'completed')
                                ->distinct('muzakki_id')
                                ->count('muzakki_id');
                        @endphp
                        <p class="text-xl font-bold text-blue-700">{{ number_format($donorCount) }} Orang</p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700">Progress</span>
                        <span class="text-sm font-bold text-emerald-600">100%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-4 rounded-full transition-all duration-1000 ease-out animate-pulse" 
                             style="width: 100%"></div>
                    </div>
                </div>

                {{-- Thank You Message --}}
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-6 border border-emerald-100">
                    <h3 class="font-bold text-gray-800 mb-2 flex items-center">
                        <i class="fas fa-heart text-red-500 mr-2"></i>
                        Terima Kasih Para Donatur
                    </h3>
                    <p class="text-gray-700 text-sm leading-relaxed">
                        Jazakumullahu khairan katsiran kepada semua donatur yang telah berkontribusi untuk program ini. 
                        Semoga Allah SWT membalas kebaikan kalian dengan berlipat ganda dan menjadikan donasi ini sebagai amal jariyah yang terus mengalir pahalanya.
                    </p>
                </div>
            </div>
        </div>

        {{-- Redirect Countdown --}}
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 text-center border border-gray-200">
            <i class="fas fa-clock text-amber-500 text-3xl mb-3"></i>
            <p class="text-gray-700 mb-2">Anda akan diarahkan ke program lain dalam</p>
            <div class="text-5xl font-bold text-emerald-600 mb-2" id="countdown">10</div>
            <p class="text-sm text-gray-500">detik</p>
            <button onclick="window.location.href='{{ route('program') }}'" 
                    class="mt-4 text-emerald-600 hover:text-emerald-700 font-semibold text-sm underline">
                Lihat Program Sekarang
            </button>
        </div>

        {{-- Recommended Programs --}}
        @if($recommendedPrograms->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-hand-holding-usd text-emerald-600 mr-2"></i>
                Program Lain yang Membutuhkan
            </h3>
            <p class="text-gray-600 text-sm mb-6">Masih banyak program lain yang membutuhkan bantuan Anda</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($recommendedPrograms as $recProgram)
                <a href="{{ route('guest.payment.create', ['program_id' => $recProgram->id]) }}" 
                   class="block bg-gray-50 rounded-xl overflow-hidden hover:shadow-md transition-all border border-gray-200 hover:border-emerald-300 group">
                    <img src="{{ $recProgram->image_url }}" 
                         alt="{{ $recProgram->name }}"
                         class="w-full h-32 object-cover"
                         onerror="this.src='{{ asset('img/masjid.webp') }}'">
                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 mb-2 text-sm group-hover:text-emerald-600 transition-colors">
                            {{ Str::limit($recProgram->name, 40) }}
                        </h4>
                        <div class="mb-2">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Terkumpul</span>
                                <span class="font-semibold">{{ number_format($recProgram->progress_percentage, 0) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-emerald-500 h-1.5 rounded-full" 
                                     style="width: {{ min(100, $recProgram->progress_percentage) }}%"></div>
                            </div>
                        </div>
                        {{-- <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-600">Target:</span>
                            <span class="font-semibold text-emerald-600">{{ $recProgram->formatted_total_target }}</span>
                        </div> --}}
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Countdown Script --}}
<script>
    let countdown = 10;
    const countdownEl = document.getElementById('countdown');
    
    const interval = setInterval(() => {
        countdown--;
        countdownEl.textContent = countdown;
        
        if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = '{{ route('program') }}';
        }
    }, 1000);
</script>
@endsection
