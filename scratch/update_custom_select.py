with open("resources/views/components/custom-select.blade.php", "r") as f:
    content = f.read()

# Add search to x-data
x_data_old = "options: {{ json_encode($formattedOptions) }},"
x_data_new = """options: {{ json_encode($formattedOptions) }},
    search: '',
    get filteredOptions() {
        if (this.search === '') return this.options;
        return this.options.filter(opt => opt.label.toLowerCase().includes(this.search.toLowerCase()));
    },"""
content = content.replace(x_data_old, x_data_new)

# Add watch to open
open_watch = """x-data="{"""
open_watch_new = """x-data="{"""
# Wait, let's just add x-init="$watch('open', value => { if(value) setTimeout(() => $refs.searchInput.focus(), 100) })" to the div
div_tag = """class="relative {{ $variant === 'pill' ? 'inline-block' : 'w-full' }} {{ $class }}"""
div_tag_new = """x-init="$watch('open', value => { if(value) { search = ''; setTimeout(() => $refs.searchInput.focus(), 100); } })"
class="relative {{ $variant === 'pill' ? 'inline-block' : 'w-full' }} {{ $class }}"""
content = content.replace(div_tag, div_tag_new)

# Replace the dropdown div content to include the search input
dropdown_start = """class="absolute z-[100] left-0 top-full mt-1 min-w-[130px] w-full bg-white rounded-2xl border border-[#e8e0d6] shadow-xl py-1.5 max-h-60 overflow-y-auto">"""
search_html = """class="absolute z-[100] left-0 top-full mt-1 min-w-[130px] w-full bg-white rounded-2xl border border-[#e8e0d6] shadow-xl py-1.5 max-h-60 overflow-y-auto">
        
        <div class="px-2 pb-2 sticky top-0 bg-white border-b border-[#e8e0d6] z-10 mb-1" @click.stop>
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" x-model="search" placeholder="Cari..." 
                       class="w-full text-xs border border-gray-200 rounded-lg pl-8 pr-3 py-1.5 focus:border-[#c2410c] focus:outline-none focus:ring-1 focus:ring-[#c2410c] transition-colors"
                       x-ref="searchInput">
            </div>
        </div>"""
content = content.replace(dropdown_start, search_html)

# Change x-for loop to use filteredOptions
x_for_old = """<template x-for="opt in options" :key="opt.value">"""
x_for_new = """<template x-for="opt in filteredOptions" :key="opt.value">"""
content = content.replace(x_for_old, x_for_new)

# Also fix the chevron icon which is still using bi bi-chevron-down
content = content.replace('bi bi-chevron-down', 'fa-solid fa-chevron-down')
# Fix bi bi-check2
content = content.replace('bi bi-check2', 'fa-solid fa-check')

with open("resources/views/components/custom-select.blade.php", "w") as f:
    f.write(content)
