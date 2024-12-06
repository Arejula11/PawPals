<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'is_public',
        'owner_id',
    ];
    
    /**
     * Get the owner of the group.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the participants of the group.
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'group_participant', 'group_id', 'user_id');
    }

    /**
     * Get the messages of the group.
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'group_id');
    }
}
