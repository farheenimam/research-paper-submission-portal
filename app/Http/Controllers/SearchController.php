<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\SavedPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('search', '');
        $papers = collect();
        $totalResults = 0;
        $savedPaperIds = [];

        // Get saved paper IDs for logged-in users
        if (Auth::check()) {
            $savedPaperIds = SavedPaper::where('user_id', Auth::id())
                ->pluck('paper_id')
                ->toArray();
        }

        if (!empty($query)) {
            // Simple search based on paper title only
            $papers = Paper::where('status', 'approved')
                ->where('title', 'LIKE', "%{$query}%")
                ->with(['authors', 'uploader', 'categories'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $totalResults = $papers->total();
        }

        return view('search.results', compact('papers', 'query', 'totalResults', 'savedPaperIds'));
    }

    public function savePaper(Request $request, $paperId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to save papers'], 401);
        }

        $paper = Paper::where('id', $paperId)
            ->where('status', 'approved')
            ->firstOrFail();

        // Check if already saved
        $saved = SavedPaper::where('user_id', Auth::id())
            ->where('paper_id', $paperId)
            ->first();

        if ($saved) {
            $saved->delete();
            return response()->json(['success' => true, 'saved' => false, 'message' => 'Paper removed from saved']);
        } else {
            SavedPaper::create([
                'user_id' => Auth::id(),
                'paper_id' => $paperId,
            ]);
            return response()->json(['success' => true, 'saved' => true, 'message' => 'Paper saved successfully']);
        }
    }

    public function savedPapers(Request $request)
    {
        $query = $request->input('search', '');
        $savedPaperIds = SavedPaper::where('user_id', Auth::id())->pluck('paper_id');
        
        $papers = Paper::whereIn('id', $savedPaperIds)
            ->where('status', 'approved')
            ->with(['authors', 'uploader', 'categories']);

        if (!empty($query)) {
            $papers = $papers->where('title', 'LIKE', "%{$query}%");
        }

        $papers = $papers->orderBy('created_at', 'desc')->paginate(10);
        $totalResults = $papers->total();

        return view('reader.saved-papers', compact('papers', 'query', 'totalResults'));
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