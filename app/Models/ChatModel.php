<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChatModel extends Model
{
    use HasFactory;
    protected $table='tbl_chat_models';
    protected $guarded=['id'];
    protected $fillable=[
        ''
    ];

    // chat has many participant
    public function participant():HasMany{
        return $this->hasMany(ChatParticipant::class,'chat_id');
    }

    // and participant has many message
    public function message():HasMany{
        return $this->hasMany(ChatMessage::class,'chat_id');
    }

    public function lastMessage():HasOne{
        return $this->hasOne(ChatMessage::class,'chat_id')->lastest('update_at');
    }
}
