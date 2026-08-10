@extends('layouts.main')

@section('title', 'Target Tercapai - ' . $program->name)

@section('navbar')
@include('partials.navbarHome')
@endsection

@section('content')
<div class="min-h-screen bg-gradient-to-b from-orange-50 via-white to-orange-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8 border-t-4 border-orange-500">
            
            <div class="relative bg-orange-500 p-8 text-center">
                
                <div class="relative z-10">
                    
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-4 shadow-lg">
                        <i class="fas fa-check-circle text-orange-500 text-5xl"></i>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                        Alhamdulillah, Target Tercapai!
                    </h1>
                    <p class="text-orange-100 text-lg">
                        Program ini telah mencapai target donasi
                    </p>
                </div>
            </div>

            
            <div class="p-8">
                <div class="flex items-start gap-4 mb-6">
                    @if($program->image_url)
                    <img src="{{ $program->image_url }}" 
                         alt="{{ $program->name }}"
                         class="w-24 h-24 object-cover rounded-xl shadow-md"
                         onerror="this.src=''">
                    @else
                    <div class="w-24 h-24 bg-gray-200 rounded-xl shadow-md flex items-center justify-center shrink-0">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $program->name }}</h2>
                        <p class="text-gray-600 text-sm">{{ Str::limit($program->description, 150) }}</p>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-orange-50 rounded-xl p-4 text-center border border-orange-100">
                        <i class="fas fa-hand-holding-heart text-orange-600 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600 mb-1">Total Terkumpul</p>
                        <p class="text-xl font-bold text-orange-700">{{ $program->formatted_total_collected }}</p>
                    </div>
                    
                    
                    
                    <div class="bg-blue-50 rounded-xl p-4 text-center border border-blue-100">
                        <i class="fas fa-users text-blue-600 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-600 mb-1">Jumlah Donatur</p>
                        @php
                            $donorCount = \App\Models\Payment::where('program_id', $program->id)
                                ->where('status', 'completed')
                                ->distinct('muzakki_id')
                                ->count('muzakki_id');
                        @endphp
                        <p class="text-xl font-bold text-blue-700">{{ number_format($donorCount) }} Orang</p>
                    </div>
                </div>

                
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-700">Progress</span>
                        <span class="text-sm font-bold text-orange-600">100%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-orange-500 h-4 rounded-full transition-all duration-1000 ease-out" 
                             style="width: 100%"></div>
                    </div>
                </div>

                
                <div class="bg-orange-50 rounded-xl p-6 border border-orange-100">
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

        
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 text-center border border-gray-200">
            <i class="fas fa-clock text-amber-500 text-3xl mb-3"></i>
            <p class="text-gray-700 mb-2">Anda akan diarahkan ke program lain dalam</p>
            <div class="text-5xl font-bold text-orange-600 mb-2" id="countdown">10</div>
            <p class="text-sm text-gray-500">detik</p>
            <button onclick="window.location.href='{{ route('program') }}'" 
                    class="mt-4 text-orange-600 hover:text-orange-700 font-semibold text-sm underline">
                Lihat Program Sekarang
            </button>
        </div>

        
        @if($recommendedPrograms->count() > 0)
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-hand-holding-usd text-orange-600 mr-2"></i>
                Program Lain yang Membutuhkan
            </h3>
            <p class="text-gray-600 text-sm mb-6">Masih banyak program lain yang membutuhkan bantuan Anda</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($recommendedPrograms as $recProgram)
                <a href="{{ route('guest.payment.create', ['program_id' => $recProgram->id]) }}" 
                   class="block bg-gray-50 rounded-xl overflow-hidden hover:shadow-md transition-all border border-gray-200 hover:border-orange-300 group">
                    @if($recProgram->image_url)
                    <img src="{{ $recProgram->image_url }}" 
                         alt="{{ $recProgram->name }}"
                         class="w-full h-32 object-cover"
                         onerror="this.src=''">
                    @else
                    <div class="w-full h-32 bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    @endif
                    <div class="p-4">
                        <h4 class="font-bold text-gray-800 mb-2 text-sm group-hover:text-orange-600 transition-colors">
                            {{ Str::limit($recProgram->name, 40) }}
                        </h4>
                        <div class="mb-2">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>Terkumpul</span>
                                <span class="font-semibold">{{ number_format($recProgram->progress_percentage, 0) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-orange-500 h-1.5 rounded-full" 
                                     style="width: {{ min(100, $recProgram->progress_percentage) }}%"></div>
                            </div>
                        </div>
                        
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

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
