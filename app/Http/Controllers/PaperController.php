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

    public function recent(Request $request)
    {
        $query = $request->input('search', '');
        $papers = Paper::where('status', 'approved')
            ->with(['authors', 'uploader'])
            ->orderBy('created_at', 'desc');

        if (!empty($query)) {
            $papers = $papers->where('title', 'LIKE', "%{$query}%");
        }

        $papers = $papers->paginate(12);
        $totalResults = $papers->total();

        return view('paper.recent', compact('papers', 'query', 'totalResults'));
    }
}