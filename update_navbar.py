import re

with open("resources/views/partials/navbarHome.blade.php", "r", encoding="utf-8") as f:
    text = f.read()

# 1. Desktop Title
text = re.sub(
    r'<h1 style="[^"]*linear-gradient[^"]*"[^>]*>\s*SIPZIS\s*</h1>',
    r'<h1 style="font-family: \'Poppins\', sans-serif; font-size: 1.5rem; letter-spacing: 0.1em; font-weight: 800;" id="navbar-title" class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-orange-400">SIPZIS</h1>',
    text
)

# 2. Desktop Links Default Text Color
text = re.sub(r'text-white hover:border-b-2 hover:border-white', r'text-gray-800 hover:border-b-2 hover:border-orange-600 hover:text-orange-600', text)

# 3. Mobile Hamburger Button
text = text.replace('id="mobile-menu-button" class="text-white hover:text-orange-200', 'id="mobile-menu-button" class="text-gray-800 hover:text-orange-600')

# 4. Mobile Menu Container
text = text.replace('bg-orange-800 bg-opacity-95 z-40 hidden', 'bg-white bg-opacity-[0.98] z-40 hidden')
text = text.replace('border-b border-orange-700', 'border-b border-gray-200')
text = text.replace('id="close-mobile-menu" class="text-white hover:text-orange-200', 'id="close-mobile-menu" class="text-gray-800 hover:text-orange-600')

# 5. Mobile Menu Links
text = text.replace('text-white hover:bg-orange-700', 'text-gray-800 hover:bg-orange-50 hover:text-orange-600')
text = text.replace('border-t border-orange-700', 'border-t border-gray-200')

# 6. Auth buttons
# Mobile auth specific
text = text.replace('border-2 border-white rounded-lg hover:bg-orange-700 transition duration-300 text-center mx-4 mb-2" style="border: 2px solid white;"', 'border-2 border-orange-600 text-orange-600 rounded-lg hover:bg-orange-50 hover:text-orange-700 transition duration-300 text-center mx-4 mb-2"')
# Desktop Auth admin
text = text.replace('text-white hover:text-orange-200 transition duration-300 font-medium"', 'text-gray-800 hover:text-orange-600 transition duration-300 font-medium"')
text = text.replace('bg-white text-orange-800 px-4 py-2 rounded-full font-medium hover:bg-orange-100', 'bg-orange-600 text-white px-4 py-2 rounded-full font-medium hover:bg-orange-700')
# Desktop Auth public
text = text.replace('text-white border border-white px-4 py-2 rounded-full hover:bg-white hover:text-orange-800', 'text-orange-600 border border-orange-600 px-4 py-2 rounded-full hover:bg-orange-50 hover:text-orange-700')

# 7. JavaScript for scroll
js_replace = """
        // Fungsi untuk membuat navbar transparan (putih dengan efek blur)
        function setNavbarTransparent() {
            if (!isTransparent) {
                navbar.classList.remove('bg-white', 'shadow-md');
                navbar.classList.add('bg-white/90', 'backdrop-blur-md');
                isTransparent = true;
            }
        }

        // Fungsi untuk membuat navbar solid (putih solid)
        function setNavbarSolid() {
            if (isTransparent) {
                navbar.classList.remove('bg-transparent', 'bg-white/90', 'backdrop-blur-md');
                navbar.classList.add('bg-white', 'shadow-md');
                isTransparent = false;
            }
        }
"""
text = re.sub(r'// Fungsi untuk membuat navbar transparan[\s\S]*?isTransparent = false;\s*\}[\s]*\}', js_replace, text)

# JS final fallback logic when no home section
text = re.sub(
    r'// Jika tidak ada elemen beranda, buat navbar selalu solid[\s\S]*?\}\);[\s]*\}',
    r"// Jika tidak ada elemen beranda, buat navbar selalu solid\n        navbar.classList.remove('bg-transparent');\n        navbar.classList.add('bg-white', 'shadow-md');\n    }",
    text
)

# 8. CSS styling
css_replace = """<style>
    /* Mengubah warna hover tautan menjadi orange */
    .navbar-link:hover {
        color: #ea580c !important;
        border-bottom: 2px solid #ea580c;
    }

    /* Warna teks default untuk navbar solid / transparan sama-sama abu gelap */
    .bg-white .navbar-link, .bg-white\\/90 .navbar-link {
        color: #1f2937;
    }

    .navbar-link.border-orange-600 {
        color: #ea580c !important;
        border-color: #ea580c !important;
    }
"""
text = re.sub(r'<style>\s*/\* Mengubah warna hover tautan menjadi putih[\s\S]*?\.navbar-link\s*\{\s*color:\s*white;\s*\}', css_replace, text)

with open("resources/views/partials/navbarHome.blade.php", "w", encoding="utf-8") as f:
    f.write(text)

