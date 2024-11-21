<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'username',
        'firstname',
        'surname',
        'password',
        'email',
        'bio_description',
        'is_public',
        'admin',
        'type',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Get the profile picture for the user.
     */
    public function profilePicture(): BelongsTo
    {
        return $this->belongsTo(Picture::class, 'profile_picture');
    }

    /**
     * Get the cards for a user.
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * Get the groups the user is a member of.
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_participant', 'user_id', 'group_id');
    }

    /**
     * Get the posts created by the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the comments made by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the likes given by the user on posts.
     */
    public function postLikes(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_like', 'user_id', 'post_id');
    }

    /**
     * Get the likes given by the user on comments.
     */
    public function commentLikes(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'comment_like', 'user_id', 'comment_id');
    }

    /**
     * Get the posts tagged by the user.
     */
    public function postTags(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag', 'user_id', 'post_id');
    }

    /**
     * Get the comments tagged by the user.
     */
    public function commentTags(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'comment_tag', 'user_id', 'comment_id');
    }

    /**
     * Get the messages sent by the user.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the bans for the user.
     */
    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user notifications related to the user.
     */
    public function userNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class, 'trigger_user_id');
    }

    /**
     * Get the post notifications related to the user.
     */
    public function postNotifications(): HasMany
    {
        return $this->hasMany(PostNotification::class, 'trigger_post_id');
    }

    /**
     * Get the comment notifications related to the user.
     */
    public function commentNotifications(): HasMany
    {
        return $this->hasMany(CommentNotification::class, 'trigger_comment_id');
    }

    /**
     * Get the group owner notifications related to the user.
     */
    public function groupOwnerNotifications(): HasMany
    {
        return $this->hasMany(GroupOwnerNotification::class, 'trigger_group_id');
    }

    /**
     * Get the group member notifications related to the user.
     */
    public function groupMemberNotifications(): HasMany
    {
        return $this->hasMany(GroupMemberNotification::class, 'trigger_group_id');
    }

    /**
     * Get the users that the user is following.
     */
    public function follows(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'user1_id', 'user2_id')
                    ->wherePivot('request_status', 'accepted');
    }

    /**
     * Get the users who are following the user.
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'user2_id', 'user1_id')
                    ->wherePivot('request_status', 'accepted');
    }
}
