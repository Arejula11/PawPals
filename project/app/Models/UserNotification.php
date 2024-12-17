<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    use HasFactory;


    public $timestamps = false;

    protected $table = 'user_notification';
    protected $primaryKey = 'notification_id'; 
    public $incrementing = false; 
    protected $keyType = 'int'; 

    protected $fillable = [
        'response_type',
        'trigger_user_id',
        'user_notification_type',
        'notification_id',
    ];

    
}
