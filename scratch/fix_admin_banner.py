with open("resources/views/dashboard/admin.blade.php", "r") as f:
    content = f.read()

content = content.replace("@include('components.two-factor-reminder')", "")

wrapper = '<div class="w-full mx-auto px-4 sm:px-6 py-5" style="max-width: 1280px;">'
new_wrapper = wrapper + '\n    @include(\'components.two-factor-reminder\')'

content = content.replace(wrapper, new_wrapper)

with open("resources/views/dashboard/admin.blade.php", "w") as f:
    f.write(content)

with open("resources/views/dashboard/muzakki.blade.php", "r") as f:
    content = f.read()

content = content.replace("@include('components.two-factor-reminder')", "")

wrapper2 = '<div class="px-4 sm:px-6 py-5 w-full mx-auto" style="max-width: 1280px;">'
new_wrapper2 = wrapper2 + '\n    @include(\'components.two-factor-reminder\')'

content = content.replace(wrapper2, new_wrapper2)

with open("resources/views/dashboard/muzakki.blade.php", "w") as f:
    f.write(content)
