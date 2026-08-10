with open("resources/views/components/two-factor-reminder.blade.php", "r") as f:
    content = f.read()

import re
content = re.sub(r'<script>.*?</script>', """<script>
        document.addEventListener('DOMContentLoaded', function() {
            const reminder = document.getElementById('security-reminder');
            const closeBtn = document.getElementById('close-reminder');
            // Use session ID to ensure reminder resets on new login/account switch
            const storageKey = 'security_reminder_dismissed_{{ session()->getId() }}';
            
            // Check if dismissed in this specific session
            if (!sessionStorage.getItem(storageKey)) {
                reminder.style.display = 'flex';
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Fade out animation
                    reminder.style.transition = 'opacity 0.3s ease';
                    reminder.style.opacity = '0';
                    setTimeout(() => {
                        reminder.style.display = 'none';
                        sessionStorage.setItem(storageKey, 'true');
                    }, 300);
                });
            }
        });
    </script>""", content, flags=re.DOTALL)

with open("resources/views/components/two-factor-reminder.blade.php", "w") as f:
    f.write(content)
