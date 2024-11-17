<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    public $timestamps = false;

    protected $fillable = [
        'description',
        'date',
        'user_id',
    ];

    /**
     * Get the user who received the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user notifications.
     */
    public function userNotifications()
    {
        return $this->hasMany(UserNotification::class, 'notification_id');
    }

    /**
     * Get the post notifications.
     */
    public function postNotifications()
    {
        return $this->hasMany(PostNotification::class, 'notification_id');
    }

    /**
     * Get the comment notifications.
     */
    public function commentNotifications()
    {
        return $this->hasMany(CommentNotification::class, 'notification_id');
    }

    /**
     * Get the group owner notifications.
     */
    public function groupOwnerNotifications()
    {
        return $this->hasMany(GroupOwnerNotification::class, 'notification_id');
    }

    /**
     * Get the group member notifications.
     */
    public function groupMemberNotifications()
    {
        return $this->hasMany(GroupMemberNotification::class, 'notification_id');
    }
}
