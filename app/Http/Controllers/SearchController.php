<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\SavedPaper;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        
        if (Auth::check()) {
            $request->session()->increment('visits', 1);
        }
        
        $query = $request->input('search', '');
        $categoryId = $request->input('category', '');
        $year = $request->input('year', '');
        $papers = collect();
        $totalResults = 0;
        $savedPaperIds = [];

        // Get all categories for filter dropdown
        $categories = Category::orderBy('name', 'asc')->get();
        
        // Get available years for filter (from approved papers)
        $availableYears = Paper::where('status', 'approved')
            ->distinct()
            ->orderBy('publication_year', 'desc')
            ->pluck('publication_year')
            ->toArray();

        // Get saved paper IDs for logged-in users
        if (Auth::check()) {
            $savedPaperIds = SavedPaper::where('user_id', Auth::id())
                ->pluck('paper_id')
                ->toArray();
        }

        $papers = Paper::where('status', 'approved')
            ->with(['authors', 'uploader', 'categories'])
            ->orderBy('created_at', 'desc');

        // Apply search query
        if (!empty($query)) {
            $papers = $papers->where('title', 'LIKE', "%{$query}%");
        }

        // Apply category filter
        if (!empty($categoryId)) {
            $papers = $papers->whereHas('categories', function($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        // Apply year filter
        if (!empty($year)) {
            $papers = $papers->where('publication_year', $year);
        }

        $papers = $papers->paginate(10);
        $totalResults = $papers->total();

        return view('search.results', compact('papers', 'query', 'totalResults', 'savedPaperIds', 'categories', 'availableYears', 'categoryId', 'year'));
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
}