@extends('layouts.dashboard')

@section('title', 'Team Chat - ' . config('app.name', 'Laravel'))
@section('page_title', 'Team Chat')

@section('content')
@php
    $other = $conversation->participants->first(fn($p) => $p->user_id !== auth()->id())?->user;
@endphp

<div class="h-[calc(100vh-8rem)] bg-white rounded-xl shadow-sm border border-gray-200 flex overflow-hidden animate-fade" id="chat-app" data-conversation="{{ $conversation->id }}" data-last-id="{{ $messages->last()?->id ?? 0 }}">
    {{-- Sidebar --}}
    <div class="w-full md:w-80 border-r border-gray-200 flex flex-col bg-gray-50 hidden md:flex">
        <div class="p-4 border-b border-gray-200 bg-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="{{ asset('chat.png') }}" alt="Team Chat" class="w-6 h-6 object-contain">
                <div>
                    <h2 class="font-bold text-gray-800">Conversations</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Chat with staff members</p>
                </div>
            </div>
            <a href="{{ route('chat.index') }}" class="md:hidden text-sm text-emerald-600 font-semibold">Back</a>
        </div>

        <div class="flex-1 overflow-y-auto p-3 space-y-2">
            @foreach ($conversations as $conv)
                @php
                    $convOther = $conv->participants->first(fn($p) => $p->user_id !== auth()->id())?->user;
                    $unread = $conv->unreadCountFor(auth()->user());
                @endphp
                <a href="{{ route('chat.show', $conv) }}" class="flex items-center gap-3 p-3 rounded-lg hover:bg-white hover:shadow-sm transition-all group {{ $conv->id === $conversation->id ? 'bg-white shadow-sm ring-1 ring-emerald-100' : '' }}">
                    <div class="relative">
                        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center font-bold text-sm">
                            {{ $convOther ? strtoupper(substr($convOther->name, 0, 2)) : 'ST' }}
                        </div>
                        @if($unread > 0)
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse">{{ $unread }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-sm text-gray-900 truncate">{{ $convOther?->name ?? 'Staff Group' }}</h3>
                            @if($conv->last_message_at)
                                <span class="text-[10px] text-gray-400">{{ $conv->last_message_at->diffForHumans() }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 truncate mt-0.5">
                            {{ $conv->latestMessage?->body ?? 'No messages yet' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Chat area --}}
    <div class="flex-1 flex flex-col bg-white">
        {{-- Header --}}
        <div class="h-16 border-b border-gray-200 px-5 flex items-center justify-between bg-white">
            <div class="flex items-center gap-3">
                <a href="{{ route('chat.index') }}" class="md:hidden text-emerald-600 text-sm font-semibold mr-2">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center font-bold text-sm">
                    {{ $other ? strtoupper(substr($other->name, 0, 2)) : 'ST' }}
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">{{ $other?->name ?? 'Staff Group' }}</h3>
                    <p class="text-xs text-emerald-600 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        Online
                    </p>
                </div>
            </div>
            <div class="text-xs text-gray-400" id="typing-indicator"></div>
        </div>

        {{-- Messages --}}
        <div id="chat-messages" class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50">
            @foreach ($messages as $msg)
                @if($msg->user_id === auth()->id())
                    <div class="flex justify-end message-item" data-id="{{ $msg->id }}">
                        <div class="max-w-[75%]">
                            <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 text-white px-4 py-2.5 rounded-2xl rounded-tr-sm shadow-sm">
                                <p class="text-sm leading-relaxed">{{ $msg->body }}</p>
                            </div>
                            <div class="flex items-center justify-end gap-1 mt-1">
                                <span class="text-[10px] text-gray-400">{{ $msg->created_at->format('g:i A') }}</span>
                                @if($msg->read_at)
                                    <i class="fa-solid fa-check-double text-[10px] text-emerald-500"></i>
                                @else
                                    <i class="fa-solid fa-check text-[10px] text-gray-300"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex justify-start message-item" data-id="{{ $msg->id }}">
                        <div class="max-w-[75%]">
                            <p class="text-[10px] text-gray-400 mb-0.5 ml-1">{{ $msg->user->name }}</p>
                            <div class="bg-white text-gray-800 px-4 py-2.5 rounded-2xl rounded-tl-sm shadow-sm border border-gray-100">
                                <p class="text-sm leading-relaxed">{{ $msg->body }}</p>
                            </div>
                            <span class="text-[10px] text-gray-400 ml-1 mt-1 block">{{ $msg->created_at->format('g:i A') }}</span>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Input --}}
        <div class="p-4 bg-white border-t border-gray-200">
            <form id="chat-form" action="{{ route('chat.send', $conversation) }}" method="POST" class="flex items-end gap-2">
                @csrf
                <div class="flex-1 relative">
                    <input type="text" id="chat-input" name="body" autocomplete="off"
                        class="w-full pl-4 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm transition-all"
                        placeholder="Type a message..." required maxlength="2000">
                </div>
                <button type="submit" id="chat-send-btn"
                    class="h-12 w-12 bg-gradient-to-br from-emerald-500 to-emerald-700 text-white rounded-2xl shadow-md hover:shadow-lg hover:scale-105 transition-all flex items-center justify-center disabled:opacity-60 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const chatApp = document.getElementById('chat-app');
    if (!chatApp) return;

    const conversationId = chatApp.dataset.conversation;
    let lastId = parseInt(chatApp.dataset.lastId) || 0;
    const messagesContainer = document.getElementById('chat-messages');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const sendBtn = document.getElementById('chat-send-btn');
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function appendMessage(msg, isMe) {
        const div = document.createElement('div');
        div.className = isMe ? 'flex justify-end message-item' : 'flex justify-start message-item';
        div.dataset.id = msg.id;

        const time = new Date(msg.created_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });

        if (isMe) {
            div.innerHTML = `
                <div class="max-w-[75%]">
                    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 text-white px-4 py-2.5 rounded-2xl rounded-tr-sm shadow-sm animate-fade">
                        <p class="text-sm leading-relaxed">${escapeHtml(msg.body)}</p>
                    </div>
                    <div class="flex items-center justify-end gap-1 mt-1">
                        <span class="text-[10px] text-gray-400">${time}</span>
                        <i class="fa-solid fa-check text-[10px] text-gray-300"></i>
                    </div>
                </div>`;
        } else {
            div.innerHTML = `
                <div class="max-w-[75%]">
                    <p class="text-[10px] text-gray-400 mb-0.5 ml-1">${escapeHtml(msg.user.name)}</p>
                    <div class="bg-white text-gray-800 px-4 py-2.5 rounded-2xl rounded-tl-sm shadow-sm border border-gray-100 animate-fade">
                        <p class="text-sm leading-relaxed">${escapeHtml(msg.body)}</p>
                    </div>
                    <span class="text-[10px] text-gray-400 ml-1 mt-1 block">${time}</span>
                </div>`;
        }

        messagesContainer.appendChild(div);
        scrollToBottom();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async function pollMessages() {
        try {
            const res = await fetch(`{{ url('chat') }}/${conversationId}/poll?last_id=${lastId}`, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const data = await res.json();
            data.messages.forEach(msg => {
                if (msg.id > lastId) lastId = msg.id;
                if (!document.querySelector(`.message-item[data-id="${msg.id}"]`)) {
                    appendMessage(msg, msg.user_id === {{ auth()->id() }});
                }
            });
        } catch (e) {
            console.error('Chat poll error', e);
        }
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const body = input.value.trim();
        if (!body) return;

        sendBtn.disabled = true;
        input.disabled = true;

        try {
            const res = await fetch(form.action, {
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
                if (!document.querySelector(`.message-item[data-id="${data.message.id}"]`)) {
                    appendMessage(data.message, true);
                    if (data.message.id > lastId) lastId = data.message.id;
                }
                input.value = '';
            }
        } catch (err) {
            console.error('Send error', err);
        } finally {
            sendBtn.disabled = false;
            input.disabled = false;
            input.focus();
        }
    });

    scrollToBottom();
    setInterval(pollMessages, 2500);
})();
</script>
@endpush
@endsection
