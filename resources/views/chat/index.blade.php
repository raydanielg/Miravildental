@extends('layouts.dashboard')

@section('title', 'Team Chat - ' . config('app.name', 'Laravel'))
@section('page_title', 'Team Chat')

@section('content')
<div id="wa-chat-app" class="h-[calc(100vh-8rem)] bg-[#e5ddd5] rounded-xl shadow-sm border border-gray-200 flex overflow-hidden animate-fade relative">
    {{-- WhatsApp-style left sidebar --}}
    <div class="w-full md:w-[360px] bg-[#f0f2f5] border-r border-[#d1d7db] flex flex-col z-10 md:relative absolute md:static inset-0 md:inset-auto transition-transform duration-300" id="chatSidebar">
        {{-- Header --}}
        <div class="h-14 bg-[#f0f2f5] px-4 flex items-center justify-between border-b border-[#d1d7db]">
            <div class="flex items-center gap-3">
                <img src="{{ asset('chat.png') }}" alt="Team Chat" class="w-7 h-7 object-contain">
                <h2 class="font-bold text-[#111b21] text-base">Team Chat</h2>
            </div>
            <span class="text-xs text-[#54656f] font-medium">{{ auth()->user()->name }}</span>
        </div>

        {{-- Users list --}}
        <div class="flex-1 overflow-y-auto" id="chatUsersList">
            @forelse ($users as $u)
            <button type="button"
                    class="chat-user-btn w-full flex items-center gap-3 px-4 py-3 hover:bg-[#f5f6f6] transition-colors border-b border-[#e9edef] text-left {{ $u->conversation_id ? '' : 'new-chat' }}"
                    data-user-id="{{ $u->id }}"
                    data-user-name="{{ $u->name }}"
                    data-conversation-id="{{ $u->conversation_id ?? '' }}"
                    data-online="{{ $u->isOnline() ? '1' : '0' }}">
                <div class="relative shrink-0">
                    <div class="w-10 h-10 rounded-full bg-[#dfe5e7] text-[#54656f] flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr($u->name, 0, 2)) }}
                    </div>
                    <span class="absolute top-0 right-0 w-3 h-3 {{ $u->isOnline() ? 'bg-emerald-500' : 'bg-red-500' }} border-2 border-[#f0f2f5] rounded-full"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-sm text-[#111b21] truncate">{{ $u->name }}</h3>
                        @if($u->last_message_at)
                            <span class="text-[11px] text-[#667781]">{{ $u->last_message_at->diffForHumans() }}</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between mt-0.5">
                        <p class="text-xs text-[#667781] truncate w-4/5">{{ $u->last_message ?? 'Click to start chatting' }}</p>
                        @if($u->unread_count > 0)
                            <span class="unread-counter px-1.5 py-0.5 min-w-[20px] h-5 bg-emerald-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $u->unread_count }}</span>
                        @endif
                    </div>
                </div>
            </button>
            @empty
                <div class="text-center py-10 text-[#667781] text-sm">
                    <img src="{{ asset('chat.png') }}" alt="Team Chat" class="w-12 h-12 object-contain mx-auto mb-2 opacity-60">
                    No staff members found
                </div>
            @endforelse
        </div>
    </div>

    {{-- Mobile sidebar toggle --}}
    <button type="button" id="mobileBackBtn" class="hidden absolute top-3 left-3 z-30 w-9 h-9 bg-white/90 rounded-full shadow items-center justify-center text-[#111b21]">
        <i class="fa-solid fa-arrow-left"></i>
    </button>

    {{-- Main chat area --}}
    <div class="flex-1 flex flex-col bg-[#e5ddd5] bg-[url('{{ asset('flat-abstract-background-pattern-vector_822782-866.jpg') }}')] bg-cover bg-center bg-no-repeat bg-fixed relative" id="chatMainArea">
        <div class="absolute inset-0 bg-white/80 pointer-events-none"></div>

        {{-- Default empty state --}}
        <div id="chatEmptyState" class="relative z-10 flex-1 flex flex-col items-center justify-center text-center p-8">
            <div class="w-24 h-24 bg-[#00a884]/10 rounded-full flex items-center justify-center mb-5 animate-bounce">
                <img src="{{ asset('chat.png') }}" alt="Team Chat" class="w-14 h-14 object-contain">
            </div>
            <h3 class="text-xl font-bold text-[#41525d]">Team Chat</h3>
            <p class="text-sm text-[#667781] mt-2 max-w-xs">Select a staff member from the left to start a real-time conversation.</p>
            <p class="text-xs text-[#8696a0] mt-6 flex items-center gap-1.5">
                <i class="fa-solid fa-lock text-[10px]"></i>
                Internal clinic messaging
            </p>
        </div>

        {{-- Active chat --}}
        <div id="chatActiveArea" class="relative z-10 hidden flex-col h-full">
            {{-- Chat header --}}
            <div class="h-14 bg-[#f0f2f5] px-4 flex items-center justify-between border-b border-[#d1d7db] z-10">
                <div class="flex items-center gap-3">
                    <button type="button" class="chat-mobile-back md:hidden w-8 h-8 flex items-center justify-center text-[#54656f] hover:text-[#111b21]">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    <div class="relative">
                        <div id="chatHeaderAvatar" class="w-10 h-10 rounded-full bg-[#dfe5e7] text-[#54656f] flex items-center justify-center font-bold text-xs">ST</div>
                        <span id="chatHeaderStatus" class="absolute top-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-[#f0f2f5] rounded-full"></span>
                    </div>
                    <div>
                        <h3 id="chatHeaderName" class="font-bold text-sm text-[#111b21]">Staff Name</h3>
                        <p id="chatHeaderStatusText" class="text-xs text-emerald-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                            online
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a id="chatHeaderAppointmentBtn" href="{{ route('appointments.create') }}"
                       class="hidden items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-emerald-500 hover:text-white text-emerald-600 text-xs font-medium rounded-full shadow-sm border border-emerald-200 transition-colors"
                       title="Book appointment">
                        <i class="fa-solid fa-calendar-plus"></i>
                        <span class="hidden sm:inline">Book</span>
                    </a>
                </div>
            </div>

            {{-- Messages --}}
            <div id="chatMessagesArea" class="flex-1 overflow-y-auto p-4 space-y-2" data-last-id="0">
                {{-- loaded via AJAX --}}
            </div>

            {{-- Input --}}
            <div class="p-3 bg-[#f0f2f5] border-t border-[#d1d7db]">
                <form id="chatMessageForm" class="flex items-center gap-2">
                    @csrf
                    <div class="flex items-center gap-1.5">
                        <button type="button" id="chatFileBtn"
                            class="group w-10 h-10 bg-white hover:bg-gradient-to-br hover:from-[#00a884] hover:to-[#008f72] rounded-full shadow-sm border border-slate-200 hover:border-[#00a884] hover:shadow-md flex items-center justify-center transition-all duration-200 hover:scale-105 overflow-hidden"
                            title="Attach file">
                            <img src="{{ asset('plus.png') }}" alt="Attach" class="w-5 h-5 object-contain group-hover:scale-110 transition-transform">
                        </button>
                        <input type="file" id="chatFileInput" class="hidden" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" multiple>
                        <button type="button" id="chatVoiceBtn"
                            class="group w-10 h-10 bg-white hover:bg-gradient-to-br hover:from-red-500 hover:to-red-600 rounded-full shadow-sm border border-slate-200 hover:border-red-400 hover:shadow-md flex items-center justify-center transition-all duration-200 hover:scale-105 overflow-hidden"
                            title="Voice message">
                            <img src="{{ asset('microphone.png') }}" alt="Voice" class="w-5 h-5 object-contain group-hover:scale-110 transition-transform">
                        </button>
                    </div>
                    <input type="text" id="chatMessageInput" autocomplete="off"
                        class="flex-1 bg-white text-sm text-[#111b21] px-4 py-2.5 rounded-full border-none outline-none focus:ring-2 focus:ring-[#00a884] placeholder-[#667781]"
                        placeholder="Type a message..." maxlength="2000">
                    <button type="submit" id="chatMessageSend"
                        class="group w-10 h-10 bg-white hover:bg-slate-50 rounded-full shadow-md hover:shadow-lg border border-slate-200 flex items-center justify-center transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:hover:scale-100 overflow-hidden"
                        title="Send message">
                        <img src="{{ asset('send.gif') }}" alt="Send" class="w-5 h-5 object-contain">
                    </button>
                </form>
                <div id="voiceRecordingIndicator" class="hidden mt-2">
                    <div class="flex items-center justify-center gap-3 bg-red-50 rounded-full px-4 py-2 mx-auto w-max">
                        <span class="inline-flex items-center gap-2 text-xs text-red-600 font-semibold">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            Recording...
                        </span>
                        <div id="recordingWaveform" class="flex items-center gap-[2px] h-5">
                            {{-- animated bars --}}
                        </div>
                        <span id="recordingTimer" class="text-xs font-mono text-red-600">0:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- File Viewer Modal --}}
<div id="fileViewerModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 p-0 sm:p-4">
    <div class="bg-white sm:rounded-xl rounded-none shadow-2xl w-full sm:max-w-5xl h-full sm:h-[90vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
            <div class="flex items-center gap-3 min-w-0">
                <div id="fileViewerIconBox" class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center shrink-0">
                    <i id="fileViewerIcon" class="fa-solid fa-file text-slate-500"></i>
                </div>
                <div class="min-w-0">
                    <h3 id="fileViewerTitle" class="font-semibold text-sm text-slate-800 truncate">File Preview</h3>
                    <p id="fileViewerSubtitle" class="text-xs text-slate-400 truncate">Document</p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a id="fileViewerDownload" href="#" download class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium text-white bg-[#00a884] hover:bg-[#008f72] rounded-lg transition-colors">
                    <i class="fa-solid fa-download"></i>
                    <span class="hidden sm:inline">Download</span>
                </a>
                <button type="button" id="fileViewerClose" class="w-9 h-9 rounded-full hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>
        <div class="flex-1 bg-slate-200 flex items-center justify-center overflow-auto">
            <img id="fileViewerImage" src="" alt="File preview" class="max-w-full max-h-full object-contain hidden shadow-lg">
            <iframe id="fileViewerFrame" src="" class="w-full h-full border-0 hidden bg-white"></iframe>
            <div id="fileViewerUnsupported" class="hidden text-center py-20">
                <div class="w-20 h-20 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-file text-5xl text-slate-300"></i>
                </div>
                <p class="text-sm text-slate-500 mb-1">Preview not available for this file type</p>
                <p id="fileViewerUnsupportedName" class="text-xs text-slate-400 mb-5"></p>
                <a id="fileViewerUnsupportedDownload" href="#" download class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#00a884] text-white text-sm font-medium rounded-lg hover:bg-[#008f72] transition-colors shadow-md">
                    <i class="fa-solid fa-download"></i>
                    Download file
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const app = document.getElementById('wa-chat-app');
    if (!app) return;

    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    let currentConversationId = null;
    let pollInterval = null;
    let lastId = 0;

    const sidebar = document.getElementById('chatSidebar');
    const mainArea = document.getElementById('chatMainArea');
    const emptyState = document.getElementById('chatEmptyState');
    const activeArea = document.getElementById('chatActiveArea');
    const messagesArea = document.getElementById('chatMessagesArea');
    const headerName = document.getElementById('chatHeaderName');
    const headerAvatar = document.getElementById('chatHeaderAvatar');
    const headerStatus = document.getElementById('chatHeaderStatus');
    const headerStatusText = document.getElementById('chatHeaderStatusText');
    const headerAppointmentBtn = document.getElementById('chatHeaderAppointmentBtn');
    const input = document.getElementById('chatMessageInput');
    const form = document.getElementById('chatMessageForm');
    const sendBtn = document.getElementById('chatMessageSend');
    const fileBtn = document.getElementById('chatFileBtn');
    const fileInput = document.getElementById('chatFileInput');
    const voiceBtn = document.getElementById('chatVoiceBtn');
    const voiceIndicator = document.getElementById('voiceRecordingIndicator');
    const recordingWaveform = document.getElementById('recordingWaveform');
    const recordingTimer = document.getElementById('recordingTimer');
    const mobileBackBtn = document.getElementById('mobileBackBtn');
    const fileViewerModal = document.getElementById('fileViewerModal');
    const fileViewerFrame = document.getElementById('fileViewerFrame');
    const fileViewerImage = document.getElementById('fileViewerImage');
    const fileViewerTitle = document.getElementById('fileViewerTitle');
    const fileViewerSubtitle = document.getElementById('fileViewerSubtitle');
    const fileViewerIcon = document.getElementById('fileViewerIcon');
    const fileViewerDownload = document.getElementById('fileViewerDownload');
    const fileViewerClose = document.getElementById('fileViewerClose');
    const fileViewerUnsupported = document.getElementById('fileViewerUnsupported');
    const fileViewerUnsupportedDownload = document.getElementById('fileViewerUnsupportedDownload');
    const fileViewerUnsupportedName = document.getElementById('fileViewerUnsupportedName');
    let mediaRecorder = null;
    let audioChunks = [];
    let isRecording = false;
    let recordingTimerInterval = null;
    let recordingWaveformInterval = null;

    function scrollBottom() {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }

    function showActive() {
        emptyState.classList.add('hidden');
        activeArea.classList.remove('hidden');
        activeArea.classList.add('flex');
        if (window.innerWidth < 768) {
            sidebar.classList.add('-translate-x-full');
            mobileBackBtn.classList.remove('hidden');
            mobileBackBtn.classList.add('flex');
        }
    }

    function showSidebar() {
        if (window.innerWidth < 768) {
            sidebar.classList.remove('-translate-x-full');
            mobileBackBtn.classList.add('hidden');
            mobileBackBtn.classList.remove('flex');
        }
    }

    mobileBackBtn?.addEventListener('click', () => {
        currentConversationId = null;
        showSidebar();
        emptyState.classList.remove('hidden');
        activeArea.classList.add('hidden');
        activeArea.classList.remove('flex');
        clearInterval(pollInterval);
    });

    document.querySelector('.chat-mobile-back')?.addEventListener('click', () => {
        mobileBackBtn?.dispatchEvent(new Event('click'));
    });

    async function loadConversation(conversationId) {
        messagesArea.innerHTML = '<div class="text-center py-8 text-[#667781] text-sm"><i class="fa-solid fa-circle-notch fa-spin mr-1"></i> Loading...</div>';
        showActive();

        try {
            const res = await fetch(`{{ url('chat') }}/${conversationId}/ajax`, {
                headers: { 'Accept': 'text/html', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await res.text();
            messagesArea.innerHTML = html;
            lastId = Math.max(...Array.from(messagesArea.querySelectorAll('.message-item')).map(el => parseInt(el.dataset.id) || 0), 0);
            scrollBottom();
            startPolling();
            updateCounters();
        } catch (e) {
            messagesArea.innerHTML = '<div class="text-center py-8 text-red-500 text-sm">Failed to load chat.</div>';
        }
    }

    async function startChat(userId, name) {
        let conversationId = document.querySelector(`.chat-user-btn[data-user-id="${userId}"]`)?.dataset.conversationId;

        const selectedBtn = document.querySelector(`.chat-user-btn[data-user-id="${userId}"]`);
        const isOnline = selectedBtn?.dataset.online === '1';

        headerName.textContent = name;
        headerAvatar.textContent = name.substring(0, 2).toUpperCase();
        headerStatus.className = `absolute top-0 right-0 w-3 h-3 border-2 border-[#f0f2f5] rounded-full ${isOnline ? 'bg-emerald-500' : 'bg-red-500'}`;
        headerStatusText.innerHTML = `
            <span class="w-1.5 h-1.5 ${isOnline ? 'bg-emerald-500' : 'bg-red-500'} rounded-full ${isOnline ? 'animate-pulse' : ''}"></span>
            ${isOnline ? 'online' : 'offline'}`;
        headerStatusText.className = `text-xs flex items-center gap-1 ${isOnline ? 'text-emerald-600' : 'text-red-500'}`;

        if (headerAppointmentBtn) {
            headerAppointmentBtn.href = `{{ route('appointments.create') }}?user_id=${userId}`;
            headerAppointmentBtn.classList.remove('hidden');
            headerAppointmentBtn.classList.add('inline-flex');
        }

        if (!conversationId) {
            try {
                const res = await fetch('{{ route('chat.ajax.start') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ user_id: userId })
                });
                const data = await res.json();
                conversationId = data.conversation_id;
                document.querySelector(`.chat-user-btn[data-user-id="${userId}"]`)?.setAttribute('data-conversation-id', conversationId);
            } catch (e) {
                console.error('Start chat error', e);
                return;
            }
        }

        currentConversationId = conversationId;
        document.querySelectorAll('.chat-user-btn').forEach(b => {
            b.classList.remove('bg-[#f5f6f6]', 'border-l-4', 'border-l-[#00a884]');
        });
        const activeBtn = document.querySelector(`.chat-user-btn[data-user-id="${userId}"]`);
        activeBtn?.classList.add('bg-[#f5f6f6]', 'border-l-4', 'border-l-[#00a884]');

        await loadConversation(conversationId);
    }

    document.querySelectorAll('.chat-user-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            startChat(btn.dataset.userId, btn.dataset.userName);
        });
    });

    async function sendMessage(e) {
        e.preventDefault();
        const body = input.value.trim();
        if (!body || !currentConversationId) return;

        sendBtn.disabled = true;
        input.disabled = true;

        try {
            const res = await fetch(`{{ url('chat') }}/${currentConversationId}/send`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ body })
            });
            if (res.ok) {
                const data = await res.json();
                appendMessage(data.message, true);
                if (data.message.id > lastId) lastId = data.message.id;
                input.value = '';
                updateUserRow(currentConversationId, body, data.message.created_at);
            }
        } catch (err) {
            console.error('Send error', err);
        } finally {
            sendBtn.disabled = false;
            input.disabled = false;
            input.focus();
        }
    }

    form.addEventListener('submit', sendMessage);

    fileBtn?.addEventListener('click', () => fileInput?.click());
    fileInput?.addEventListener('change', () => {
        const files = Array.from(fileInput.files || []);
        if (!files.length) return;

        if (files.length > 1 || files.every(f => isImageFile(f.name))) {
            uploadGallery(files);
        } else {
            uploadFile(files[0]);
        }
        fileInput.value = '';
    });

    function isImageFile(name) {
        return /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(name);
    }

    async function uploadFile(file) {
        if (!currentConversationId) return;
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', token);

        try {
            const res = await fetch(`{{ url('chat') }}/${currentConversationId}/file`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (res.ok) {
                const data = await res.json();
                appendMessage(data.message, true);
                if (data.message.id > lastId) lastId = data.message.id;
                updateUserRow(currentConversationId, 'File: ' + data.message.file_name, data.message.created_at);
            }
        } catch (err) {
            console.error('File upload error', err);
        }
    }

    async function uploadGallery(files) {
        if (!currentConversationId) return;
        const formData = new FormData();
        files.forEach(f => formData.append('images[]', f));
        formData.append('_token', token);

        try {
            const res = await fetch(`{{ url('chat') }}/${currentConversationId}/gallery`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (res.ok) {
                const data = await res.json();
                appendMessage(data.message, true);
                if (data.message.id > lastId) lastId = data.message.id;
                updateUserRow(currentConversationId, `Gallery (${data.message.gallery_paths?.length || 1})`, data.message.created_at);
            }
        } catch (err) {
            console.error('Gallery upload error', err);
        }
    }

    voiceBtn?.addEventListener('click', () => {
        if (!isRecording) {
            startRecording();
        } else {
            stopRecording();
        }
    });

    async function startRecording() {
        if (!navigator.mediaDevices || !window.MediaRecorder) {
            alert('Voice recording is not supported in this browser.');
            return;
        }
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];

            mediaRecorder.ondataavailable = e => {
                if (e.data.size > 0) audioChunks.push(e.data);
            };

            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                uploadVoice(audioBlob);
                stream.getTracks().forEach(t => t.stop());
            };

            mediaRecorder.start();
            isRecording = true;
            voiceBtn.classList.add('bg-red-500', 'text-white');
            voiceBtn.classList.remove('bg-white', 'text-slate-600');
            voiceIndicator.classList.remove('hidden');
            startRecordingWaveform();
            startRecordingTimer();
        } catch (err) {
            console.error('Mic error', err);
            alert('Could not access microphone.');
        }
    }

    function stopRecording() {
        if (mediaRecorder && isRecording) {
            mediaRecorder.stop();
            isRecording = false;
            voiceBtn.classList.remove('bg-red-500', 'text-white');
            voiceBtn.classList.add('bg-white', 'text-slate-600');
            voiceIndicator.classList.add('hidden');
            stopRecordingWaveform();
            stopRecordingTimer();
        }
    }

    function startRecordingWaveform() {
        const bars = Array.from({ length: 16 }, () => `<div class="recording-bar w-[3px] bg-red-400 rounded-full transition-all duration-100" style="height:6px"></div>`);
        recordingWaveform.innerHTML = bars.join('');
        recordingWaveformInterval = setInterval(() => {
            recordingWaveform.querySelectorAll('.recording-bar').forEach(bar => {
                const h = Math.floor(Math.random() * 14 + 4);
                bar.style.height = h + 'px';
            });
        }, 120);
    }

    function stopRecordingWaveform() {
        clearInterval(recordingWaveformInterval);
        recordingWaveform.innerHTML = '';
    }

    function startRecordingTimer() {
        let seconds = 0;
        recordingTimer.textContent = '0:00';
        recordingTimerInterval = setInterval(() => {
            seconds++;
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            recordingTimer.textContent = `${m}:${s.toString().padStart(2, '0')}`;
        }, 1000);
    }

    function stopRecordingTimer() {
        clearInterval(recordingTimerInterval);
        recordingTimer.textContent = '0:00';
    }

    async function uploadVoice(blob) {
        if (!currentConversationId) return;
        const formData = new FormData();
        formData.append('audio', blob, 'voice-message.webm');
        formData.append('_token', token);

        try {
            const res = await fetch(`{{ url('chat') }}/${currentConversationId}/voice`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (res.ok) {
                const data = await res.json();
                appendMessage(data.message, true);
                if (data.message.id > lastId) lastId = data.message.id;
                updateUserRow(currentConversationId, 'Voice message', data.message.created_at);
            }
        } catch (err) {
            console.error('Voice upload error', err);
        }
    }

    function appendMessage(msg, isMe) {
        if (document.querySelector(`.message-item[data-id="${msg.id}"]`)) return;

        const div = document.createElement('div');
        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        const myName = {{ Js::from(auth()->user()->name) }};

        const isVoice = !!msg.audio_path;
        const isFile = !!msg.file_path;
        const isGallery = !!msg.gallery_paths && msg.gallery_paths.length > 0;
        const isImage = isFile && !isGallery && /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(msg.file_name || '');
        const hasLink = !!msg.link_url;
        const audioUrl = isVoice ? `{{ asset('storage') }}/${msg.audio_path}` : '';
        const fileUrl = isFile ? `{{ asset('storage') }}/${msg.file_path}` : '';
        const galleryUrls = isGallery ? msg.gallery_paths.map(p => `{{ asset('storage') }}/${p}`) : [];

        const contentHtml = isVoice ? renderVoicePlayer(audioUrl, isMe)
            : isGallery ? renderGalleryGrid(galleryUrls, isMe)
            : isImage ? renderImageAttachment(fileUrl, msg.file_name, isMe)
            : isFile ? renderFileAttachment(fileUrl, msg.file_name, msg.file_size, isMe)
            : `<p class="text-sm ${isMe ? 'text-slate-800' : 'text-slate-700'}">${escapeHtml(msg.body)}</p>${hasLink ? renderLinkPreview(msg, isMe) : ''}`;

        if (isMe) {
            div.className = 'flex items-start gap-2.5 justify-end message-item';
            div.dataset.id = msg.id;
            div.innerHTML = `
                <div class="flex flex-col gap-1 w-full max-w-[320px]">
                    <div class="flex items-center space-x-1.5 justify-end">
                        <span class="text-sm font-semibold text-slate-900">${escapeHtml(myName)}</span>
                        <span class="text-xs text-slate-500">${time}</span>
                    </div>
                    <div class="flex flex-col leading-1.5 p-4 bg-[#dcf8c6] rounded-s-xl rounded-ee-xl rounded-es-xl shadow-sm">
                        ${contentHtml}
                    </div>
                    <div class="flex items-center justify-end gap-1">
                        <span class="text-xs font-medium text-slate-500 status-label">Sent</span>
                        <span class="tick-sent relative w-3 h-3 flex items-center justify-center">
                            <i class="fa-solid fa-check text-[10px] text-slate-400"></i>
                        </span>
                    </div>
                </div>
                <img class="w-8 h-8 rounded-full bg-slate-200 object-cover" src="{{ asset('logo.png') }}" alt="${escapeHtml(myName)}">`;
        } else {
            div.className = 'flex items-start gap-2.5 message-item';
            div.dataset.id = msg.id;
            div.innerHTML = `
                <img class="w-8 h-8 rounded-full bg-slate-200 object-cover" src="{{ asset('logo.png') }}" alt="${escapeHtml(msg.user.name)}">
                <div class="flex flex-col gap-1 w-full max-w-[320px]">
                    <div class="flex items-center space-x-1.5">
                        <span class="text-sm font-semibold text-slate-900">${escapeHtml(msg.user.name)}</span>
                        <span class="text-xs text-slate-500">${time}</span>
                    </div>
                    <div class="flex flex-col leading-1.5 p-4 bg-white rounded-e-xl rounded-es-xl shadow-sm border border-slate-100">
                        ${contentHtml}
                    </div>
                    <span class="text-xs text-slate-400">Received</span>
                </div>`;
        }
        messagesArea.appendChild(div);
        scrollBottom();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    let currentAudio = null;
    let currentPlayBtn = null;

    messagesArea.addEventListener('click', function(e) {
        const btn = e.target.closest('.voice-play-btn');
        if (btn) {
            toggleVoicePlay(btn);
            return;
        }
        const viewFile = e.target.closest('.view-file-btn');
        if (viewFile) {
            e.preventDefault();
            openFileViewer(viewFile.dataset.url, viewFile.dataset.name);
            return;
        }
        const viewImage = e.target.closest('.view-image-btn');
        if (viewImage) {
            e.preventDefault();
            openFileViewer(viewImage.dataset.url, viewImage.dataset.name);
            return;
        }
    });

    fileViewerClose?.addEventListener('click', closeFileViewer);
    fileViewerModal?.addEventListener('click', function(e) {
        if (e.target === fileViewerModal) closeFileViewer();
    });

    function openFileViewer(url, fileName) {
        const displayName = fileName || 'File Preview';
        fileViewerTitle.textContent = displayName;
        fileViewerDownload.href = url;
        fileViewerDownload.download = fileName || 'file';
        fileViewerUnsupportedDownload.href = url;
        fileViewerUnsupportedDownload.download = fileName || 'file';
        fileViewerUnsupportedName.textContent = displayName;

        const iconClass = getFileIcon(displayName);
        const fileType = getFileType(displayName);
        fileViewerIcon.className = 'fa-solid ' + iconClass;
        fileViewerSubtitle.textContent = fileType + ' Document';

        fileViewerImage.classList.add('hidden');
        fileViewerFrame.classList.add('hidden');
        fileViewerUnsupported.classList.add('hidden');

        const name = (fileName || url).toLowerCase();
        const isImage = /\.(jpg|jpeg|png|gif|webp|svg|bmp)$/i.test(name);
        const isPdf = /\.pdf$/i.test(name);
        const isPreviewable = /\.(txt|html?|svg)$/i.test(name);
        const isOffice = /\.(doc|docx|xls|xlsx|ppt|pptx)$/i.test(name);

        if (isImage) {
            fileViewerImage.src = url;
            fileViewerImage.classList.remove('hidden');
        } else if (isPdf) {
            fileViewerFrame.src = url;
            fileViewerFrame.classList.remove('hidden');
        } else if (isPreviewable) {
            fileViewerFrame.src = url;
            fileViewerFrame.classList.remove('hidden');
        } else if (isOffice) {
            fileViewerFrame.src = 'https://docs.google.com/gview?url=' + encodeURIComponent(window.location.origin + url) + '&embedded=true';
            fileViewerFrame.classList.remove('hidden');
        } else {
            fileViewerUnsupported.classList.remove('hidden');
        }

        fileViewerModal.classList.remove('hidden');
        fileViewerModal.classList.add('flex');
    }

    function closeFileViewer() {
        fileViewerModal.classList.add('hidden');
        fileViewerModal.classList.remove('flex');
        fileViewerFrame.src = '';
        fileViewerImage.src = '';
    }

    function toggleVoicePlay(btn) {
        const url = btn.dataset.url;
        const icon = btn.querySelector('i');

        if (currentAudio && currentAudio.src === url && !currentAudio.paused) {
            currentAudio.pause();
            icon.classList.remove('fa-pause');
            icon.classList.add('fa-play');
            return;
        }

        if (currentAudio) {
            currentAudio.pause();
            if (currentPlayBtn) {
                const prevIcon = currentPlayBtn.querySelector('i');
                prevIcon?.classList.remove('fa-pause');
                prevIcon?.classList.add('fa-play');
            }
        }

        currentAudio = new Audio(url);
        currentPlayBtn = btn;
        icon.classList.remove('fa-play');
        icon.classList.add('fa-pause');

        currentAudio.onended = () => {
            icon.classList.remove('fa-pause');
            icon.classList.add('fa-play');
            currentAudio = null;
            currentPlayBtn = null;
        };

        currentAudio.play().catch(err => {
            console.error('Audio play error', err);
            icon.classList.remove('fa-pause');
            icon.classList.add('fa-play');
        });
    }

    function renderGalleryGrid(urls, isMe) {
        const visible = urls.slice(0, 4);
        const remaining = urls.length - 4;
        return `
            <div class="grid grid-cols-2 gap-2 my-2.5">
                ${visible.map((url, idx) => `
                    <div class="group relative aspect-square overflow-hidden rounded-lg ${idx === 3 && remaining > 0 ? 'bg-slate-900/50' : ''} cursor-pointer">
                        ${idx === 3 && remaining > 0 ? `
                            <div class="absolute inset-0 flex items-center justify-center z-10">
                                <span class="text-xl font-medium text-white">+${remaining}</span>
                            </div>
                        ` : ''}
                        <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2 z-20">
                            <button type="button" class="view-image-btn inline-flex items-center justify-center rounded-full h-8 w-8 bg-white/30 hover:bg-white/50 transition-colors" data-url="${url}" data-name="gallery-image-${idx + 1}" title="View">
                                <i class="fa-solid fa-eye text-white text-xs"></i>
                            </button>
                            <a href="${url}" download class="inline-flex items-center justify-center rounded-full h-8 w-8 bg-white/30 hover:bg-white/50 transition-colors" title="Download">
                                <i class="fa-solid fa-download text-white text-xs"></i>
                            </a>
                        </div>
                        <img src="${url}" class="w-full h-full object-cover rounded-lg" alt="Gallery image">
                    </div>
                `).join('')}
            </div>`;
    }

    function renderLinkPreview(msg, isMe) {
        const hostname = msg.link_url ? new URL(msg.link_url).hostname : '';
        return `
            <a href="${escapeHtml(msg.link_url)}" target="_blank" rel="noopener" class="block bg-slate-100 rounded-lg p-3 mb-2 hover:bg-slate-200 transition-colors mt-2">
                ${msg.link_image ? `<img src="${escapeHtml(msg.link_image)}" alt="${escapeHtml(msg.link_title || '')}" class="w-full h-auto rounded-lg mb-2 object-cover max-h-40">` : ''}
                <span class="text-sm font-medium text-slate-900 line-clamp-1">${escapeHtml(msg.link_title || msg.link_url)}</span>
                ${msg.link_description ? `<p class="text-xs text-slate-500 mt-1 line-clamp-2">${escapeHtml(msg.link_description)}</p>` : ''}
                <p class="text-xs text-slate-400 mt-1">${escapeHtml(hostname)}</p>
            </a>`;
    }

    function renderImageAttachment(imageUrl, fileName, isMe) {
        return `
            <div class="group relative my-2.5 max-w-full cursor-pointer">
                <img src="${imageUrl}" alt="${escapeHtml(fileName)}" class="rounded-lg max-w-full h-auto object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center gap-2 z-10">
                    <button type="button" class="view-image-btn inline-flex items-center justify-center rounded-full h-10 w-10 bg-white/30 hover:bg-white/50 transition-colors" data-url="${imageUrl}" data-name="${escapeHtml(fileName)}" title="View">
                        <i class="fa-solid fa-eye text-white"></i>
                    </button>
                    <a href="${imageUrl}" download="${escapeHtml(fileName)}" class="inline-flex items-center justify-center rounded-full h-10 w-10 bg-white/30 hover:bg-white/50 transition-colors" title="Download">
                        <i class="fa-solid fa-download text-white"></i>
                    </a>
                </div>
            </div>`;
    }

    function formatBytes(bytes) {
        if (!bytes) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }

    function getFileIcon(fileName) {
        if (/\.pdf$/i.test(fileName)) return 'fa-file-pdf text-red-500';
        if (/\.(doc|docx)$/i.test(fileName)) return 'fa-file-word text-blue-500';
        if (/\.(xls|xlsx)$/i.test(fileName)) return 'fa-file-excel text-emerald-600';
        if (/\.(ppt|pptx)$/i.test(fileName)) return 'fa-file-powerpoint text-orange-500';
        if (/\.(jpg|jpeg|png|gif|webp|svg)$/i.test(fileName)) return 'fa-file-image text-purple-500';
        if (/\.txt$/i.test(fileName)) return 'fa-file-lines text-slate-500';
        return 'fa-file text-slate-500';
    }

    function getFileType(fileName) {
        if (/\.pdf$/i.test(fileName)) return 'PDF';
        if (/\.(doc|docx)$/i.test(fileName)) return 'DOC';
        if (/\.(xls|xlsx)$/i.test(fileName)) return 'XLS';
        if (/\.(ppt|pptx)$/i.test(fileName)) return 'PPT';
        if (/\.txt$/i.test(fileName)) return 'TXT';
        return 'FILE';
    }

    function renderFileAttachment(fileUrl, fileName, fileSize, isMe) {
        const iconClass = getFileIcon(fileName);
        const fileType = getFileType(fileName);
        const borderColor = isMe ? 'border-l-[#00a884]' : 'border-l-blue-400';
        return `
            <div class="flex items-center my-2 bg-white/80 rounded-lg overflow-hidden shadow-sm border-l-4 ${borderColor} hover:shadow-md transition-all">
                <div class="w-12 h-12 flex items-center justify-center shrink-0 bg-slate-50">
                    <i class="fa-solid ${iconClass} text-xl"></i>
                </div>
                <div class="flex-1 min-w-0 py-2 px-3">
                    <p class="text-sm font-medium text-slate-800 truncate">${escapeHtml(fileName)}</p>
                    <p class="text-xs text-slate-400 mt-0.5">${formatBytes(fileSize)} · ${fileType}</p>
                </div>
                <div class="flex items-center gap-1 px-2 shrink-0">
                    <button type="button" class="view-file-btn w-8 h-8 rounded-full text-[#00a884] hover:bg-[#00a884] hover:text-white flex items-center justify-center transition-colors" data-url="${fileUrl}" data-name="${escapeHtml(fileName)}" title="View file">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>
                    <a href="${fileUrl}" download="${escapeHtml(fileName)}" class="w-8 h-8 rounded-full text-slate-500 hover:bg-slate-200 flex items-center justify-center transition-colors" title="Download">
                        <i class="fa-solid fa-download text-sm"></i>
                    </a>
                </div>
            </div>`;
    }

    function renderVoicePlayer(audioUrl, isMe) {
        const bars = Array.from({ length: 24 }, () => Math.floor(Math.random() * 24 + 6));
        return `
            <div class="flex items-center gap-2 py-2.5 ${isMe ? 'flex-row-reverse' : ''}">
                <button type="button" class="voice-play-btn w-9 h-9 rounded-full bg-white shadow flex items-center justify-center text-slate-700 hover:text-[#00a884] transition-colors" data-url="${audioUrl}">
                    <i class="fa-solid fa-play text-xs"></i>
                </button>
                <div class="flex items-end gap-[2px] h-8">
                    ${bars.map(h => `<div class="w-[3px] bg-slate-400 rounded-full" style="height:${h}px"></div>`).join('')}
                </div>
                <span class="text-xs text-slate-500 font-medium voice-duration">0:00</span>
            </div>`;
    }

    async function pollMessages() {
        if (!currentConversationId) return;
        try {
            const res = await fetch(`{{ url('chat') }}/${currentConversationId}/poll?last_id=${lastId}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();
            let hasNew = false;
            data.messages.forEach(msg => {
                if (msg.id > lastId) lastId = msg.id;
                if (!document.querySelector(`.message-item[data-id="${msg.id}"]`)) {
                    appendMessage(msg, msg.user_id === {{ auth()->id() }});
                    hasNew = true;
                    updateUserRow(currentConversationId, msg.body, msg.created_at);
                }
            });

            // Update tick status based on receipts
            (data.read_ids || []).forEach(id => updateTick(id, 'read'));
            (data.delivered_ids || []).forEach(id => updateTick(id, 'delivered'));

            if (hasNew) updateCounters();
        } catch (e) {
            console.error('Poll error', e);
        }
    }

    function updateTick(messageId, status) {
        const item = document.querySelector(`.message-item[data-id="${messageId}"]`);
        if (!item) return;
        const tickContainer = item.querySelector('.tick-sent, .tick-delivered, .tick-read');
        const statusLabel = item.querySelector('.status-label');
        if (!tickContainer) return;

        if (status === 'read') {
            tickContainer.className = 'tick-read relative w-3 h-3 flex items-center justify-center';
            tickContainer.innerHTML = `
                <i class="fa-solid fa-check text-[10px] text-blue-500 absolute -left-[3px]"></i>
                <i class="fa-solid fa-check text-[10px] text-blue-500 absolute left-[1px]"></i>`;
            if (statusLabel) { statusLabel.textContent = 'Read'; statusLabel.className = 'text-xs font-medium text-blue-600 status-label'; }
        } else if (status === 'delivered') {
            tickContainer.className = 'tick-delivered relative w-3 h-3 flex items-center justify-center';
            tickContainer.innerHTML = `
                <i class="fa-solid fa-check text-[10px] text-slate-400 absolute -left-[3px]"></i>
                <i class="fa-solid fa-check text-[10px] text-slate-400 absolute left-[1px]"></i>`;
            if (statusLabel) { statusLabel.textContent = 'Delivered'; statusLabel.className = 'text-xs font-medium text-slate-500 status-label'; }
        }
    }

    function startPolling() {
        clearInterval(pollInterval);
        pollInterval = setInterval(pollMessages, 2500);
    }

    // Heartbeat to keep current user online
    setInterval(async () => {
        try {
            await fetch('{{ route('chat.heartbeat') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        } catch (e) {
            console.error('Heartbeat error', e);
        }
    }, 30000);

    function updateUserRow(conversationId, lastMsg, lastTime) {
        const btn = document.querySelector(`.chat-user-btn[data-conversation-id="${conversationId}"]`);
        if (!btn) return;
        const preview = btn.querySelector('.text-xs.truncate');
        const time = btn.querySelector('.text-\[11px\]');
        if (preview) preview.textContent = lastMsg;
        if (time) time.textContent = 'just now';
        btn.parentElement?.prepend(btn);
    }

    async function updateCounters() {
        // Optional: fetch unread counts via an endpoint; for now we rely on visual updates on click/load
    }
})();
</script>
@endpush
@endsection
