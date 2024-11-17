<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Picture extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'img_path',
    ];

    /**
     * Get the users that have a profile picture.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'profile_picture');
    }

    /**
     * Get the groups that have a group picture.
     */
    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'img_id');
    }

    /**
     * Get the posts picture.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'post_picture_id');
    }
}
