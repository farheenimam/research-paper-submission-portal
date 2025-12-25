<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedPaper extends Model
{
    use HasFactory;

    protected $table = 'saved_papers';

    protected $fillable = [
        'user_id',
        'paper_id',
    ];

    /**
     * Get the user who saved the paper.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the paper that was saved.
     */
    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }
}

