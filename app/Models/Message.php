<?php

namespace App\Models;

use App\Events\MessageCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Message extends Model
{
    use HasFactory;

    protected $hidden = [
        'sender_id',
        'recipient_id',
    ];

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'content',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(static function ( self $message )
        {
            event(new MessageCreated($message->load(['sender']))); // and recipient ?
        });
    }

    public function sender(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'sender_id');
    }

    public function recipient(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'recipient_id');
    }
}
