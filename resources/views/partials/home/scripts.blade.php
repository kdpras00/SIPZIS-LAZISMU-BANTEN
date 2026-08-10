<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    
    <style>
        /* Prevent FOUC (Flash of Unstyled Content) */
        body:not(.page-loaded) .animate-fadeInUp,
        body:not(.page-loaded) .animate-fadeInDown {
            opacity: 0;
        }

        .animate-fadeInUp,
        .animate-fadeInDown {
            animation-fill-mode: both;
        }

        /* Only animate when page is loaded */
        body.page-loaded .animate-fadeInUp,
        body.page-loaded .animate-fadeInDown {
            animation-play-state: running;
            visibility: visible;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInDown {
            animation: fadeInDown 0.3s ease-out forwards;
            animation-play-state: paused;
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.3s ease-out forwards;
            animation-play-state: paused;
        }

        /* Delay classes with animation-fill-mode */
        .delay-500 {
            animation-delay: 0.5s;
        }

        .delay-700 {
            animation-delay: 0.7s;
        }

        .delay-1000 {
            animation-delay: 1s;
        }

        /* Optimize animations */
        .animate-fadeInUp,
        .animate-fadeInDown {
            will-change: opacity, transform;
        }

        /* Drag interactions */
        .cursor-grab {
            cursor: grab;
        }

        .cursor-grabbing {
            cursor: grabbing !important;
        }

        /* Respect user's motion preferences */
        @media (prefers-reduced-motion: reduce) {

            .animate-fadeInUp,
            .animate-fadeInDown {
                animation: none;
                opacity: 1;
                transform: none;
            }
        }

        /* Gradient text effect */
        .bg-clip-text {
            background-clip: text;
            -webkit-background-clip: text;
        }

        /* Glass morphism effect */
        .backdrop-blur-sm {
            backdrop-filter: blur(4px);
        }

        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Floating chat icon */
        .fixed.bottom-6.right-6 {
            right: 1.5rem !important;
            bottom: 1.5rem !important;
        }

        #chatbot-popup {
            width: 320px !important;
            max-width: 90vw;
        }

        .chat-messages-container {
            max-height: 350px;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        /* Custom scrollbar for chat */
        .chat-messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .chat-messages-container::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 10px;
        }

        .chat-messages-container::-webkit-scrollbar-thumb:hover {
            background: #a0a0a0;
        }

        /* Chat message bubbles */
        .message-user {
            align-self: flex-end;
            background-color: #ea580c;
            color: white;
            border-bottom-right-radius: 18px;
            border-bottom-left-radius: 18px;
            border-top-left-radius: 18px;
            border-top-right-radius: 4px;
        }

        .message-bot {
            align-self: flex-start;
            background-color: #F3F4F6;
            color: #1F2937;
            border-bottom-right-radius: 18px;
            border-bottom-left-radius: 18px;
            border-top-right-radius: 18px;
            border-top-left-radius: 4px;
        }

        .message-bubble {
            max-width: 85%;
            padding: 10px 14px;
            margin-bottom: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.3s ease-out forwards;
        }

        /* Add bounce animation for arrows */
        @keyframes bounce-x {

            0%,
            100% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(4px);
            }
        }

        .animate-bounce-x {
            animation: bounce-x 1s infinite;
        }

        /* Enhanced card hover effect */
        .card-hover:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Progress bar animation */
        .progress-bar {
            transition: width 0.6s ease-in-out;
        }

        /* Scroll snap alignment for better mobile experience */
        .snap-start {
            scroll-snap-align: center;
        }

        /* Hide scrollbar for sliders but keep functionality */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Responsive adjustments for mobile */
        @media (max-width: 767px) {
            .group:hover .opacity-0 {
                opacity: 0 !important;
            }
        }
    </style>

    
    <script>
        // Chatbot functionality
        document.addEventListener("DOMContentLoaded", () => {
            // Initialize chatbot directly (no need to wait for Puter.js)
            function initializeChatbot() {
                const sendBtn = document.getElementById("send-btn");
                const input = document.getElementById("chat-input");
                const messages = document.getElementById("chat-messages");
                const chatbotBtn = document.getElementById('chatbot-button');
                const chatbotPopup = document.getElementById('chatbot-popup');

                // If any required element is missing, exit
                if (!sendBtn || !input || !messages || !chatbotBtn || !chatbotPopup) {
                    console.error('Chatbot elements not found');
                    return;
                }

                let isFirstOpen = true;

                // Improved scroll to bottom function with delay for better rendering
                function scrollToBottom() {
                    setTimeout(() => {
                        messages.scrollTop = messages.scrollHeight;
                    }, 100);
                }

                chatbotBtn.addEventListener('click', () => {
                    // Toggle tampilan chatbot (muncul/sembunyi)
                    const isHidden = chatbotPopup.classList.toggle('hidden');

                    if (!isHidden) {
                        // Chatbot baru dibuka
                        if (isFirstOpen) {
                            messages.innerHTML = '';
                            appendMessage(
                                `
                <div class="space-y-2 text-justify">
                    <p>Selamat datang di <strong>Lazismu Banten</strong>! 👋</p>
                    <p class="text-[0.95em] opacity-90">Saya siap membantu Anda dengan pertanyaan seputar <em>zakat</em>, cara pembayaran, program yang tersedia, dan informasi lainnya.</p>
                    <p class="text-[0.95em] font-medium mt-2">Apa yang ingin Anda tanyakan hari ini?</p>
                </div>
                `,
                                "bot"
                            );
                            isFirstOpen = false;
                        }

                        // Fokuskan ke input setelah chatbot muncul
                        setTimeout(() => input.focus(), 500);

                        // ❌ Tidak scroll otomatis ke bawah
                        // Jadi pesan selamat datang tetap kelihatan penuh
                    } else {
                        // Chatbot ditutup → bisa tambahkan efek jika mau
                        input.blur(); // opsional, supaya keyboard tertutup di mobile
                    }
                });

                // Function to append messages in HTML format
                function appendMessage(htmlContent, sender) {
                    const div = document.createElement("div");
                    div.classList.add("message-bubble", "animate-fadeInUp");

                    if (sender === "user") {
                        div.classList.add("message-user");
                    } else {
                        div.classList.add("message-bot");
                    }

                    // Set the HTML content properly
                    div.innerHTML = htmlContent;
                    messages.appendChild(div);

                    // Auto-scroll to bottom with improved behavior
                    scrollToBottom();
                }

                function formatResponseToHtml(text) {
                    if (!text || typeof text !== 'string') {
                        return '<p>⚠️ Respon tidak valid dari AI. Silakan coba lagi.</p>';
                    }

                    try {
                        let html = marked.parse(text);

                        html = html.replace(/<h1>/g, '<h1 class="text-lg font-bold mb-2">')
                            .replace(/<h2>/g, '<h2 class="text-base font-semibold mb-1">')
                            .replace(/<ul>/g, '<ul class="list-disc pl-5 space-y-1">')
                            .replace(/<p>/g, '<p class="mb-2 leading-relaxed">');
                        return html;
                    } catch (error) {
                        console.error('Error parsing markdown:', error);
                        return '<p class="mb-2 leading-relaxed">' + text.replace(/</g, '&lt;').replace(/>/g,
                            '&gt;') + '</p>';
                    }
                }

                // Send message handler
                async function sendMessage() {
                    const userText = input.value.trim();
                    if (!userText) return;

                    // Display user message
                    appendMessage(`<p>${userText.replace(/\n/g, '<br>')}</p>`, "user");
                    input.value = "";
                    // Reset textarea height
                    input.style.height = 'auto';
                    input.style.height = input.scrollHeight + 'px';

                    // Show typing indicator
                    const loadingMsg = document.createElement("div");
                    loadingMsg.id = "typing-indicator";
                    loadingMsg.classList.add("message-bubble", "message-bot");
                    loadingMsg.innerHTML = '<p class="text-gray-400 italic text-xs">Mengetik...</p>';
                    messages.appendChild(loadingMsg);

                    // Scroll to show typing indicator
                    scrollToBottom();

                    try {
                        // Check if we should use custom responses for common zakat questions
                        let replyHtml = '';

                        if (userText.toLowerCase().includes('zakat') && userText.toLowerCase().includes(
                                'apa')) {
                            replyHtml = `
                            <p class="mb-2">Zakat adalah rukun Islam kelima yang wajib dilaksanakan oleh setiap Muslim yang memenuhi syarat.</p>
                            <p class="mb-2">Zakat berasal dari bahasa Arab yang berarti "bersih" atau "tumbuh". Zakat merupakan bentuk ibadah sekaligus sistem ekonomi dalam Islam yang bertujuan untuk membersihkan harta dan menyejahterakan umat.</p>
                            <p class="mb-2"><strong>Syarat wajib zakat:</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Muslim</li>
                                <li>Baligh (dewasa)</li>
                                <li>Merdeka (bukan budak)</li>
                                <li>Kaya (melebihi nisab)</li>
                                <li>Memiliki harta selama satu tahun (haul)</li>
                            </ul>
                        `;
                        } else if (userText.toLowerCase().includes('bayar') || userText.toLowerCase().includes(
                                'cara')) {
                            replyHtml = `
                            <p class="mb-2">Untuk membayar zakat melalui platform kami, Anda dapat mengikuti langkah-langkah berikut:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Klik tombol "BAYAR ZAKAT SEKARANG" di halaman utama</li>
                                <li>Pilih jenis zakat yang ingin Anda bayarkan</li>
                                <li>Isi formulir dengan data diri dan nominal zakat</li>
                                <li>Pilih metode pembayaran yang tersedia</li>
                                <li>Konfirmasi pembayaran dan simpan bukti transfer</li>
                            </ul>
                            <p class="mt-2">Pembayaran zakat bisa dilakukan kapan saja sepanjang tahun. Namun, banyak umat Muslim yang memilih membayarnya saat bulan Ramadhan karena keutamaannya.</p>
                        `;
                        } else if (userText.toLowerCase().includes('jenis') && (userText.toLowerCase().includes(
                                'zakat') || userText.toLowerCase().includes('macam'))) {
                            replyHtml = `
                            <p class="mb-2">Ada beberapa jenis zakat yang wajib dan sunnah dibayarkan:</p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li><strong>Zakat Mal</strong> - Zakat atas harta yang dimiliki</li>
                                <li><strong>Zakat Fitrah</strong> - Zakat yang wajib dibayar saat Ramadhan</li>
                                <li><strong>Zakat Profesi</strong> - Zakat atas penghasilan/profesi</li>
                                <li><strong>Zakat Emas/Perak</strong> - Zakat atas kepemilikan logam mulia</li>
                                <li><strong>Zakat Perniagaan</strong> - Zakat atas aset perdagangan</li>
                                <li><strong>Zakat Pertanian</strong> - Zakat atas hasil pertanian</li>
                                <li><strong>Zakat Peternakan</strong> - Zakat atas hewan ternak</li>
                            </ul>
                            <p class="mt-2">Untuk memudahkan perhitungan, Anda dapat menggunakan Kalkulator Zakat yang tersedia di platform kami.</p>
                        `;
                        } else if (userText.toLowerCase().includes('manfaat') || userText.toLowerCase()
                            .includes('guna')) {
                            replyHtml = `
                            <p class="mb-2">Zakat memiliki manfaat besar bagi kedua belah pihak:</p>
                            <p class="mb-2"><strong>Bagi Muzakki (Pembayar Zakat):</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Membersihkan harta dari kotoran dan sifat kikir</li>
                                <li>Mendapatkan pahala dan ridha Allah SWT</li>
                                <li>Melatih sikap peduli terhadap sesama</li>
                                <li>Mendapat perlindungan dari bencana dan musibah</li>
                            </ul>
                            <p class="mt-2"><strong>Bagi Mustahik (Penerima Zakat):</strong></p>
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Memenuhi kebutuhan dasar hidup</li>
                                <li>Meningkatkan taraf hidup dan kesejahteraan</li>
                                <li>Mendapat kesempatan untuk berkembang secara ekonomi</li>
                                <li>Merasakan kepedulian dan kasih sayang dari sesama Muslim</li>
                            </ul>
                        `;
                        } else {
                            // For other questions, use Gemini API via backend
                            try {
                                const response = await fetch('{{ route('chatbot.ask') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                                        'Accept': 'application/json'
                                    },
                                    credentials: 'same-origin',
                                    body: JSON.stringify({
                                        message: userText
                                    })
                                });

                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }

                                const data = await response.json();

                                // Extract response text from Gemini API response
                                let responseText = '';
                                
                                if (data.choices && data.choices[0] && data.choices[0].message) {
                                    responseText = data.choices[0].message.content || '';
                                } else if (data.error) {
                                    responseText = 'Maaf, terjadi kesalahan: ' + data.error;
                                }

                                if (responseText) {
                                    appendMessage(formatResponseToHtml(responseText), "bot");
                                } else {
                                    appendMessage(
                                        '<p>⚠️ Format respon dari AI tidak dikenali. Silakan coba lagi.</p>',
                                        "bot");
                                }

                            } catch (apiError) {
                                console.error('Gemini API Error:', apiError);
                                appendMessage(
                                    '<p>⚠️ Terjadi kesalahan saat menghubungi AI. Silakan coba lagi nanti.</p>',
                                    "bot");
                            }
                        }

                        // Remove typing indicator safely
                        const typingIndicator = document.getElementById("typing-indicator");
                        if (typingIndicator) {
                            typingIndicator.remove();
                        }

                        if (replyHtml.trim() !== '') {
                            appendMessage(replyHtml, "bot");
                        }
                        // Focus input after bot response
                        setTimeout(() => input.focus(), 300);
                    } catch (err) {
                        // Remove typing indicator safely
                        const typingIndicator = document.getElementById("typing-indicator");
                        if (typingIndicator) {
                            typingIndicator.remove();
                        }

                        // Display error message
                        appendMessage(
                            '<p>⚠️ Terjadi kesalahan saat memproses pesan Anda. Silakan coba lagi nanti.</p>',
                            "bot");
                        console.error(err);

                        // Focus input after error
                        setTimeout(() => input.focus(), 300);
                    }
                }

                sendBtn.addEventListener("click", sendMessage);
                input.addEventListener("keydown", e => {
                    if (e.key === "Enter" && !e.shiftKey) {
                        e.preventDefault();
                        sendMessage();
                    }
                });

                // Auto-resize textarea as user types
                input.addEventListener("input", () => {
                    input.style.height = 'auto';
                    input.style.height = Math.min(input.scrollHeight, 120) + 'px';
                });

                // Focus input when chat container is clicked
                document.querySelector('.p-3.border-t').addEventListener('click', () => {
                    input.focus();
                });

                // Ensure input remains focused when user interacts with chat
                messages.addEventListener('click', () => {
                    input.focus();
                });
            }

            // Initialize chatbot directly
            initializeChatbot();
        });

        // Prevent blinking on page load
        (function() {
            // Mark page as loaded to trigger animations
            function markPageLoaded() {
                document.body.classList.add('page-loaded');
            }

            // Check if DOM is already loaded
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', markPageLoaded);
            } else {
                // DOM already loaded, mark immediately
                markPageLoaded();
            }
        })();

        document.addEventListener('DOMContentLoaded', function() {
            ['campaigns', 'news', 'artikel'].forEach(s => {
                initSlider(s);
                setupAutoScroll(s);
            });
        });

        function initSlider(sliderName) {
            const slider = document.getElementById(`${sliderName}-slider`);
            const prevBtn = document.getElementById(`${sliderName}-prev`);
            const nextBtn = document.getElementById(`${sliderName}-next`);
            const indicatorsContainer = document.querySelector(`.${sliderName}-indicators`);

            if (!slider) return;

            function renderDots() {
                if (!indicatorsContainer) return;
                
                const slideItem = slider.querySelector('.flex-shrink-0');
                if (!slideItem) return;
                
                const slideWidth = slideItem.offsetWidth + 16;
                const maxScroll = slider.scrollWidth - slider.clientWidth;
                
                if (maxScroll <= 0) {
                    indicatorsContainer.innerHTML = '';
                    return;
                }
                
                const dotCount = Math.ceil(maxScroll / slideWidth) + 1;
                
                if (indicatorsContainer.children.length === dotCount) return;
                
                indicatorsContainer.innerHTML = '';
                for (let i = 0; i < dotCount; i++) {
                    const indicator = document.createElement('span');
                    indicator.classList.add('w-2', 'h-2', 'rounded-full', 'cursor-pointer', 'transition-all', 'duration-300');
                    if (i === 0) {
                        indicator.classList.add('bg-orange-600', 'w-4');
                    } else {
                        indicator.classList.add('bg-gray-300');
                    }
                    indicator.dataset.index = i;
                    indicatorsContainer.appendChild(indicator);

                    indicator.addEventListener('click', () => scrollToSlide(slider, i));
                }
                
                updateIndicatorsOnScroll(slider, indicatorsContainer);
            }

            renderDots();
            window.addEventListener('resize', renderDots);

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    scrollSlider(slider, -1);
                    updateIndicatorsOnScroll(slider, indicatorsContainer);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    scrollSlider(slider, 1);
                    updateIndicatorsOnScroll(slider, indicatorsContainer);
                });
            }

            slider.addEventListener('scroll', () => updateIndicatorsOnScroll(slider, indicatorsContainer));

            function toggleNavigationButtons() {
                const isMobile = window.innerWidth < 768;
                if (prevBtn) prevBtn.classList.toggle('hidden', isMobile);
                if (nextBtn) nextBtn.classList.toggle('hidden', isMobile);
            }

            toggleNavigationButtons();
            window.addEventListener('resize', toggleNavigationButtons);

            let isDown = false;
            let startX, scrollLeft;

            slider.addEventListener('mousedown', (e) => {
                isDown = true;
                slider.classList.add('cursor-grabbing');
                startX = e.pageX - slider.offsetLeft;
                scrollLeft = slider.scrollLeft;
            });

            slider.addEventListener('mouseleave', () => {
                isDown = false;
                slider.classList.remove('cursor-grabbing');
            });

            slider.addEventListener('mouseup', () => {
                isDown = false;
                slider.classList.remove('cursor-grabbing');
            });

            slider.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const walk = (e.pageX - slider.offsetLeft - startX) * 2;
                slider.scrollLeft = scrollLeft - walk;
            });
        }

        function scrollSlider(slider, direction) {
            const slideItem = slider.querySelector('.flex-shrink-0');
            if (!slideItem) return;
            slider.scrollBy({
                left: direction * (slideItem.offsetWidth + 16),
                behavior: 'smooth'
            });
        }

        function scrollToSlide(slider, index) {
            const slideWidth = slider.querySelector('.flex-shrink-0').offsetWidth + 16;
            slider.scrollTo({ left: index * slideWidth, behavior: 'smooth' });
        }

        function updateIndicators(indicatorsContainer, activeIndex) {
            indicatorsContainer.querySelectorAll('span').forEach((indicator, index) => {
                if (index === activeIndex) {
                    indicator.classList.remove('bg-gray-300');
                    indicator.classList.add('bg-orange-600', 'w-4');
                } else {
                    indicator.classList.remove('bg-orange-600', 'w-4');
                    indicator.classList.add('bg-gray-300', 'w-2');
                }
            });
        }

        function updateIndicatorsOnScroll(slider, indicatorsContainer) {
            if (!indicatorsContainer || indicatorsContainer.children.length === 0) return;

            const slideItem = slider.querySelector('.flex-shrink-0');
            if (!slideItem) return;
            
            const slideWidth = slideItem.offsetWidth + 16;
            let activeIndex = Math.round(slider.scrollLeft / slideWidth);
            const maxIndex = indicatorsContainer.children.length - 1;
            
            if (Math.ceil(slider.scrollLeft + slider.clientWidth) >= slider.scrollWidth) {
                activeIndex = maxIndex;
            }
            
            if (activeIndex > maxIndex) activeIndex = maxIndex;
            updateIndicators(indicatorsContainer, activeIndex);
        }

        function setupAutoScroll(sliderName) {
            const slider = document.getElementById(`${sliderName}-slider`);
            const prevBtn = document.getElementById(`${sliderName}-prev`);
            const nextBtn = document.getElementById(`${sliderName}-next`);
            if (!slider) return;

            let autoScrollInterval;

            function startAutoScroll() {
                autoScrollInterval = setInterval(() => {
                    if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
                        slider.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        scrollSlider(slider, 1);
                    }
                }, 5000);
            }

            function stopAutoScroll() {
                if (autoScrollInterval) clearInterval(autoScrollInterval);
            }

            startAutoScroll();

            slider.addEventListener('mouseenter', stopAutoScroll);
            slider.addEventListener('mouseleave', startAutoScroll);

            if (prevBtn) prevBtn.addEventListener('click', stopAutoScroll);
            if (nextBtn) nextBtn.addEventListener('click', stopAutoScroll);
        }
    </script>
