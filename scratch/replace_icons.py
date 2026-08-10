import re

with open("resources/views/components/sidebar.blade.php", "r") as f:
    content = f.read()

replacements = {
    'bi bi-grid-1x2-fill': 'fa-solid fa-gauge',
    'bi bi-people-fill': 'fa-solid fa-users',
    'bi bi-person-hearts': 'fa-solid fa-hand-holding-heart',
    'bi bi-credit-card-fill': 'fa-solid fa-wallet',
    'bi bi-box2-heart-fill': 'fa-solid fa-hand-holding-dollar',
    'bi bi-file-earmark-text-fill': 'fa-solid fa-file-invoice-dollar',
    'bi bi-box-seam-fill': 'fa-solid fa-boxes-stacked',
    'bi bi-megaphone-fill': 'fa-solid fa-bullhorn',
    'bi bi-newspaper': 'fa-solid fa-newspaper',
    'bi bi-journal-text': 'fa-solid fa-book-open',
    'bi bi-receipt': 'fa-solid fa-receipt',
    'bi bi-file-text-fill': 'fa-solid fa-file-contract',
    'bi bi-shield-lock-fill': 'fa-solid fa-shield-halved',
    'bi bi-bank': 'fa-solid fa-building-columns',
    'bi bi-gear-fill': 'fa-solid fa-gear',
    'bi bi-door-closed': 'fa-solid fa-arrow-right-from-bracket',
    'bi bi-heart-fill': 'fa-solid fa-heart'
}

for old, new in replacements.items():
    content = content.replace(old, new)

with open("resources/views/components/sidebar.blade.php", "w") as f:
    f.write(content)
