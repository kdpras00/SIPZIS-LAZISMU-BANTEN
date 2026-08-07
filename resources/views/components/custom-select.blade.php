@props([
    'name' => '',
    'id' => '',
    'options' => [],
    'selected' => '',
    'placeholder' => 'Pilih...',
    'required' => false,
    'class' => '',
    'onChange' => '',
    'variant' => 'default', // 'default' or 'pill'
    'icon' => '',
])

@php
    $formattedOptions = [];
    foreach ($options as $key => $val) {
        if (is_array($val)) {
            $formattedOptions[] = $val;
        } else {
            $formattedOptions[] = ['value' => (string)$key, 'label' => (string)$val];
        }
    }
    
    $initialLabel = '';
    foreach ($formattedOptions as $opt) {
        if ((string)$opt['value'] === (string)$selected) {
            $initialLabel = $opt['label'];
            break;
        }
    }
@endphp

<div x-data="{
    open: false,
    selectedValue: '{{ $selected }}',
    selectedLabel: '{{ addslashes($initialLabel) }}',
    placeholder: '{{ addslashes($placeholder) }}',
    options: {{ json_encode($formattedOptions) }},
    selectOption(opt) {
        this.open = false;
        this.selectedValue = opt.value;
        this.selectedLabel = opt.value !== '' ? opt.label : '';
        const input = this.$refs.hiddenInput;
        if (input) {
            input.value = opt.value;
            this.$nextTick(() => {
                input.dispatchEvent(new Event('change', { bubbles: true }));
                @if($onChange)
                    {{ $onChange }};
                @endif
            });
        }
    }
}" 
@click.outside="open = false" 
class="relative {{ $variant === 'pill' ? 'inline-block' : 'w-full' }} {{ $class }}">

    <!-- Hidden native select for form submission and JS compatibility -->
    <select name="{{ $name }}" id="{{ $id }}" x-ref="hiddenInput" class="sr-only" {{ $required ? 'required' : '' }}>
        <option value="">{{ $placeholder }}</option>
        @foreach($formattedOptions as $opt)
            <option value="{{ $opt['value'] }}" {{ (string)$opt['value'] === (string)$selected ? 'selected' : '' }}>{{ $opt['label'] }}</option>
        @endforeach
    </select>

    <!-- Trigger Button -->
    @if($variant === 'pill')
        <button type="button" 
            @click="open = !open"
            class="flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-[#1c0f0a] hover:bg-orange-50/50 rounded-l-xl transition-colors cursor-pointer outline-none relative">
            @if($icon)
                <i class="{{ $icon }} text-xs text-[#8b7e74] pointer-events-none"></i>
            @endif
            <span class="truncate" x-text="selectedLabel || placeholder" :class="!selectedValue ? 'text-gray-400' : 'text-[#1c0f0a]'">{{ $initialLabel ?: $placeholder }}</span>
            <i class="bi bi-chevron-down text-[10px] text-[#8b7e74] pointer-events-none ml-0.5"></i>
        </button>
    @else
        <button type="button" 
            @click="open = !open"
            :class="open ? 'border-[#c2410c] ring-2 ring-[#c2410c]/10' : 'border-[#e8e0d6] hover:border-gray-300'"
            class="w-full h-11 pl-4 pr-9 rounded-xl border bg-white text-xs font-medium text-[#1c0f0a] transition-colors duration-150 flex items-center justify-between outline-none cursor-pointer text-left relative">
            <span class="truncate" x-text="selectedLabel || placeholder" :class="!selectedValue ? 'text-gray-400' : 'text-[#1c0f0a]'">{{ $initialLabel ?: $placeholder }}</span>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#8b7e74]">
                <i class="bi bi-chevron-down text-xs"></i>
            </div>
        </button>
    @endif

    <!-- Dropdown Menu Popup -->
    <div x-show="open" 
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        style="display: none;"
        class="absolute z-[100] left-0 top-full mt-1 min-w-[130px] w-full bg-white rounded-2xl border border-[#e8e0d6] shadow-xl py-1.5 max-h-60 overflow-y-auto">
        
        <div @click="selectOption({value: '', label: ''})"
            :class="!selectedValue ? 'bg-[#fff7ed] text-[#c2410c] font-semibold' : 'text-[#1c0f0a] hover:bg-orange-50/60'"
            class="mx-1.5 px-3.5 py-2.5 rounded-xl text-xs cursor-pointer transition-colors flex items-center justify-between select-none">
            <span x-text="placeholder"></span>
            <i x-show="!selectedValue" class="bi bi-check2 text-sm text-[#c2410c]"></i>
        </div>

        <template x-for="opt in options" :key="opt.value">
            <template x-if="opt.value !== ''">
                <div @click="selectOption(opt)"
                    :class="opt.value === selectedValue ? 'bg-[#fff7ed] text-[#c2410c] font-semibold' : 'text-[#1c0f0a] hover:bg-orange-50/60'"
                    class="mx-1.5 px-3.5 py-2.5 rounded-xl text-xs cursor-pointer transition-colors flex items-center justify-between select-none">
                    <span x-text="opt.label"></span>
                    <i x-show="opt.value === selectedValue" class="bi bi-check2 text-sm text-[#c2410c]"></i>
                </div>
            </template>
        </template>
    </div>
</div>
