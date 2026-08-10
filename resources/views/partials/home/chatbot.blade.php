
    <div id="chatbot-container" class="fixed bottom-6 right-6 z-50 flex flex-col items-end space-y-3">

        
        <div id="chatbot-popup"
            class="hidden flex-col bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl w-80 max-h-[500px] border border-orange-200 overflow-hidden">
            <div class="bg-orange-600 text-white p-3 font-bold text-center">
                Lazismu Banten
            </div>
            <div id="chat-messages"
                class="flex-1 p-3 overflow-y-auto flex flex-col text-sm text-gray-800 chat-messages-container">
                <div class="text-center text-gray-400 text-xs animate-fadeInUp">Mulai percakapan...</div>
            </div>
        <div class="p-3 border-t border-gray-200">
                <div class="flex items-end gap-2">
                    <textarea id="chat-input"
                        placeholder="Ketik pesan..."
                        rows="1"
                        class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 resize-none overflow-hidden leading-relaxed"
                        style="max-height: 120px;"></textarea>
                    <button id="send-btn"
                        class="flex-shrink-0 bg-orange-600 text-white w-9 h-9 rounded-xl hover:bg-orange-700 transition-colors flex items-center justify-center"
                        aria-label="Kirim pesan">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </button>
                </div>
                <p class="text-[10px] text-gray-400 mt-1 pl-0.5">Enter untuk kirim · Shift+Enter baris baru</p>
            </div>
        </div>

        
        <div class="flex items-center gap-3">
            
            <a href="https://api.whatsapp.com/send/?phone=628561626222&text=Assalamu%E2%80%99alaikum+Warahmatullahi+Wabarakatuh%2C+hallo+tim+Lazismu+%5Bwebsite%5D&type=phone_number&app_absent=0" 
               target="_blank" 
               class="bg-green-500 hover:bg-green-600 text-white rounded-full p-4 shadow-lg transition transform hover:scale-110 flex items-center justify-center w-14 h-14"
               aria-label="Chat WhatsApp">
                <i class="fab fa-whatsapp text-2xl"></i>
            </a>

            
            <button id="chatbot-button"
                class="bg-orange-600 hover:bg-orange-700 text-white rounded-full p-4 shadow-lg transition-colors flex items-center justify-center w-14 h-14"
                aria-label="Buka Chatbot">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </button>
        </div>
    </div>

    
