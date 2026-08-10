with open("resources/views/muzakki/dashboard/index.blade.php", "r") as f:
    content = f.read()

old_content = """@section('content')
@include('components.two-factor-reminder')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">"""

new_content = """@section('content')
<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">
    @include('components.two-factor-reminder')"""

content = content.replace(old_content, new_content)

with open("resources/views/muzakki/dashboard/index.blade.php", "w") as f:
    f.write(content)

with open("resources/views/dashboard/admin.blade.php", "r") as f:
    content = f.read()

old_content = """@section('content')
@include('components.two-factor-reminder')"""

new_content = """@section('content')
<div class="px-4 sm:px-6 pt-5 w-full mx-auto" style="max-width: 1280px;">
    @include('components.two-factor-reminder')
</div>"""
# Wait admin.blade.php probably already has a main container wrapper further down? Let's check!
