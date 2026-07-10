<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $conversations = ChatConversation::whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->with(['latestMessage.user', 'participants.user'])
            ->orderByDesc('last_message_at')
            ->get();

        $users = User::where('id', '!=', $user->id)->orderBy('name')->get();

        $users = $users->map(function ($u) use ($user) {
            $conv = ChatConversation::where('type', 'private')
                ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
                ->whereHas('participants', fn ($q) => $q->where('user_id', $u->id))
                ->with('latestMessage')
                ->first();

            $u->conversation_id = $conv?->id;
            $u->unread_count = $conv ? $conv->unreadCountFor($user) : 0;
            $u->last_message = $conv?->latestMessage?->body;
            $u->last_message_at = $conv?->last_message_at;

            return $u;
        });

        return view('chat.index', compact('conversations', 'users'));
    }

    public function ajaxStart(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $user = Auth::user();
        $otherUserId = $request->input('user_id');

        $conversation = ChatConversation::where('type', 'private')
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $otherUserId))
            ->first();

        if (! $conversation) {
            $conversation = ChatConversation::create([
                'type' => 'private',
                'created_by' => $user->id,
                'last_message_at' => now(),
            ]);

            ChatParticipant::create([
                'chat_conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'last_read_at' => now(),
            ]);
            ChatParticipant::create([
                'chat_conversation_id' => $conversation->id,
                'user_id' => $otherUserId,
            ]);
        }

        return response()->json(['conversation_id' => $conversation->id]);
    }

    public function ajaxLoad(ChatConversation $conversation)
    {
        $this->authorizeAccess($conversation);
        $user = Auth::user();
        $now = now();

        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->where(function ($q) {
                $q->whereNull('delivered_at')->orWhereNull('read_at');
            })
            ->update([
                'delivered_at' => $now,
                'read_at' => $now,
            ]);

        ChatParticipant::updateOrCreate(
            ['chat_conversation_id' => $conversation->id, 'user_id' => $user->id],
            ['last_read_at' => $now]
        );

        $messages = $conversation->messages()->with('user')->get();
        $other = $conversation->participants->first(fn ($p) => $p->user_id !== $user->id)?->user;

        return view('chat.ajax-messages', compact('conversation', 'messages', 'other'));
    }

    public function show(ChatConversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $user = Auth::user();

        // Mark messages from others as delivered and read
        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->where(function ($q) {
                $q->whereNull('delivered_at')->orWhereNull('read_at');
            })
            ->update([
                'delivered_at' => now(),
                'read_at' => now(),
            ]);

        ChatParticipant::updateOrCreate(
            ['chat_conversation_id' => $conversation->id, 'user_id' => $user->id],
            ['last_read_at' => now()]
        );

        $messages = $conversation->messages()->with('user')->get();
        $conversations = ChatConversation::whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->with(['latestMessage.user', 'participants.user'])
            ->orderByDesc('last_message_at')
            ->get();

        return view('chat.show', compact('conversation', 'messages', 'conversations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'body' => 'required|string|max:2000',
        ]);

        $user = Auth::user();
        $otherUserId = $request->input('user_id');

        // Find existing private conversation
        $conversation = ChatConversation::where('type', 'private')
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $otherUserId))
            ->first();

        if (! $conversation) {
            $conversation = ChatConversation::create([
                'type' => 'private',
                'created_by' => $user->id,
                'last_message_at' => now(),
            ]);

            ChatParticipant::create([
                'chat_conversation_id' => $conversation->id,
                'user_id' => $user->id,
                'last_read_at' => now(),
            ]);
            ChatParticipant::create([
                'chat_conversation_id' => $conversation->id,
                'user_id' => $otherUserId,
            ]);
        }

        $message = ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $request->input('body'),
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        if ($request->wantsJson()) {
            return response()->json(['message' => $message->load('user')]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    public function storeVoice(Request $request, ChatConversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $request->validate([
            'audio' => 'required|file|mimes:webm,mp3,ogg|max:10240',
        ]);

        $user = Auth::user();
        $file = $request->file('audio');
        $filename = 'voice_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('chat-audio', $filename, 'public');

        $message = ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => 'Voice message',
            'audio_path' => $path,
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        ChatParticipant::updateOrCreate(
            ['chat_conversation_id' => $conversation->id, 'user_id' => $user->id],
            ['last_read_at' => now()]
        );

        return response()->json(['message' => $message->load('user')]);
    }

    public function storeGallery(Request $request, ChatConversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $request->validate([
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'file|mimes:jpg,jpeg,png,gif,webp|max:10240',
        ]);

        $user = Auth::user();
        $paths = [];

        foreach ($request->file('images') as $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $paths[] = $image->storeAs('chat-gallery', $filename, 'public');
        }

        $message = ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => 'Image gallery',
            'gallery_paths' => $paths,
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        ChatParticipant::updateOrCreate(
            ['chat_conversation_id' => $conversation->id, 'user_id' => $user->id],
            ['last_read_at' => now()]
        );

        return response()->json(['message' => $message->load('user')]);
    }

    public function storeFile(Request $request, ChatConversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $request->validate([
            'file' => 'required|file|max:51200',
        ]);

        $user = Auth::user();
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $path = $file->storeAs('chat-files', $filename, 'public');

        $message = ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $originalName,
            'file_path' => $path,
            'file_name' => $originalName,
            'file_size' => $file->getSize(),
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        ChatParticipant::updateOrCreate(
            ['chat_conversation_id' => $conversation->id, 'user_id' => $user->id],
            ['last_read_at' => now()]
        );

        return response()->json(['message' => $message->load('user')]);
    }

    public function sendMessage(Request $request, ChatConversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $request->validate(['body' => 'required|string|max:2000']);

        $user = Auth::user();
        $body = $request->input('body');
        $linkPreview = $this->extractLinkPreview($body);

        $message = ChatMessage::create([
            'chat_conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $body,
            ...$linkPreview,
        ]);

        $conversation->update(['last_message_at' => $message->created_at]);

        ChatParticipant::updateOrCreate(
            ['chat_conversation_id' => $conversation->id, 'user_id' => $user->id],
            ['last_read_at' => now()]
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => $message->load('user')]);
        }

        return redirect()->route('chat.show', $conversation);
    }

    protected function extractLinkPreview(string $text): array
    {
        preg_match('/https?:\/\/[^\s]+/i', $text, $matches);
        if (empty($matches[0])) {
            return [];
        }

        $url = $matches[0];
        $preview = ['link_url' => $url];

        try {
            $html = @file_get_contents($url, false, stream_context_create([
                'http' => ['timeout' => 5, 'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'],
            ]));

            if ($html) {
                if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $m)) {
                    $preview['link_title'] = $m[1];
                } elseif (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $m)) {
                    $preview['link_title'] = trim($m[1]);
                }

                if (preg_match('/<meta[^>]+property=["\']og:description["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $m)) {
                    $preview['link_description'] = $m[1];
                } elseif (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $m)) {
                    $preview['link_description'] = $m[1];
                }

                if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\'][^>]*>/i', $html, $m)) {
                    $preview['link_image'] = $this->resolveUrl($url, $m[1]);
                }
            }
        } catch (\Throwable $e) {
            // Silently fail; preview is optional
        }

        return $preview;
    }

    protected function resolveUrl(string $baseUrl, string $url): string
    {
        if (str_starts_with($url, 'http')) {
            return $url;
        }

        $parts = parse_url($baseUrl);
        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'] ?? '';

        if (str_starts_with($url, '//')) {
            return $scheme . ':' . $url;
        }

        if (str_starts_with($url, '/')) {
            return $scheme . '://' . $host . $url;
        }

        return $baseUrl . (str_ends_with($baseUrl, '/') ? '' : '/') . $url;
    }

    public function heartbeat(Request $request)
    {
        Auth::user()->update(['last_seen_at' => now()]);
        return response()->json(['status' => 'ok']);
    }

    public function unreadCount(Request $request)
    {
        $user = Auth::user();
        $count = ChatConversation::whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->get()
            ->sum(fn ($c) => $c->unreadCountFor($user));

        return response()->json(['unread_count' => $count]);
    }

    public function poll(ChatConversation $conversation)
    {
        $this->authorizeAccess($conversation);

        $user = Auth::user();
        $lastId = request()->input('last_id', 0);
        $now = now();

        $messages = $conversation->messages()
            ->with('user')
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark messages from others as delivered (and read if they were just fetched)
        $conversation->messages()
            ->where('user_id', '!=', $user->id)
            ->whereNull('delivered_at')
            ->update(['delivered_at' => $now]);

        ChatParticipant::updateOrCreate(
            ['chat_conversation_id' => $conversation->id, 'user_id' => $user->id],
            ['last_read_at' => $now]
        );

        // Send read receipts for this user's messages that have been read
        $readReceipts = $conversation->messages()
            ->where('user_id', $user->id)
            ->whereNotNull('read_at')
            ->pluck('id');

        $deliveredReceipts = $conversation->messages()
            ->where('user_id', $user->id)
            ->whereNotNull('delivered_at')
            ->pluck('id');

        return response()->json([
            'messages' => $messages,
            'read_ids' => $readReceipts,
            'delivered_ids' => $deliveredReceipts,
        ]);
    }

    protected function authorizeAccess(ChatConversation $conversation): void
    {
        abort_if(! $conversation->participants()->where('user_id', Auth::id())->exists(), 403);
    }
}
