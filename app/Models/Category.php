<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the papers for the category.
     */
    public function papers()
    {
        return $this->belongsToMany(Paper::class, 'paper_category');
    }
}

