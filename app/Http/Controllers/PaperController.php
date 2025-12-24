<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;

class PaperController extends Controller
{
    public function view($id)
    {
        $paper = Paper::with(['authors', 'uploader'])
                     ->where('id', $id)
                     ->where('status', 'approved') // Only show approved papers
                     ->firstOrFail();
        
        return view('paper.view', compact('paper'));
    }
}