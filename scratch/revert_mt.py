with open("resources/views/components/two-factor-reminder.blade.php", "r") as f:
    content = f.read()

old_div = '<div id="security-reminder" class="max-w-7xl mx-auto mt-24 md:mt-6 mb-6 bg-[#fff7ed] border border-[#ffedd5] rounded-2xl p-4 md:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm mx-4 md:mx-auto"'
new_div = '<div id="security-reminder" class="mb-6 bg-[#fff7ed] border border-[#ffedd5] rounded-2xl p-4 md:p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm"'

content = content.replace(old_div, new_div)

with open("resources/views/components/two-factor-reminder.blade.php", "w") as f:
    f.write(content)
