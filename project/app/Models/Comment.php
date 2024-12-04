<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';

    public $timestamps = false;

    protected $fillable = [
        'content',
        'date',
        'post_id',
        'user_id',
        'previous_comment_id',
    ];

    /**
     * Get the post of the comment.
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * Get the user who created the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the previous comment of the comment.
     */
    public function previousComment()
    {
        return $this->belongsTo(Comment::class, 'previous_comment_id');
    }

    /**
     * Get the child comments of the comment.
     */
    public function childComments()
    {
        return $this->hasMany(Comment::class, 'previous_comment_id');
    }

    /**
     * Get the likes of the comment.
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, 'comment_like');
    }

    /**
     * Get the tags of the comment.
     */
    public function tags()
    {
        return $this->belongsToMany(User::class, 'comment_tag', 'comment_id', 'user_id');
    }
}
