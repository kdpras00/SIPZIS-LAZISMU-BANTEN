import re
import glob

def replace_in_file(filepath, old_str, new_str):
    with open(filepath, 'r') as f:
        content = f.read()
    
    if old_str in content:
        content = content.replace(old_str, new_str)
        with open(filepath, 'w') as f:
            f.write(content)
        print(f"Updated {filepath}")

# guest-summary.blade.php
old_guest = "&copy; {{ date('Y') }} SIPZIS Lazismu."
new_guest = """&copy; <span class="font-semibold text-gray-500">Created By : </span> — Kurniawan Dwi Prasetyo<br>
                    <span class="text-gray-400">Hak Cipta Dilindungi.</span>"""
replace_in_file('resources/views/payments/guest-summary.blade.php', old_guest, new_guest)

# Emails
old_email = "&copy; {{ date('Y') }} SIPZIS. All rights reserved."
new_email = """&copy; <b>Created By :</b> — Kurniawan Dwi Prasetyo<br>
            Hak Cipta Dilindungi."""

emails = [
    'resources/views/emails/auth/email-verification.blade.php',
    'resources/views/emails/auth/password-reset.blade.php',
    'resources/views/emails/donor/payment-status.blade.php'
]

for email in emails:
    replace_in_file(email, old_email, new_email)

