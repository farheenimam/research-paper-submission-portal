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
        'approved_by',
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
        return $this->hasMany(PaperAuthor::class, 'paper_id', 'id');
    }

    /**
     * Get the user who approved the paper.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the categories for the paper.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'paper_category');
    }

    /**
     * Get the users who saved this paper.
     */
    public function savedByUsers()
    {
        return $this->hasMany(SavedPaper::class);
    }
}