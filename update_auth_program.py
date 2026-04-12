import os

base = "/Applications/XAMPP/xamppfiles/htdocs/sipzisLazismu/resources/views/"

def process_program():
    filepath = base + "partials/program.blade.php"
    if not os.path.exists(filepath): return
    with open(filepath, "r", encoding="utf-8") as f:
        text = f.read()

    # Base wrapper
    text = text.replace('bg-gradient-to-br from-orange-900 via-orange-800 to-orange-700 min-h-screen', 'bg-gray-50 min-h-screen')
    
    # Masjid opacity
    text = text.replace('opacity-90\n        style="background-image: url(\'{{ asset(\'img/masjid.webp\') }}\')', 'opacity-[0.10]\n        style="background-image: url(\'{{ asset(\'img/masjid.webp\') }}\')')
    
    # Inner gradient overlays
    text = text.replace('from-orange-900/80 via-orange-800/70 to-orange-700/80', 'from-white/95 via-white/80 to-white/60')
    text = text.replace('from-black/40 via-transparent to-black/20', 'from-gray-50/40 via-transparent to-transparent')
    
    # Adjust typography from white text to gray-900/600 text
    text = text.replace('class="text-4xl md:text-5xl font-extrabold text-white mb-6"', 'class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6"')
    text = text.replace('class="text-xl text-white max-w-3xl mx-auto"', 'class="text-xl text-gray-600 max-w-3xl mx-auto"')
    
    # Adjust tab buttons container
    text = text.replace('bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/30', 'bg-gray-100/80 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200/50')

    with open(filepath, "w", encoding="utf-8") as f:
        f.write(text)
    print(f"Updated program")

def process_auth():
    for f_name in ["auth/login.blade.php", "auth/register.blade.php", "auth/forgot-password.blade.php"]:
        filepath = base + f_name
        if not os.path.exists(filepath): continue
        with open(filepath, "r", encoding="utf-8") as f:
            text = f.read()
            
        text = text.replace('bg-orange-900', 'bg-gray-50')
        
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(text)
        print(f"Updated {f_name}")

process_program()
process_auth()
