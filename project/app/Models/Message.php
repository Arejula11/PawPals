<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'message';

    public $timestamps = false;

    protected $fillable = [
        'content',
        'date',
        'sender_id',
        'group_id',
    ];

    /**
     * Get the sender of the message.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the group of the message.
     */
    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
