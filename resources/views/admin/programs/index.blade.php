@extends('layouts.app')

@section('page-title', 'Kelola Program')

@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold mb-1" style="color: #1c0f0a;">Kelola Program</h2>
            <p class="text-sm" style="color: #8b7e74;">Daftar semua program yang tersedia di sistem</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.programs.bulk-create') }}" 
               class="inline-flex items-center px-4 py-2 font-medium rounded-xl transition-all duration-200 text-xs hover:bg-gray-50" style="border: 1px solid #e8e0d6; color: #1c0f0a; background: #fff;">
                <i class="bi bi-layers-fill mr-1.5" style="color: #c2410c;"></i> Tambah Massal
            </a>
            <a href="{{ route('admin.programs.create') }}" 
               class="inline-flex items-center px-4 py-2 text-white font-medium rounded-xl transition-colors duration-200 text-xs shadow-xs" style="background: #c2410c;">
                <i class="bi bi-plus-circle-fill mr-1.5"></i> Tambah Program
            </a>
        </div>
    </div>

    
    <div class="rounded-2xl overflow-hidden mb-6" style="background: #fff; box-shadow: 0 1px 3px rgba(28,15,10,0.04); border: 1px solid #f0ece6;">
        <div style="border-bottom: 1px solid #f0ece6;">
            <nav class="flex -mb-px overflow-x-auto" aria-label="Tabs">
                <button onclick="showTab('zakat')" 
                        id="zakat-tab" 
                        class="tab-button active px-6 py-3.5 text-xs font-semibold text-center border-b-2 border-[#c2410c] text-[#c2410c] whitespace-nowrap">
                    Zakat
                </button>
                <button onclick="showTab('infaq')" 
                        id="infaq-tab" 
                        class="tab-button px-6 py-3.5 text-xs font-semibold text-center border-b-2 border-transparent text-[#8b7e74] hover:text-[#1c0f0a] whitespace-nowrap">
                    Infaq
                </button>
                <button onclick="showTab('shadaqah')" 
                        id="shadaqah-tab" 
                        class="tab-button px-6 py-3.5 text-xs font-semibold text-center border-b-2 border-transparent text-[#8b7e74] hover:text-[#1c0f0a] whitespace-nowrap">
                    Shadaqah
                </button>
                <button onclick="showTab('pilar')" 
                        id="pilar-tab" 
                        class="tab-button px-6 py-3.5 text-xs font-semibold text-center border-b-2 border-transparent text-[#8b7e74] hover:text-[#1c0f0a] whitespace-nowrap">
                    Program Pilar
                </button>
            </nav>
        </div>

        
        <div class="p-4 sm:p-6">
            
            <div id="zakat-content" class="tab-content">
                @php
                $zakatPrograms = $groupedPrograms->filter(function($programs) {
                    return $programs->first()->category === 'zakat';
                })->flatten();
                @endphp

                @if($zakatPrograms->count() > 0)
                <div class="overflow-x-auto">
                    <table id="table-programs-zakat" class="min-w-full divide-y divide-[#f0ece6]">
                        <thead style="background: #faf8f5;">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Program</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kategori</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Target</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Terkumpul</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Progress</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#f0ece6]">
                            @foreach($zakatPrograms as $program)
                            @include('admin.programs.partials.program-row', ['program' => $program, 'categoryName' => 'Zakat'])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <i class="bi bi-grid text-4xl mb-2 block" style="color: #d1cbc4;"></i>
                    <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Belum ada program zakat yang tersedia</p>
                </div>
                @endif
            </div>

            
            <div id="infaq-content" class="tab-content hidden">
                @php
                $infaqPrograms = $groupedPrograms->filter(function($programs) {
                    return $programs->first()->category === 'infaq';
                })->flatten();
                @endphp

                @if($infaqPrograms->count() > 0)
                <div class="overflow-x-auto">
                    <table id="table-programs-infaq" class="min-w-full divide-y divide-[#f0ece6]">
                        <thead style="background: #faf8f5;">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Program</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kategori</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Target</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Terkumpul</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Progress</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#f0ece6]">
                            @foreach($infaqPrograms as $program)
                            @include('admin.programs.partials.program-row', ['program' => $program, 'categoryName' => 'Infaq'])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <i class="bi bi-grid text-4xl mb-2 block" style="color: #d1cbc4;"></i>
                    <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Belum ada program infaq yang tersedia</p>
                </div>
                @endif
            </div>

            
            <div id="shadaqah-content" class="tab-content hidden">
                @php
                $shadaqahPrograms = $groupedPrograms->filter(function($programs) {
                    return $programs->first()->category === 'shadaqah';
                })->flatten();
                @endphp

                @if($shadaqahPrograms->count() > 0)
                <div class="overflow-x-auto">
                    <table id="table-programs-shadaqah" class="min-w-full divide-y divide-[#f0ece6]">
                        <thead style="background: #faf8f5;">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Program</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kategori</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Target</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Terkumpul</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Progress</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#f0ece6]">
                            @foreach($shadaqahPrograms as $program)
                            @include('admin.programs.partials.program-row', ['program' => $program, 'categoryName' => 'Shadaqah'])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <i class="bi bi-grid text-4xl mb-2 block" style="color: #d1cbc4;"></i>
                    <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Belum ada program shadaqah yang tersedia</p>
                </div>
                @endif
            </div>

            
            <div id="pilar-content" class="tab-content hidden">
                @php
                $pilarPrograms = $groupedPrograms->filter(function($programs) {
                    return !in_array($programs->first()->category, ['zakat', 'infaq', 'shadaqah']);
                })->flatten();
                $categoryNames = [
                    'pendidikan' => 'Pendidikan',
                    'kesehatan' => 'Kesehatan',
                    'ekonomi' => 'Ekonomi',
                    'sosial-dakwah' => 'Sosial & Dakwah',
                    'kemanusiaan' => 'Kemanusiaan',
                    'lingkungan' => 'Lingkungan'
                ];
                @endphp

                @if($pilarPrograms->count() > 0)
                <div class="overflow-x-auto">
                    <table id="table-programs-pilar" class="min-w-full divide-y divide-[#f0ece6]">
                        <thead style="background: #faf8f5;">
                            <tr>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Program</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Kategori</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Target</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Terkumpul</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Progress</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Status</th>
                                <th scope="col" class="px-5 py-3.5 text-center text-xs font-semibold uppercase tracking-wider" style="color: #8b7e74;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#f0ece6]">
                            @foreach($pilarPrograms as $program)
                            @include('admin.programs.partials.program-row', ['program' => $program, 'categoryName' => $categoryNames[$program->category] ?? ucfirst($program->category)])
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <i class="bi bi-grid text-4xl mb-2 block" style="color: #d1cbc4;"></i>
                    <p class="text-sm font-semibold mb-0" style="color: #1c0f0a;">Belum ada program pilar yang tersedia</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const TAB_TABLE = {
        zakat:     '#table-programs-zakat',
        infaq:     '#table-programs-infaq',
        shadaqah:  '#table-programs-shadaqah',
        pilar:     '#table-programs-pilar',
    };

    function showTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });

        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'border-[#c2410c]', 'text-[#c2410c]');
            button.classList.add('border-transparent', 'text-[#8b7e74]');
        });

        document.getElementById(tabName + '-content').classList.remove('hidden');

        const activeTab = document.getElementById(tabName + '-tab');
        activeTab.classList.add('active', 'border-[#c2410c]', 'text-[#c2410c]');
        activeTab.classList.remove('border-transparent', 'text-[#8b7e74]');

        if (window.SipzisTable && TAB_TABLE[tabName]) {
            window.SipzisTable.initTable(TAB_TABLE[tabName]);
        }

        history.replaceState(null, '', '#' + tabName);
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.SipzisTable) {
            window.SipzisTable.initTable('#table-programs-zakat');
        }

        const hash = window.location.hash;
        if (hash) {
            const tabName = hash.substring(1);
            if (['zakat', 'infaq', 'shadaqah', 'pilar'].includes(tabName)) {
                showTab(tabName);
            }
        }
    });
</script>
@endpush
@endsection
