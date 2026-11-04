<!-- ==========================================
     HTD STORE - AI CHAT BOX & FLOATING WIDGET
     ========================================== -->
<div id="ai-chat-root" class="fixed bottom-6 right-6 z-[9999] font-sans antialiased">
    <!-- Chat Modal Window -->
    <div id="ai-chat-window" class="hidden flex flex-col w-[94vw] sm:w-[410px] h-[580px] max-h-[82vh] bg-white rounded-3xl shadow-2xl shadow-indigo-500/25 border border-slate-200 overflow-hidden transition-all duration-300 transform scale-95 opacity-0 origin-bottom-right mb-4">
        
        <!-- Header (Fixed at top) -->
        <div class="flex-shrink-0 relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white p-4 flex items-center justify-between shadow-md">
            <!-- Background Glow -->
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>

            <div class="flex items-center gap-3 relative z-10">
                <div class="relative">
                    <div class="w-10 h-10 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30 shadow-inner">
                        <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-400 border-2 border-white rounded-full animate-pulse"></span>
                </div>
                <div>
                    <h3 class="font-bold text-base leading-tight flex items-center gap-1.5">
                        Trợ Lý AI HTD Store
                        <span class="bg-white/20 text-[10px] font-semibold px-2 py-0.5 rounded-full uppercase tracking-wider backdrop-blur-sm">AI 2.0</span>
                    </h3>
                    <p class="text-xs text-indigo-100 opacity-90">Tư vấn chọn size & mẫu áo 24/7</p>
                </div>
            </div>

            <!-- Header Action Buttons -->
            <div class="flex items-center gap-1 relative z-10">
                <!-- Clear Chat -->
                <button id="ai-clear-btn" title="Xóa lịch sử chat" class="w-8 h-8 rounded-xl flex items-center justify-center text-white/80 hover:text-white hover:bg-white/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
                <!-- Close Button -->
                <button id="ai-close-btn" title="Thu nhỏ" class="w-8 h-8 rounded-xl flex items-center justify-center text-white/80 hover:text-white hover:bg-white/20 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Quick Question Chips (Fixed under header) -->
        <div id="ai-quick-chips" class="flex-shrink-0 px-3 pt-2 pb-2.5 bg-slate-50 border-b border-slate-200 overflow-x-auto flex items-center gap-2 whitespace-nowrap text-xs select-none">
            <span class="text-slate-400 font-medium text-[11px] flex items-center gap-1 flex-shrink-0">
                <svg class="w-3.5 h-3.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                Gợi ý:
            </span>
            <button class="ai-chip px-2.5 py-1 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-xs flex-shrink-0 cursor-pointer" data-prompt="tôi muốn mua áo mặc mùa đông">
                ❄️ Áo mặc mùa đông
            </button>
            <button class="ai-chip px-2.5 py-1 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-xs flex-shrink-0 cursor-pointer" data-prompt="Mình cao 1m68 nặng 62kg nên chọn size nào?">
                📏 Tư vấn size
            </button>
            <button class="ai-chip px-2.5 py-1 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-xs flex-shrink-0 cursor-pointer" data-prompt="Gợi ý các mẫu áo thun và áo polo thoáng mát mùa hè">
                ☀️ Áo thun mùa hè
            </button>
            <button class="ai-chip px-2.5 py-1 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-xs flex-shrink-0 cursor-pointer" data-prompt="Shop có những mẫu áo sơ mi hoặc vest công sở nào?">
                👔 Sơ mi công sở
            </button>
            <button class="ai-chip px-2.5 py-1 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-xs flex-shrink-0 cursor-pointer" data-prompt="Các mẫu đang giảm giá hot hôm nay">
                🔥 Khuyến mãi Hot
            </button>
            <button class="ai-chip px-2.5 py-1 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-xs flex-shrink-0 cursor-pointer" data-prompt="Chính sách freeship và đổi trả của shop như thế nào?">
                🚚 Freeship & Đổi trả
            </button>
            <button class="ai-chip px-2.5 py-1 rounded-full bg-white border border-slate-200 text-slate-700 hover:border-indigo-500 hover:text-indigo-600 transition shadow-xs flex-shrink-0 cursor-pointer" data-prompt="Gợi ý cho mình các mẫu mới về nhất">
                ✨ Mới về nhất
            </button>
        </div>

        <!-- Chat Messages Area with Persistent Scrollbar (Takes remaining height) -->
        <div class="relative flex-1 min-h-0 flex flex-col overflow-hidden bg-slate-50/30">
            <div id="ai-chat-messages" class="flex-1 p-3.5 space-y-3 text-sm bg-gradient-to-b from-slate-50/40 to-white" style="overflow-y: scroll !important;">
                
                <!-- Welcome message from AI -->
                <div class="flex items-start gap-2.5 ai-msg-item">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex-shrink-0 flex items-center justify-center text-xs shadow-sm font-bold">
                        AI
                    </div>
                    <div class="max-w-[84%] bg-white p-3.5 rounded-2xl rounded-tl-sm text-slate-800 shadow-xs border border-slate-200/80 leading-relaxed text-[13.5px]">
                        <p class="font-semibold text-indigo-700 mb-1 flex items-center gap-1.5">
                            <span>👋 Chào bạn! Mình là Trợ lý AI HTD Store</span>
                        </p>
                        <p>Mình có thể tư vấn <strong>chọn size chuẩn</strong>, gợi ý các mẫu <strong>áo mùa đông</strong> (áo phao, áo khoác, áo giữ nhiệt), <strong>áo polo/thun mùa hè</strong> hoặc giải đáp chính sách giao hàng.</p>
                        <p class="mt-2 text-xs text-slate-500 italic">Bạn đang muốn tìm trang phục nào hôm nay?</p>
                    </div>
                </div>

            </div>

            <!-- Nút cuộn xuống dưới cùng (Hiện khi cuộn lên xem tin cũ) -->
            <button id="ai-scroll-bottom-btn" type="button" title="Cuộn xuống tin nhắn mới nhất" class="hidden absolute bottom-3 right-4 w-8 h-8 rounded-full bg-white text-indigo-600 shadow-lg border border-slate-200 hover:bg-indigo-50 hover:scale-110 active:scale-95 transition-all flex items-center justify-center z-20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            </button>
        </div>

        <!-- Typing Indicator (Hidden by default) -->
        <div id="ai-typing" class="hidden flex-shrink-0 px-4 py-2 bg-white flex items-center gap-2 text-xs text-slate-400 border-t border-slate-100">
            <div class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[10px]">
                AI
            </div>
            <div class="bg-slate-100 px-3 py-2 rounded-2xl rounded-tl-sm flex items-center gap-1">
                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
            </div>
            <span class="text-[11px] text-slate-400">Trợ lý AI đang tìm sản phẩm...</span>
        </div>

        <!-- Quick Size Consultation Bar (Hiện khi ấn 'Tư vấn' ở sản phẩm) -->
        <div id="ai-quick-size-bar" class="hidden px-3.5 py-2.5 bg-gradient-to-r from-purple-50 via-indigo-50 to-pink-50 border-t border-purple-200/80 transition-all flex-shrink-0">
            <div class="flex items-center justify-between gap-2 mb-1.5">
                <div class="flex items-center gap-1.5 min-w-0">
                    <span class="text-sm">📏</span>
                    <span class="text-[11.5px] font-bold text-purple-900 truncate">
                        Tư vấn size: <span id="ai-quick-product-name" class="text-indigo-700 font-semibold"></span>
                    </span>
                </div>
                <button type="button" id="ai-quick-close-btn" class="text-slate-400 hover:text-slate-600 text-xs px-1 hover:bg-slate-200/50 rounded transition cursor-pointer" title="Đóng">✕</button>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input type="number" id="ai-quick-height" placeholder="Cao (cm) vd 170" class="w-full pl-2.5 pr-7 py-1.5 text-xs bg-white border border-purple-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent" min="100" max="230">
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none">cm</span>
                </div>
                <div class="relative flex-1">
                    <input type="number" id="ai-quick-weight" placeholder="Nặng (kg) vd 65" class="w-full pl-2.5 pr-6 py-1.5 text-xs bg-white border border-purple-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent" min="30" max="150">
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none">kg</span>
                </div>
                <button type="button" id="ai-quick-submit-btn" class="px-3 py-1.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all whitespace-nowrap active:scale-95 cursor-pointer">
                    Tư vấn ✨
                </button>
            </div>
        </div>

        <!-- Chat Input Form (Fixed at bottom) -->
        <div class="flex-shrink-0 p-3 bg-white border-t border-slate-200">
            <form id="ai-chat-form" class="flex items-center gap-2">
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        id="ai-user-input" 
                        placeholder="Hỏi AI về áo mùa đông, size, mẫu mới..." 
                        autocomplete="off"
                        class="w-full pl-4 pr-10 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-2xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                    />
                </div>
                <button 
                    type="submit" 
                    id="ai-send-btn" 
                    class="w-10 h-10 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30 hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:hover:scale-100"
                >
                    <svg class="w-4 h-4 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
            <div class="mt-1.5 text-center">
                <span class="text-[10px] text-slate-400">Tối ưu bởi AI & kho hàng thời trang HTD Store</span>
            </div>
        </div>
    </div>

    <!-- Floating Trigger Controls (Bottom-Right) -->
    <div class="flex flex-col items-end gap-3">
        <!-- Main AI Chatbot Button -->
        <button 
            id="ai-chat-trigger" 
            type="button"
            title="Chat với Trợ Lý AI"
            class="relative group w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 via-purple-600 to-pink-500 text-white flex items-center justify-center shadow-2xl shadow-indigo-600/40 hover:scale-110 hover:-translate-y-1 transition-all duration-300 focus:outline-none"
        >
            <!-- Pulsing outer ring -->
            <span class="absolute -inset-1 rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-60 blur-sm group-hover:opacity-100 group-hover:blur-md transition-all animate-pulse"></span>

            <!-- Tooltip Badge on hover -->
            <div class="absolute right-full mr-3 top-1/2 -translate-y-1/2 bg-slate-900/90 backdrop-blur-md text-white text-xs font-semibold px-3 py-1.5 rounded-xl shadow-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none translate-x-2 group-hover:translate-x-0 flex items-center gap-1.5">
                <span>✨ Chat với Trợ lý AI</span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            </div>

            <!-- Icon State 1: Sparkle / Robot AI (Visible when closed) -->
            <div id="ai-trigger-icon-chat" class="relative z-10 transition-transform duration-300 flex items-center justify-center">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <!-- Sparkle Badge -->
                <span class="absolute -top-1 -right-1 text-xs">✨</span>
            </div>

            <!-- Icon State 2: Close X (Visible when open) -->
            <div id="ai-trigger-icon-close" class="hidden relative z-10 transition-transform duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        </button>

        <!-- Scroll Top Button -->
        <button 
            id="globalScrollTopBtn" 
            type="button"
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
            title="Lên đầu trang"
            class="w-12 h-12 bg-white text-slate-800 rounded-2xl flex items-center justify-center shadow-lg border border-slate-100 hover:bg-slate-50 hover:scale-105 transition-all opacity-0 pointer-events-none translate-y-6"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>
    </div>
</div>

<style>
/* ===================================================
   THANH CUỘN (SCROLLBAR) RÕ RÀNG VÀ CHUẨN ĐẸP CHO CHAT BOX
   =================================================== */
#ai-chat-messages {
    overflow-y: scroll !important;
    scrollbar-gutter: stable;
    scrollbar-width: thin !important;
    scrollbar-color: #6366f1 #f1f5f9 !important;
    scroll-behavior: smooth !important;
}

#ai-chat-messages::-webkit-scrollbar {
    width: 8px !important;
    display: block !important;
}

#ai-chat-messages::-webkit-scrollbar-track {
    background: #f1f5f9 !important;
    border-radius: 9999px !important;
    margin: 4px 0 !important;
}

#ai-chat-messages::-webkit-scrollbar-thumb {
    background: #6366f1 !important;
    border-radius: 9999px !important;
    border: 2px solid #f1f5f9 !important;
}

#ai-chat-messages::-webkit-scrollbar-thumb:hover {
    background: #4f46e5 !important;
}

/* ===================================================
   THANH CUỘN NGANG RÕ RÀNG VÀ CHUẨN ĐẸP CHO GỢI Ý (CHIPS)
   =================================================== */
#ai-quick-chips {
    overflow-x: auto !important;
    scrollbar-gutter: stable;
    scrollbar-width: thin !important;
    scrollbar-color: #818cf8 #f1f5f9 !important;
    cursor: grab;
}

#ai-quick-chips:active {
    cursor: grabbing;
}

#ai-quick-chips::-webkit-scrollbar {
    height: 6px !important;
    display: block !important;
}

#ai-quick-chips::-webkit-scrollbar-track {
    background: #f1f5f9 !important;
    border-radius: 9999px !important;
    margin: 0 8px !important;
}

#ai-quick-chips::-webkit-scrollbar-thumb {
    background: #818cf8 !important;
    border-radius: 9999px !important;
    border: 1px solid #f1f5f9 !important;
}

#ai-quick-chips::-webkit-scrollbar-thumb:hover {
    background: #6366f1 !important;
}

/* AI Message Markdown Links */
.ai-msg-content a {
    color: #4f46e5;
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 2px;
}
.ai-msg-content a:hover {
    color: #4338ca;
}
.ai-msg-content p {
    margin-bottom: 0.5rem;
}
.ai-msg-content p:last-child {
    margin-bottom: 0;
}
.ai-msg-content ul {
    list-style-type: disc;
    padding-left: 1.25rem;
    margin-bottom: 0.5rem;
}
</style>

<script>
(function() {
    const triggerBtn = document.getElementById('ai-chat-trigger');
    const closeBtn = document.getElementById('ai-close-btn');
    const clearBtn = document.getElementById('ai-clear-btn');
    const chatWindow = document.getElementById('ai-chat-window');
    const chatMessages = document.getElementById('ai-chat-messages');
    const chatForm = document.getElementById('ai-chat-form');
    const userInput = document.getElementById('ai-user-input');
    const sendBtn = document.getElementById('ai-send-btn');
    const typingIndicator = document.getElementById('ai-typing');
    const iconChat = document.getElementById('ai-trigger-icon-chat');
    const iconClose = document.getElementById('ai-trigger-icon-close');
    const scrollTopBtn = document.getElementById('globalScrollTopBtn');
    const scrollBottomBtn = document.getElementById('ai-scroll-bottom-btn');
    const quickSizeBar = document.getElementById('ai-quick-size-bar');
    const quickProductName = document.getElementById('ai-quick-product-name');
    const quickHeight = document.getElementById('ai-quick-height');
    const quickWeight = document.getElementById('ai-quick-weight');
    const quickSubmitBtn = document.getElementById('ai-quick-submit-btn');
    const quickCloseBtn = document.getElementById('ai-quick-close-btn');

    let isOpen = false;
    let chatHistory = [];
    let currentConsultProduct = '';
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Scroll to bottom of message list
    function scrollToBottom() {
        if (!chatMessages) return;
        chatMessages.scrollTo({
            top: chatMessages.scrollHeight,
            behavior: 'smooth'
        });
    }

    // Lắng nghe sự kiện cuộn của chat để ẩn/hiện nút cuộn xuống dưới cùng
    if (chatMessages && scrollBottomBtn) {
        chatMessages.addEventListener('scroll', function() {
            const distanceFromBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight;
            if (distanceFromBottom > 80) {
                scrollBottomBtn.classList.remove('hidden');
            } else {
                scrollBottomBtn.classList.add('hidden');
            }
        });

        scrollBottomBtn.addEventListener('click', function() {
            scrollToBottom();
        });
    }

    // Toggle Chat Window
    function toggleChat(openState) {
        isOpen = (typeof openState === 'boolean') ? openState : !isOpen;

        if (isOpen) {
            chatWindow.classList.remove('hidden');
            setTimeout(() => {
                chatWindow.classList.remove('scale-95', 'opacity-0');
                chatWindow.classList.add('scale-100', 'opacity-100');
            }, 10);
            iconChat.classList.add('hidden');
            iconClose.classList.remove('hidden');
            setTimeout(() => userInput.focus(), 150);
            scrollToBottom();
        } else {
            chatWindow.classList.remove('scale-100', 'opacity-100');
            chatWindow.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
            }, 300);
            iconChat.classList.remove('hidden');
            iconClose.classList.add('hidden');
        }
    }

    triggerBtn.addEventListener('click', () => toggleChat());
    closeBtn.addEventListener('click', () => toggleChat(false));

    // Clear chat
    clearBtn.addEventListener('click', () => {
        chatHistory = [];
        const welcomeMsg = chatMessages.querySelector('.ai-msg-item');
        chatMessages.innerHTML = '';
        if (welcomeMsg) chatMessages.appendChild(welcomeMsg);
        showToast('Đã làm mới cuộc trò chuyện!', 'success');
    });

    // Helper: Parse markdown đơn giản (bold, links, linebreaks)
    function parseSimpleMarkdown(text) {
        if (!text) return '';
        let escaped = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Links: [Text](url)
        escaped = escaped.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank">$1 ↗</a>');

        // Bold: **text**
        escaped = escaped.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');

        // Bullet points
        escaped = escaped.replace(/^[•\-\*]\s+(.*)$/gm, '<li>$1</li>');
        escaped = escaped.replace(/(<li>.*<\/li>)/gs, '<ul>$1</ul>');

        // Line breaks
        escaped = escaped.replace(/\n\n/g, '<br><br>').replace(/\n/g, '<br>');

        return escaped;
    }

    // Tạo HTML danh sách sản phẩm gợi ý
    function renderProductCards(products) {
        if (!products || !Array.isArray(products) || products.length === 0) return '';

        let html = `
            <div class="mt-3 pt-3 border-t border-slate-200/80 space-y-2">
                <p class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1">
                    <span>🛍️ Sản phẩm gợi ý cho bạn:</span>
                </p>
                <div class="space-y-2">
        `;

        products.forEach(p => {
            const escapedName = (p.name || '').replace(/"/g, '&quot;');
            html += `
                <div class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 hover:bg-indigo-50/50 border border-slate-200 hover:border-indigo-200 transition-all">
                    <a href="${p.url}" target="_blank" class="flex items-center gap-2.5 flex-1 min-w-0 no-underline text-inherit group">
                        <img src="${p.image}" class="w-12 h-12 rounded-lg object-cover bg-white flex-shrink-0 border border-slate-200" alt="${escapedName}">
                        <div class="flex-1 min-w-0">
                            <h5 class="text-xs font-semibold text-slate-900 group-hover:text-indigo-600 line-clamp-1" title="${escapedName}">${escapedName}</h5>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="text-xs font-bold text-red-600">${p.sale_price || p.price}</span>
                                ${p.has_sale ? `<span class="text-[10px] text-slate-400 line-through">${p.price}</span>` : ''}
                            </div>
                        </div>
                    </a>
                    <div class="flex items-center gap-1.5 flex-shrink-0">
                        <button type="button" 
                            class="ai-consult-btn text-[11px] font-semibold text-purple-700 hover:text-white bg-purple-50 hover:bg-gradient-to-r hover:from-purple-600 hover:to-indigo-600 border border-purple-200 hover:border-transparent px-2 py-1 rounded-lg shadow-2xs transition-all flex items-center gap-1 cursor-pointer"
                            data-product-name="${escapedName}"
                            title="Tư vấn chọn size chuẩn theo chiều cao cân nặng">
                            <span>📏</span> Tư vấn
                        </button>
                        <a href="${p.url}" target="_blank" class="text-[11px] text-indigo-600 font-bold px-2 py-1 bg-white hover:bg-indigo-600 hover:text-white border border-slate-200 hover:border-indigo-600 rounded-lg shadow-2xs transition-all flex-shrink-0 no-underline">
                            Xem ↗
                        </a>
                    </div>
                </div>
            `;
        });

        html += `
                </div>
            </div>
        `;
        return html;
    }

    // Append Message to UI
    function appendMessage(sender, content, products = []) {
        const item = document.createElement('div');
        item.classList.add('flex', 'items-start', 'gap-2.5');

        if (sender === 'user') {
            item.classList.add('justify-end');
            item.innerHTML = `
                <div class="max-w-[82%] bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-3.5 rounded-2xl rounded-tr-sm shadow-md text-[13.5px] leading-relaxed">
                    ${content.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                </div>
            `;
        } else {
            const productHtml = renderProductCards(products);
            item.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 text-white flex-shrink-0 flex items-center justify-center text-xs shadow-sm font-bold">
                    AI
                </div>
                <div class="max-w-[84%] bg-white p-3.5 rounded-2xl rounded-tl-sm text-slate-800 shadow-xs border border-slate-200/80 leading-relaxed text-[13.5px] ai-msg-content">
                    <div>${parseSimpleMarkdown(content)}</div>
                    ${productHtml}
                </div>
            `;
        }

        chatMessages.appendChild(item);
        scrollToBottom();
    }

    // Send Message Handler
    async function sendMessage(text) {
        const msg = text || userInput.value.trim();
        if (!msg) return;

        if (quickSizeBar) {
            quickSizeBar.classList.add('hidden');
        }

        // Add user message to UI
        appendMessage('user', msg);
        chatHistory.push({ role: 'user', content: msg });
        userInput.value = '';
        userInput.disabled = true;
        sendBtn.disabled = true;

        // Show typing
        typingIndicator.classList.remove('hidden');
        scrollToBottom();

        try {
            const response = await fetch('/chatbot/message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: msg,
                    history: chatHistory
                })
            });

            const data = await response.json();
            typingIndicator.classList.add('hidden');

            if (data.success && data.reply) {
                appendMessage('assistant', data.reply, data.products || []);
                chatHistory.push({ role: 'assistant', content: data.reply });
            } else {
                appendMessage('assistant', 'Dạ hiện tại hệ thống AI đang bận một chút, anh/chị có thể thử lại sau giây lát hoặc liên hệ hotline để được hỗ trợ ngay ạ! 🙏');
            }
        } catch (err) {
            console.error('Chatbot error:', err);
            typingIndicator.classList.add('hidden');
            appendMessage('assistant', 'Dạ kết nối mạng có gián đoạn. Anh/chị vui lòng kiểm tra kết nối và nhắn lại giúp em nhé!');
        } finally {
            userInput.disabled = false;
            sendBtn.disabled = false;
            userInput.focus();
            scrollToBottom();
        }
    }

    // Submit listener
    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        sendMessage();
    });

    // Quick chips
    document.querySelectorAll('.ai-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            const prompt = this.getAttribute('data-prompt');
            if (prompt) {
                sendMessage(prompt);
            }
        });
    });

    // Kéo trượt thanh gợi ý bằng chuột (Drag to scroll) & Cuộn chuột ngang
    const chipsContainer = document.getElementById('ai-quick-chips');
    if (chipsContainer) {
        let isDown = false;
        let startX = 0;
        let scrollLeft = 0;
        let hasMoved = false;

        chipsContainer.addEventListener('mousedown', (e) => {
            isDown = true;
            hasMoved = false;
            startX = e.pageX - chipsContainer.offsetLeft;
            scrollLeft = chipsContainer.scrollLeft;
        });

        window.addEventListener('mouseup', () => {
            isDown = false;
        });

        chipsContainer.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            const x = e.pageX - chipsContainer.offsetLeft;
            const walk = (x - startX) * 1.3;
            if (Math.abs(walk) > 4) {
                hasMoved = true;
            }
            chipsContainer.scrollLeft = scrollLeft - walk;
        });

        // Hỗ trợ lăn chuột biến thành cuộn ngang
        chipsContainer.addEventListener('wheel', (e) => {
            if (e.deltaY !== 0) {
                e.preventDefault();
                chipsContainer.scrollLeft += e.deltaY;
            }
        }, { passive: false });

        // Ngăn click nhầm khi đang kéo trượt danh sách
        chipsContainer.querySelectorAll('.ai-chip').forEach(chip => {
            chip.addEventListener('click', function(e) {
                if (hasMoved) {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                    hasMoved = false;
                }
            }, true);
        });
    }

    // Xử lý khi bấm nút "📏 Tư vấn" ở mỗi thẻ sản phẩm
    chatMessages.addEventListener('click', (e) => {
        const consultBtn = e.target.closest('.ai-consult-btn');
        if (!consultBtn) return;

        const productName = consultBtn.getAttribute('data-product-name') || '';
        currentConsultProduct = productName;

        // 1. Điền vào thanh chat chính để khách dễ theo dõi và có thể tự gõ nếu muốn
        userInput.value = `Tư vấn size cho ${productName}: cao  cm, nặng  kg`;

        // 2. Mở thanh nhập nhanh chiều cao, cân nặng nổi bật ngay trên khung chat
        if (quickSizeBar && quickProductName) {
            quickProductName.textContent = productName;
            quickSizeBar.classList.remove('hidden');
            if (quickHeight) {
                quickHeight.value = '';
                quickHeight.focus();
            }
            if (quickWeight) {
                quickWeight.value = '';
            }
        } else {
            userInput.focus();
            const pos = userInput.value.indexOf('cao ') + 4;
            userInput.setSelectionRange(pos, pos);
        }
        scrollToBottom();
    });

    // Đóng thanh nhập nhanh
    if (quickCloseBtn) {
        quickCloseBtn.addEventListener('click', () => {
            quickSizeBar.classList.add('hidden');
        });
    }

    // Gửi tư vấn nhanh từ 2 ô chiều cao / cân nặng
    function submitQuickConsult() {
        const h = quickHeight ? parseInt(quickHeight.value, 10) : 0;
        const w = quickWeight ? parseInt(quickWeight.value, 10) : 0;
        const prod = currentConsultProduct || 'mẫu này';

        if (!h && !w) {
            if (quickHeight) quickHeight.focus();
            return;
        }

        let query = `Tư vấn size cho ${prod}:`;
        if (h) query += ` cao ${h}cm,`;
        if (w) query += ` nặng ${w}kg`;
        query = query.replace(/,$/, '');

        if (quickSizeBar) quickSizeBar.classList.add('hidden');
        userInput.value = '';
        sendMessage(query);
    }

    if (quickSubmitBtn) {
        quickSubmitBtn.addEventListener('click', submitQuickConsult);
    }

    [quickHeight, quickWeight].forEach(input => {
        if (!input) return;
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitQuickConsult();
            }
        });
    });

    // Global Scroll To Top Listener
    if (scrollTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 400) {
                scrollTopBtn.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-6');
            } else {
                scrollTopBtn.classList.add('opacity-0', 'pointer-events-none', 'translate-y-6');
            }
        });
    }
})();
</script>
