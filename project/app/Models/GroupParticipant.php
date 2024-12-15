<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupParticipant extends Model
{
    // Define the table associated with the model
    protected $table = 'group_participant';

    public $timestamps = false;

    protected $fillable = [
        'group_id',
        'user_id',
        'joined_at'
    ];

    /**
     * Get the group associated with the participant.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    /**
     * Get the user associated with the participant.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
