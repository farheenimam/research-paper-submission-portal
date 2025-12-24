<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('search', '');
        $papers = collect();
        $totalResults = 0;

        if (!empty($query)) {
            // Simple search based on paper title only
            $papers = Paper::where('status', 'approved')
                ->where('title', 'LIKE', "%{$query}%")
                ->with(['authors', 'uploader'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $totalResults = $papers->total();
        }

        return view('search.results', compact('papers', 'query', 'totalResults'));
    }

    public function searchApi(Request $request)
    {
        $query = $request->input('q', '');
        $suggestions = [];

        if (strlen($query) >= 2) {
            // Get search suggestions from paper titles only
            $suggestions = Paper::where('status', 'approved')
                ->where('title', 'LIKE', "%{$query}%")
                ->limit(5)
                ->pluck('title')
                ->toArray();
        }

        return response()->json($suggestions);
    }
}