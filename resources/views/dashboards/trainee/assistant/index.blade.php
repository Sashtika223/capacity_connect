@extends('layouts.trainee')

@section('title', 'AI Learning Assistant')

@section('trainee_content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="bi bi-robot text-primary me-2"></i> Learning Assistant</h2>
        <p class="text-muted mb-0">Ask questions, discover courses, and get explanations for your learning journey.</p>
    </div>
</div>

<div class="row h-100">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm rounded-4 h-100 d-flex flex-column" style="min-height: 600px;">
            
            <!-- Chat Window -->
            <div id="chat-window" class="card-body p-4 overflow-auto flex-grow-1" style="background-color: #f8f9fa;">
                
                <!-- Welcome Message -->
                <div class="d-flex mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 45px; height: 45px;">
                        <i class="bi bi-robot"></i>
                    </div>
                    <div class="bg-white border rounded-4 p-3 shadow-sm" style="max-width: 80%;">
                        <p class="mb-0">Hello {{ auth()->user()->name }}! I am your AI Learning Assistant. How can I help you today?</p>
                        
                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <button class="btn btn-sm btn-outline-primary rounded-pill quick-prompt">What courses should I take next?</button>
                            <button class="btn btn-sm btn-outline-primary rounded-pill quick-prompt">Explain a concept from my last course.</button>
                            <button class="btn btn-sm btn-outline-primary rounded-pill quick-prompt">I need help with my upcoming assessment.</button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Input Area -->
            <div class="card-footer bg-white border-top p-3">
                <form id="chat-form" class="d-flex align-items-center">
                    <input type="text" id="chat-input" class="form-control rounded-pill px-4 py-2 me-2" placeholder="Message the Learning Assistant..." required autocomplete="off">
                    <button type="submit" id="chat-submit" class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('chat-form');
        const input = document.getElementById('chat-input');
        const submitBtn = document.getElementById('chat-submit');
        const chatWindow = document.getElementById('chat-window');
        const quickPrompts = document.querySelectorAll('.quick-prompt');

        function addMessage(content, isUser = false, isError = false) {
            const wrapper = document.createElement('div');
            wrapper.className = `d-flex mb-4 ${isUser ? 'justify-content-end' : ''}`;
            
            let iconHtml = '';
            if (!isUser) {
                iconHtml = `
                <div class="${isError ? 'bg-danger' : 'bg-primary'} text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 45px; height: 45px;">
                    <i class="bi bi-${isError ? 'exclamation-triangle' : 'robot'}"></i>
                </div>`;
            }

            const bgClass = isUser ? 'bg-primary text-white' : (isError ? 'bg-danger-subtle text-danger border-danger' : 'bg-white border');
            
            wrapper.innerHTML = `
                ${!isUser ? iconHtml : ''}
                <div class="${bgClass} rounded-4 p-3 shadow-sm" style="max-width: 80%;">
                    <p class="mb-0">${content}</p>
                </div>
            `;
            
            chatWindow.appendChild(wrapper);
            chatWindow.scrollTop = chatWindow.scrollHeight;
        }

        async function sendMessage(prompt) {
            if (!prompt.trim()) return;
            
            // 1. Show user message
            addMessage(prompt, true);
            input.value = '';
            
            // 2. Disable input while loading
            input.disabled = true;
            submitBtn.disabled = true;
            
            // 3. Add loading indicator
            const loadingId = 'loading-' + Date.now();
            const loadingWrapper = document.createElement('div');
            loadingWrapper.id = loadingId;
            loadingWrapper.className = 'd-flex mb-4';
            loadingWrapper.innerHTML = `
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 45px; height: 45px;">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="bg-white border rounded-4 p-3 shadow-sm d-flex align-items-center">
                    <div class="spinner-grow spinner-grow-sm text-primary me-2" role="status"></div>
                    <span class="text-muted small">Thinking...</span>
                </div>
            `;
            chatWindow.appendChild(loadingWrapper);
            chatWindow.scrollTop = chatWindow.scrollHeight;

            try {
                const response = await fetch('{{ route('trainee.assistant.chat') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ prompt: prompt })
                });

                const data = await response.json();
                
                // Remove loading indicator
                document.getElementById(loadingId).remove();

                if (response.ok && data.success) {
                    addMessage(data.message, false);
                } else if (response.status === 503 && data.error === 'not_configured') {
                    // Explicit handling for missing API keys
                    addMessage(`<strong>Service Unavailable:</strong> ${data.message}`, false, true);
                } else {
                    addMessage(data.message || 'An error occurred while connecting to the AI.', false, true);
                }

            } catch (error) {
                document.getElementById(loadingId).remove();
                addMessage('Network error. Please check your connection and try again.', false, true);
            } finally {
                input.disabled = false;
                submitBtn.disabled = false;
                input.focus();
            }
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage(input.value);
        });

        quickPrompts.forEach(btn => {
            btn.addEventListener('click', function() {
                sendMessage(this.innerText);
            });
        });
    });
</script>
@endsection
