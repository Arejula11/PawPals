<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    use HasFactory;

    protected $table = 'ban';

    public $timestamps = false;

    protected $fillable = [
        'reason',
        'date',
        'active',
        'user_id',
    ];

    /**
     * Get the user who was banned.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the appeal for the ban.
     */
    public function appeal()
    {
        return $this->hasOne(Appeal::class, 'ban_id');
    }
}
