<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperAuthor extends Model
{
    use HasFactory;

    public $timestamps = false; // This table doesn't have timestamps

    protected $fillable = [
        'paper_id',
        'user_id',
        'author_name',
        'author_email',
        'affiliation',
    ];

    /**
     * Get the paper that owns the author.
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    /**
     * Get the user associated with the author.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}