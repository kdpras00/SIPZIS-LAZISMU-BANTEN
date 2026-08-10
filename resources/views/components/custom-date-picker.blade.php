@props([
    'name' => '',
    'id' => '',
    'value' => '',
    'placeholder' => 'dd/mm/yyyy',
    'class' => '',
    'onChange' => '',
])

@php
    $initialDisplay = '';
    if ($value) {
        try {
            $initialDisplay = \Carbon\Carbon::parse($value)->format('d/m/Y');
        } catch (\Exception $e) {
            $initialDisplay = $value;
        }
    }
@endphp

<div x-data="{
    open: false,
    dateValue: '{{ $value }}',
    displayValue: '{{ $initialDisplay }}',
    currentYear: {{ $value ? \Carbon\Carbon::parse($value)->format('Y') : date('Y') }},
    currentMonth: {{ $value ? (\Carbon\Carbon::parse($value)->format('n') - 1) : (date('n') - 1) }},
    monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
    dayNames: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
    daysInMonth: [],
    blankDays: [],

    init() {
        this.renderCalendar();
    },

    renderCalendar() {
        const daysInMonth = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        const firstDayOfWeek = new Date(this.currentYear, this.currentMonth, 1).getDay();

        this.blankDays = Array.from({ length: firstDayOfWeek });
        this.daysInMonth = Array.from({ length: daysInMonth }, (_, i) => i + 1);
    },

    prevMonth() {
        if (this.currentMonth === 0) {
            this.currentMonth = 11;
            this.currentYear--;
        } else {
            this.currentMonth--;
        }
        this.renderCalendar();
    },

    nextMonth() {
        if (this.currentMonth === 11) {
            this.currentMonth = 0;
            this.currentYear++;
        } else {
            this.currentMonth++;
        }
        this.renderCalendar();
    },

    selectDate(day) {
        const m = String(this.currentMonth + 1).padStart(2, '0');
        const d = String(day).padStart(2, '0');
        this.dateValue = `${this.currentYear}-${m}-${d}`;
        this.displayValue = `${d}/${m}/${this.currentYear}`;
        this.open = false;

        const input = this.$refs.hiddenInput;
        if (input) {
            input.value = this.dateValue;
            this.$nextTick(() => {
                input.dispatchEvent(new Event('change', { bubbles: true }));
                @if($onChange)
                    {{ $onChange }};
                @endif
            });
        }
    },

    clearDate() {
        this.dateValue = '';
        this.displayValue = '';
        this.open = false;

        const input = this.$refs.hiddenInput;
        if (input) {
            input.value = '';
            this.$nextTick(() => {
                input.dispatchEvent(new Event('change', { bubbles: true }));
                @if($onChange)
                    {{ $onChange }};
                @endif
            });
        }
    },

    setToday() {
        const today = new Date();
        this.currentYear = today.getFullYear();
        this.currentMonth = today.getMonth();
        this.renderCalendar();
        this.selectDate(today.getDate());
    },

    isSelected(day) {
        if (!this.dateValue) return false;
        const m = String(this.currentMonth + 1).padStart(2, '0');
        const d = String(day).padStart(2, '0');
        return this.dateValue === `${this.currentYear}-${m}-${d}`;
    },

    isToday(day) {
        const today = new Date();
        return day === today.getDate() && 
               this.currentMonth === today.getMonth() && 
               this.currentYear === today.getFullYear();
    }
}" 
@click.outside="open = false" 
class="relative w-full {{ $class }}">

    
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" x-ref="hiddenInput" :value="dateValue">

    
    <button type="button" 
        @click="open = !open"
        :class="open ? 'border-[#c2410c] ring-2 ring-[#c2410c]/10' : 'border-[#e8e0d6] hover:border-gray-300'"
        class="w-full h-11 px-3.5 rounded-xl border bg-white text-xs font-medium text-[#1c0f0a] transition-colors duration-150 flex items-center justify-between outline-none cursor-pointer text-left relative">
        <span class="truncate" x-text="displayValue || '{{ $placeholder }}'" :class="!dateValue ? 'text-gray-400' : 'text-[#1c0f0a]'">{{ $initialDisplay ?: $placeholder }}</span>
        <div class="pointer-events-none flex items-center text-[#c2410c]">
            <i class="bi bi-calendar3 text-xs"></i>
        </div>
    </button>

    
    <div x-show="open" 
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        style="display: none;"
        class="absolute z-[100] right-0 top-full mt-1.5 w-72 bg-white rounded-2xl border border-[#e8e0d6] shadow-2xl p-4 text-[#1c0f0a]">
        
        
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-[#f0ece6]">
            <button type="button" @click="prevMonth()" class="p-1.5 rounded-lg hover:bg-orange-50 text-[#8b7e74] hover:text-[#c2410c] transition-colors">
                <i class="bi bi-chevron-left text-xs"></i>
            </button>
            <span class="text-xs font-bold" style="color: #1c0f0a;" x-text="`${monthNames[currentMonth]} ${currentYear}`"></span>
            <button type="button" @click="nextMonth()" class="p-1.5 rounded-lg hover:bg-orange-50 text-[#8b7e74] hover:text-[#c2410c] transition-colors">
                <i class="bi bi-chevron-right text-xs"></i>
            </button>
        </div>

        
        <div class="grid grid-cols-7 gap-1 text-center mb-1">
            <template x-for="day in dayNames" :key="day">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#8b7e74]" x-text="day"></span>
            </template>
        </div>

        
        <div class="grid grid-cols-7 gap-1 text-center">
            
            <template x-for="(blank, index) in blankDays" :key="index">
                <div class="h-8"></div>
            </template>

            
            <template x-for="day in daysInMonth" :key="day">
                <button type="button" 
                    @click="selectDate(day)"
                    :class="{
                        'bg-[#c2410c] text-white font-bold shadow-xs': isSelected(day),
                        'text-[#c2410c] bg-orange-50 font-bold': isToday(day) && !isSelected(day),
                        'text-[#1c0f0a] hover:bg-orange-50/70 hover:text-[#c2410c]': !isSelected(day) && !isToday(day)
                    }"
                    class="h-8 w-full rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer">
                    <span x-text="day"></span>
                </button>
            </template>
        </div>

        
        <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-[#f0ece6] text-xs">
            <button type="button" @click="clearDate()" class="font-semibold text-[#8b7e74] hover:text-red-600 transition-colors">
                Bersihkan
            </button>
            <button type="button" @click="setToday()" class="font-bold text-[#c2410c] hover:underline transition-colors">
                Hari Ini
            </button>
        </div>
    </div>
</div>
