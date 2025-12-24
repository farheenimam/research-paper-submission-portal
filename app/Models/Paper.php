<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'abstract',
        'pdf_path',
        'publication_year',
        'status',
        'uploaded_by',
    ];

    /**
     * Get the user who uploaded the paper.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the authors for the paper.
     */
    public function authors()
    {
        return $this->hasMany(PaperAuthor::class);
    }
}