with open("resources/views/payments/guest-summary.blade.php", "r") as f:
    content = f.read()

import re

old_js = """                leavePageButton.addEventListener('click', function() {
                    fetch('{{ route('guest.payment.leavePage', $payment->payment_code) }}', {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    });
                    Swal.fire({"""

new_js = """                leavePageButton.addEventListener('click', function() {
                    Swal.fire({"""

content = content.replace(old_js, new_js)

with open("resources/views/payments/guest-summary.blade.php", "w") as f:
    f.write(content)
