with open("resources/views/payments/guest-create.blade.php", "r") as f:
    content = f.read()

# First, add ID to the checkbox
old_checkbox = '<input type="checkbox" name="is_anonymous" value="1" class="sr-only peer">'
new_checkbox = '<input type="checkbox" name="is_anonymous" id="toggle_anonymous" value="1" class="sr-only peer">'
content = content.replace(old_checkbox, new_checkbox)

# Then, add the JS logic inside DOMContentLoaded
js_logic = """
            // Hamba Allah Toggle Logic
            const toggleAnonymous = document.getElementById('toggle_anonymous');
            const donorNameInput = document.getElementById('donor_name');
            let previousName = '';

            if (toggleAnonymous && donorNameInput) {
                toggleAnonymous.addEventListener('change', function() {
                    if (this.checked) {
                        previousName = donorNameInput.value;
                        donorNameInput.value = 'Hamba Allah';
                        donorNameInput.readOnly = true;
                        donorNameInput.classList.add('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                    } else {
                        donorNameInput.value = previousName !== 'Hamba Allah' ? previousName : '';
                        donorNameInput.readOnly = false;
                        donorNameInput.classList.remove('bg-gray-100', 'text-gray-500', 'cursor-not-allowed');
                    }
                });
            }
"""

# Insert JS after "// Initialize intl-tel-input" or similar
target_line = "document.addEventListener('DOMContentLoaded', function() {"
content = content.replace(target_line, target_line + js_logic)

with open("resources/views/payments/guest-create.blade.php", "w") as f:
    f.write(content)
