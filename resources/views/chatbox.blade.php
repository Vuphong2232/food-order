<style>
    .chatbox-widget {
        position: fixed;
        right: 24px;
        bottom: 24px;
        z-index: 9999;
        font-family: inherit;
    }

    .chatbox-panel {
        width: 360px;
        height: 520px;
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(92, 64, 51, 0.22);
        border: 1px solid #eadfd6;
        overflow: hidden;
        display: none;
        flex-direction: column;
        margin-bottom: 16px;
    }

    .chatbox-panel.active {
        display: flex;
    }

    .chatbox-header {
        background: #8b5e3c;
        color: #fff;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chatbox-title {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .chatbox-subtitle {
        font-size: 12px;
        opacity: 0.9;
        margin-top: 2px;
    }

    .chatbox-close {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        cursor: pointer;
        font-size: 18px;
    }

    .chatbox-messages {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        background: #fffaf5;
    }

    .chat-message-row {
        display: flex;
        margin-bottom: 10px;
    }

    .chat-message-row.user {
        justify-content: flex-end;
    }

    .chat-message-row.bot {
        justify-content: flex-start;
    }

    .chat-message {
        max-width: 78%;
        padding: 10px 13px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.45;
        word-wrap: break-word;
    }

    .chat-message.user {
        background: #8b5e3c;
        color: #fff;
        border-bottom-right-radius: 5px;
    }

    .chat-message.bot {
        background: #fff;
        color: #3f2a1d;
        border: 1px solid #eadfd6;
        border-bottom-left-radius: 5px;
    }

    .chatbox-form {
        padding: 12px;
        border-top: 1px solid #eadfd6;
        background: #fff;
        display: flex;
        gap: 8px;
    }

    .chatbox-input {
        flex: 1;
        border: 1px solid #d6c2b3;
        border-radius: 14px;
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
    }

    .chatbox-input:focus {
        border-color: #8b5e3c;
        box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.12);
    }

    .chatbox-send {
        border: none;
        background: #8b5e3c;
        color: #fff;
        border-radius: 14px;
        padding: 0 16px;
        font-weight: 700;
        cursor: pointer;
    }

    .chatbox-toggle {
        width: 62px;
        height: 62px;
        border-radius: 999px;
        border: none;
        background: #8b5e3c;
        color: #fff;
        box-shadow: 0 14px 32px rgba(92, 64, 51, 0.35);
        cursor: pointer;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
    }

    .chatbox-actions {
        padding: 8px 12px;
        border-top: 1px solid #f1e7dd;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chatbox-clear {
        border: none;
        background: transparent;
        color: #b91c1c;
        font-size: 12px;
        cursor: pointer;
    }

    .chatbox-note {
        font-size: 12px;
        color: #8b5e3c;
    }

    @media (max-width: 480px) {
        .chatbox-widget {
            right: 14px;
            bottom: 14px;
            left: 14px;
        }

        .chatbox-panel {
            width: 100%;
            height: 70vh;
        }
    }
</style>

<div class="chatbox-widget">
    <div id="chatboxPanel" class="chatbox-panel">
        <div class="chatbox-header">
            <div>
                <h3 class="chatbox-title">Hỗ trợ khách hàng</h3>
                <div class="chatbox-subtitle">Món Ngon luôn sẵn sàng hỗ trợ bạn</div>
            </div>

            <button type="button" class="chatbox-close" onclick="toggleChatbox()">
                ×
            </button>
        </div>

        <div id="chatboxMessages" class="chatbox-messages"></div>

        <div class="chatbox-actions">
            <span class="chatbox-note">Tin nhắn lưu trên trình duyệt</span>
            <button type="button" class="chatbox-clear" onclick="clearChatboxMessages()">
                Xóa lịch sử
            </button>
        </div>

        <form class="chatbox-form" onsubmit="sendChatboxMessage(event)">
            <input
                id="chatboxInput"
                class="chatbox-input"
                type="text"
                placeholder="Nhập tin nhắn..."
                autocomplete="off"
            >

            <button type="submit" class="chatbox-send">
                Gửi
            </button>
        </form>
    </div>

    <button type="button" class="chatbox-toggle" onclick="toggleChatbox()">
        💬
    </button>
</div>

<script>
    const CHATBOX_STORAGE_KEY = 'mon_ngon_chatbox_messages';

    function getChatboxMessages() {
        try {
            return JSON.parse(localStorage.getItem(CHATBOX_STORAGE_KEY)) || [];
        } catch (error) {
            return [];
        }
    }

    function saveChatboxMessages(messages) {
        localStorage.setItem(CHATBOX_STORAGE_KEY, JSON.stringify(messages));
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function renderChatboxMessages() {
        const box = document.getElementById('chatboxMessages');
        if (!box) return;

        let messages = getChatboxMessages();

        if (messages.length === 0) {
            messages = [
                {
                    sender: 'bot',
                    message: 'Xin chào! Bạn cần hỗ trợ về món ăn, đơn hàng hay thanh toán ạ?',
                    time: new Date().toISOString()
                }
            ];

            saveChatboxMessages(messages);
        }

        box.innerHTML = messages.map(item => {
            const sender = item.sender === 'user' ? 'user' : 'bot';

            return `
                <div class="chat-message-row ${sender}">
                    <div class="chat-message ${sender}">
                        ${escapeHtml(item.message)}
                    </div>
                </div>
            `;
        }).join('');

        box.scrollTop = box.scrollHeight;
    }

    function toggleChatbox() {
        const panel = document.getElementById('chatboxPanel');
        if (!panel) return;

        panel.classList.toggle('active');
        renderChatboxMessages();

        setTimeout(() => {
            const input = document.getElementById('chatboxInput');
            if (panel.classList.contains('active') && input) {
                input.focus();
            }
        }, 100);
    }

    function sendChatboxMessage(event) {
        event.preventDefault();

        const input = document.getElementById('chatboxInput');
        if (!input) return;

        const message = input.value.trim();
        if (!message) return;

        const messages = getChatboxMessages();

        messages.push({
            sender: 'user',
            message: message,
            time: new Date().toISOString()
        });

        messages.push({
            sender: 'bot',
            message: getAutoReply(message),
            time: new Date().toISOString()
        });

        saveChatboxMessages(messages);

        input.value = '';
        renderChatboxMessages();
    }

    function getAutoReply(message) {
    const text = message.toLowerCase();

    const bestSellers = [
        'Cơm gà xối mỡ',
        'Bún bò Huế',
        'Gà viên chiên',
        'Trà đào'
    ];

    if (
        text.includes('không biết ăn gì') ||
        text.includes('ăn gì') ||
        text.includes('gợi ý') ||
        text.includes('gợi ý món') ||
        text.includes('món nào ngon') ||
        text.includes('món ngon') ||
        text.includes('bán chạy') ||
        text.includes('best seller') ||
        text.includes('bestseller') ||
        text.includes('nên ăn gì')
    ) {
        return 'Bạn có thể tham khảo các món bán chạy của quán: ' + bestSellers.join(', ') + '.';
    }

    if (text.includes('đơn') || text.includes('order')) {
        return 'Bạn có thể vào mục Lịch sử mua hàng để kiểm tra trạng thái đơn hàng nhé.';
    }

    if (text.includes('thanh toán') || text.includes('vnpay') || text.includes('bank')) {
        return 'Hệ thống hỗ trợ thanh toán COD, chuyển khoản và VNPay tùy cấu hình của cửa hàng.';
    }

    if (text.includes('món') || text.includes('sản phẩm') || text.includes('đồ ăn')) {
        return 'Bạn có thể xem danh sách món ăn tại trang chủ và lọc theo danh mục để chọn món phù hợp.';
    }

    if (text.includes('ship') || text.includes('giao hàng')) {
        return 'Sau khi đặt hàng, cửa hàng sẽ tiếp nhận, chuẩn bị món và giao hàng theo thông tin bạn cung cấp.';
    }

    return 'Cảm ơn bạn đã nhắn tin. Nhân viên sẽ hỗ trợ bạn trong thời gian sớm nhất!';
}

    function clearChatboxMessages() {
        localStorage.removeItem(CHATBOX_STORAGE_KEY);
        renderChatboxMessages();
    }

    document.addEventListener('DOMContentLoaded', renderChatboxMessages);
</script>