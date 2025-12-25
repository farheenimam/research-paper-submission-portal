<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'paper_id',
        'user_id',
        'comment',
    ];

    /**
     * Get the paper that owns the comment.
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    /**
     * Get the user (reviewer) who made the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

