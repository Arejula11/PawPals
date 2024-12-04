<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $table = 'follows';

    public $timestamps = false;

    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'user1_id',
        'user2_id',
        'request_status'
    ];

    /**
     * Relationship to the user who sent the follow request.
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    /**
     * Relationship to the user who received the follow request.
     */
    public function following()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }
}
