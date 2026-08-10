import re

with open("resources/views/components/sidebar.blade.php", "r") as f:
    content = f.read()

replacements = {
    'bi bi-archive-fill': 'fa-solid fa-box-archive',
    'bi bi-arrow-down-circle-fill': 'fa-solid fa-arrow-turn-down',
    'bi bi-arrow-up-circle-fill': 'fa-solid fa-arrow-turn-up',
    'bi bi-file-earmark-richtext': 'fa-solid fa-file-lines',
    'bi bi-file-earmark-text': 'fa-solid fa-file-invoice',
    'bi bi-megaphone': 'fa-solid fa-bullhorn',
    'bi bi-grid-fill': 'fa-solid fa-table-cells-large',
}

for old, new in replacements.items():
    content = content.replace(old, new)

with open("resources/views/components/sidebar.blade.php", "w") as f:
    f.write(content)
