import os

def remove_include(filepath):
    with open(filepath, 'r') as f:
        content = f.read()
    content = content.replace("@include('components.two-factor-reminder')", "")
    with open(filepath, 'w') as f:
        f.write(content)
    print(f"Removed from {filepath}")

remove_include('resources/views/layouts/app.blade.php')
remove_include('resources/views/layouts/main.blade.php')
