<!-- Gemini AI Floating Chatbot Assistant Widget -->
<style>
    .ai-widget-container {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99999;
        font-family: 'Inter', sans-serif;
    }
    .ai-widget-trigger {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 50%, #D97706 100%);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.35);
        border: 2px solid rgba(245, 158, 11, 0.4);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .ai-widget-trigger:hover {
        transform: scale(1.08) rotate(5deg);
        box-shadow: 0 12px 30px rgba(217, 119, 6, 0.45);
    }
    .ai-chat-panel {
        display: none;
        width: 380px;
        max-width: calc(100vw - 32px);
        height: 520px;
        max-height: calc(100vh - 100px);
        background: #FFFFFF;
        border-radius: 20px;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.25);
        border: 1px solid rgba(226, 232, 240, 0.9);
        overflow: hidden;
        flex-direction: column;
        position: absolute;
        bottom: 74px;
        right: 0;
        animation: aiSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes aiSlideUp {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .ai-chat-header {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
        color: #FFFFFF;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ai-chat-body {
        flex: 1;
        padding: 16px;
        overflow-y: auto;
        background: #F8FAFC;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .ai-msg {
        max-width: 85%;
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 0.875rem;
        line-height: 1.45;
    }
    .ai-msg-user {
        align-self: flex-end;
        background: #0F172A;
        color: #FFFFFF;
        border-bottom-right-radius: 4px;
    }
    .ai-msg-bot {
        align-self: flex-start;
        background: #FFFFFF;
        color: #0F172A;
        border: 1px solid #E2E8F0;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .ai-quick-btn {
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        color: #334155;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .ai-quick-btn:hover {
        background: #0F172A;
        color: #FFFFFF;
        border-color: #0F172A;
    }
    .ai-chat-footer {
        padding: 12px;
        background: #FFFFFF;
        border-top: 1px solid #E2E8F0;
        display: flex;
        gap: 8px;
    }
</style>

<div class="ai-widget-container">
    <!-- Chat Floating Panel -->
    <div class="ai-chat-panel" id="aiChatPanel">
        <div class="ai-chat-header">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle bg-warning bg-opacity-20 p-2 d-flex align-items-center justify-content-center" style="width:34px; height:34px;">
                    <i class="bi bi-stars text-warning fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-white" style="font-size: 0.95rem;">Gemini AI Assistant</h6>
                    <small class="text-success" style="font-size: 0.7rem;"><i class="bi bi-circle-fill me-1"></i> Live Portal Context</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white shadow-none" id="aiChatClose"></button>
        </div>

        <div class="ai-chat-body" id="aiChatBody">
            <div class="ai-msg ai-msg-bot">
                <strong>Hello! 👋 I am your Gemini AI Assistant.</strong><br>
                How can I help you navigate CapacityConnect LMS, courses, certifications, or disaster drills today?
            </div>

            <!-- Quick Suggestions -->
            <div class="d-flex flex-wrap gap-1 mt-1 mb-2" id="aiQuickPrompts">
                <button type="button" class="ai-quick-btn" onclick="sendAiPrompt('How do I enroll in a course?')">Enroll in a course</button>
                <button type="button" class="ai-quick-btn" onclick="sendAiPrompt('How do I verify a certificate?')">Verify Certificate</button>
                <button type="button" class="ai-quick-btn" onclick="sendAiPrompt('Tell me about disaster response drills')">Disaster Drills</button>
                <button type="button" class="ai-quick-btn" onclick="startPortalTour(); toggleAiChat();">Start Portal Tour</button>
            </div>
        </div>

        <div class="ai-chat-footer">
            <input type="text" id="aiChatInput" class="form-control form-control-sm rounded-pill px-3 border" placeholder="Ask Gemini AI anything..." onkeydown="if(event.key==='Enter') triggerSendAiChat();">
            <button type="button" class="btn btn-warning btn-sm rounded-circle px-2" onclick="triggerSendAiChat()" style="width:36px; height:36px;">
                <i class="bi bi-send-fill text-dark"></i>
            </button>
        </div>
    </div>

    <!-- Widget Floating Trigger Icon -->
    <button type="button" class="ai-widget-trigger" id="aiWidgetTrigger" title="Gemini AI Assistant & Portal Guide" onclick="toggleAiChat()">
        <i class="bi bi-robot"></i>
    </button>
</div>

<script>
    function toggleAiChat() {
        const panel = document.getElementById('aiChatPanel');
        if (!panel) return;
        if (panel.style.display === 'flex') {
            panel.style.display = 'none';
        } else {
            panel.style.display = 'flex';
            const input = document.getElementById('aiChatInput');
            if (input) input.focus();
        }
    }
    window.toggleAiChat = toggleAiChat;
    window.toggleAiAssistant = toggleAiChat;

    document.getElementById('aiChatClose').addEventListener('click', () => {
        document.getElementById('aiChatPanel').style.display = 'none';
    });

    function sendAiPrompt(text) {
        document.getElementById('aiChatInput').value = text;
        triggerSendAiChat();
    }

    function triggerSendAiChat() {
        const input = document.getElementById('aiChatInput');
        const message = input.value.trim();
        if (!message) return;

        const body = document.getElementById('aiChatBody');
        
        // Append User Message
        const userDiv = document.createElement('div');
        userDiv.className = 'ai-msg ai-msg-user';
        userDiv.textContent = message;
        body.appendChild(userDiv);

        input.value = '';
        body.scrollTop = body.scrollHeight;

        // Loading Indicator
        const loadingDiv = document.createElement('div');
        loadingDiv.className = 'ai-msg ai-msg-bot text-muted small italic';
        loadingDiv.id = 'aiLoadingBubble';
        loadingDiv.innerHTML = '<i class="bi bi-arrow-repeat spin me-1"></i> Gemini AI is thinking...';
        body.appendChild(loadingDiv);
        body.scrollTop = body.scrollHeight;

        fetch('{{ route("ai.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: message })
        })
        .then(res => res.json())
        .then(data => {
            const loading = document.getElementById('aiLoadingBubble');
            if (loading) loading.remove();

            const botDiv = document.createElement('div');
            botDiv.className = 'ai-msg ai-msg-bot';
            botDiv.innerHTML = data.reply ? data.reply.replace(/\n/g, '<br>') : 'I am here to assist you with CapacityConnect LMS.';
            body.appendChild(botDiv);
            body.scrollTop = body.scrollHeight;
        })
        .catch(err => {
            const loading = document.getElementById('aiLoadingBubble');
            if (loading) loading.remove();

            const botDiv = document.createElement('div');
            botDiv.className = 'ai-msg ai-msg-bot text-danger';
            botDiv.textContent = 'Network connection interrupted. Please try again.';
            body.appendChild(botDiv);
            body.scrollTop = body.scrollHeight;
        });
    }
</script>
