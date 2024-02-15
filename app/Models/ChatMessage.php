<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;
    protected $table='tbl_chat_messages';
    protected $guarded=['id'];
    protected $touches=['chat'];    // when create new chatmessage it will update on chat model too


    // chat has a user
    public function user():BelongsTo{
        return $this->belongsTo(User::class,'user_id');
    }

    public function chat():BelongsTo{
        return $this->belongsTo(ChatModel::class,'chat_id');
    }
}
