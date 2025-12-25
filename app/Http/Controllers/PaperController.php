<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\Category;
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
        $categoryId = $request->input('category', '');
        $year = $request->input('year', '');
        
        // Get all categories for filter dropdown
        $categories = Category::orderBy('name', 'asc')->get();
        
        // Get available years for filter (from approved papers)
        $availableYears = Paper::where('status', 'approved')
            ->distinct()
            ->orderBy('publication_year', 'desc')
            ->pluck('publication_year')
            ->toArray();

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

        $papers = $papers->paginate(12);
        $totalResults = $papers->total();

        return view('paper.recent', compact('papers', 'query', 'totalResults', 'categories', 'availableYears', 'categoryId', 'year'));
    }
}