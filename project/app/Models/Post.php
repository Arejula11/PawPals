<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\FileController;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    public $timestamps = false;

    protected $fillable = [
        'creation_date',
        'description',
        'user_id',
        'post_picture',
        'is_public',
    ];

    /**
     * Get the user who created the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the picture for the post.
     */
    public function getPostPicture()
    {
        return FileController::get('post', $this->id);
    }

    /**
     * Get the comments of the post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    /**
     * Get the likes of the post.
     */
    public function likes()
    {
        return $this->belongsToMany(User::class, 'post_like', 'post_id', 'user_id');
    }

    /**
     * Get the tags of the post.
     */
    public function tags()
    {
        return $this->belongsToMany(User::class, 'post_tag', 'post_id', 'user_id');
    }

    /**
     * Get all the posts
     */
    public function getAllPosts()
    {
        return $this->all();
    }

}

