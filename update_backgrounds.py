import os
import re

def process_tentang_berita(filepath):
    if not os.path.exists(filepath): return
    with open(filepath, "r", encoding="utf-8") as f:
        text = f.read()

    # Base wrapper
    text = text.replace('bg-gradient-to-br from-orange-900 via-orange-800 to-orange-700', 'bg-gray-50')
    
    # Masjid opacity
    text = text.replace('opacity-90\n        style="background-image: url(\'{{ asset(\'img/masjid.webp\') }}\')', 'opacity-[0.10]\n        style="background-image: url(\'{{ asset(\'img/masjid.webp\') }}\')')
    
    # Inner gradient overlays
    text = text.replace('from-orange-900/80 via-orange-800/70 to-orange-700/80', 'from-white/95 via-white/80 to-white/60')
    text = text.replace('from-black/40 via-transparent to-black/20', 'from-white/40 via-transparent to-transparent')
    
    with open(filepath, "w", encoding="utf-8") as f:
        f.write(text)
    print(f"Updated {filepath}")

def process_campaigns(filepath):
    if not os.path.exists(filepath): return
    with open(filepath, "r", encoding="utf-8") as f:
        text = f.read()
        
    text = text.replace('bg-orange-900', 'bg-gray-50')
    text = text.replace('opacity-30 mix-blend-overlay', 'opacity-10')
    text = text.replace('from-orange-950/95 via-orange-900/90 to-orange-800/80', 'from-white/95 via-white/80 to-white/60')
    text = text.replace('text-white mb-6 leading-tight drop-shadow-lg', 'text-gray-900 mb-6 leading-tight')
    text = text.replace('text-orange-50/90', 'text-gray-600')
    text = text.replace('bg-white/10 text-orange-100', 'bg-orange-100 text-orange-600')
    
    with open(filepath, "w", encoding="utf-8") as f:
        f.write(text)
    print(f"Updated {filepath}")

def process_news(filepath):
    if not os.path.exists(filepath): return
    with open(filepath, "r", encoding="utf-8") as f:
        text = f.read()
        
    text = text.replace('from-orange-600/40 via-orange-600/30 to-orange-600/40', 'from-white/60 via-white/40 to-white/60')
    text = text.replace('from-white via-orange-100 to-orange-200', 'from-orange-600 via-orange-500 to-orange-600')
    text = text.replace('text-white/95', 'text-gray-600')
    text = text.replace('bg-white/10 backdrop-blur-sm rounded-full px-6 py-2 mb-6 border border-white/20', 'bg-orange-100 backdrop-blur-sm rounded-full px-6 py-2 mb-6')
    text = text.replace('text-white/90 text-sm font-medium', 'text-orange-600 text-sm font-medium')
    
    # Fix the background image blending
    text = text.replace('style="background-image: url(\'{{ asset(\'img/masjid.webp\') }}\');', 'style="background-image: url(\'{{ asset(\'img/masjid.webp\') }}\'); opacity: 0.95;')
    
    with open(filepath, "w", encoding="utf-8") as f:
        f.write(text)
    print(f"Updated {filepath}")

def process_artikel(filepath):
    if not os.path.exists(filepath): return
    with open(filepath, "r", encoding="utf-8") as f:
        text = f.read()

    # Same background wrapper
    text = text.replace('opacity-90\n                style="background-image: url(\'{{ asset(\'img/masjid.webp\') }}\')', 'opacity-[0.10]\n                style="background-image: url(\'{{ asset(\'img/masjid.webp\') }}\')')
    text = text.replace('from-orange-900/80 via-orange-800/70 to-orange-700/80', 'from-white/95 via-white/80 to-white/60')
    text = text.replace('from-black/40 via-transparent to-black/20', 'from-white/40 via-transparent to-transparent')
    
    # Title fixes if un-commented later
    text = text.replace('text-white bg-gradient-to-r from-white', 'text-orange-800 bg-gradient-to-r from-orange-600')
    
    # Full CSS revamp
    text = text.replace('background: linear-gradient(to bottom right, #064e3b, #065f46, #047857);', 'background: #f9fafb /* gray-50 */;')
    text = text.replace('color: #166534;', 'color: #ea580c;')
    text = text.replace('background: linear-gradient(to right, #16a34a, #059669, #0d9488);', 'background: linear-gradient(to right, #ea580c, #f97316, #fb923c);')
    text = text.replace('color: #16a34a;', 'color: #ea580c;')
    text = text.replace('.artikel-card-title-link:hover {\n            color: #16a34a;\n        }', '.artikel-card-title-link:hover {\n            color: #ea580c;\n        }')
    text = text.replace('linear-gradient(to right, rgba(240, 253, 244, 0.5), rgba(236, 253, 245, 0.5), rgba(240, 253, 250, 0.5))', 'linear-gradient(to right, rgba(255, 247, 237, 0.5), rgba(255, 237, 213, 0.5), rgba(255, 247, 237, 0.5))')

    with open(filepath, "w", encoding="utf-8") as f:
        f.write(text)
    print(f"Updated {filepath}")


base = "/Applications/XAMPP/xamppfiles/htdocs/sipzisLazismu/resources/views/"
process_tentang_berita(base + "partials/tentang.blade.php")
process_tentang_berita(base + "partials/berita.blade.php")
process_campaigns(base + "campaigns/all.blade.php")
process_news(base + "news/all.blade.php")
process_artikel(base + "artikel/index.blade.php")
