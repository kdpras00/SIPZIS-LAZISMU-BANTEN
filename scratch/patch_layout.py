import re

def patch_file(filepath, pat1, repl1, pat2, repl2):
    with open(filepath, 'r') as f:
        content = f.read()
    
    # Remove old include
    content = content.replace(pat1, repl1)
    # Insert new include
    content = content.replace(pat2, repl2)
    
    with open(filepath, 'w') as f:
        f.write(content)
    print(f"Patched {filepath}")

# For app.blade.php
pat1_app = "@include('components.two-factor-reminder')"
repl1_app = ""
pat2_app = "@yield('content')"
repl2_app = "@include('components.two-factor-reminder')\n                    @yield('content')"

patch_file('resources/views/layouts/app.blade.php', pat1_app, repl1_app, pat2_app, repl2_app)

# For main.blade.php (Guest/Mustahik layout)
pat1_main = "@include('components.two-factor-reminder')"
repl1_main = ""
pat2_main = "@yield('content')"
repl2_main = "@include('components.two-factor-reminder')\n        @yield('content')"

patch_file('resources/views/layouts/main.blade.php', pat1_main, repl1_main, pat2_main, repl2_main)
