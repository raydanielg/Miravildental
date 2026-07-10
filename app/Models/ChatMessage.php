<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['chat_conversation_id', 'user_id', 'body', 'audio_path', 'file_path', 'file_name', 'file_size', 'gallery_paths', 'link_url', 'link_title', 'link_description', 'link_image', 'delivered_at', 'read_at'];

    protected $casts = [
        'gallery_paths' => 'array',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function isDelivered(): bool
    {
        return $this->delivered_at !== null;
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'chat_conversation_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
