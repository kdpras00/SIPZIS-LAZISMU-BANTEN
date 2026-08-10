with open("app/Services/MidtransService.php", "r") as f:
    content = f.read()

content = content.replace("config('services.midtrans.server_key')", "config('midtrans.server_key')")
content = content.replace("config('services.midtrans.is_production', false)", "config('midtrans.is_production', false)")
content = content.replace("config('services.midtrans.is_sanitized', true)", "config('midtrans.is_sanitized', true)")
content = content.replace("config('services.midtrans.is_3ds', true)", "config('midtrans.is_3ds', true)")

with open("app/Services/MidtransService.php", "w") as f:
    f.write(content)
