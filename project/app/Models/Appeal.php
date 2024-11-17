<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appeal extends Model
{
    use HasFactory;

    protected $table = 'appeal';

    public $timestamps = false;

    protected $fillable = [
        'reason',
        'date',
        'status',
        'ban_id',
    ];

    /**
     * Get the ban for the appeal.
     */
    public function ban()
    {
        return $this->belongsTo(Ban::class, 'ban_id');
    }
}
