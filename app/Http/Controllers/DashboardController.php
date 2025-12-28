<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperAuthor;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        
        $papers = Paper::where('uploaded_by', $user->id)
                      ->with('authors')
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        return view('dashboard.index', compact('user', 'papers'));
    }

    public function uploadPaper()
    {
        $categories = \App\Models\Category::orderBy('name', 'asc')->get();
        return view('dashboard.upload-paper', compact('categories'));
    }

    public function storePaper(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|regex:/.*[A-Za-z].*/',
            'abstract' => 'required|string',
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // 10MB max
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
            'authors' => 'required|array|min:1',
            'authors.*.name' => 'required|string|max:150',
            'authors.*.email' => 'nullable|email|max:150',
            'authors.*.affiliation' => 'nullable|string|max:255',
        ]);

        // Handle PDF upload
        $pdfFile = $request->file('pdf_file');
        $filename = time() . '_' . $pdfFile->getClientOriginalName();
        $pdfPath = 'papers/' . $filename;
        $pdfFile->move(public_path('papers'), $filename);

        // Create paper record
        $paper = Paper::create([
            'title' => $request->title,
            'abstract' => $request->abstract,
            'pdf_path' => $pdfPath,
            'publication_year' => $request->publication_year,
            'status' => 'pending',
            'uploaded_by' => Auth::id(),
        ]);

        // Attach category to paper
        $paper->categories()->attach($request->category_id);

        // Get the logged-in user
        $user = Auth::user();

        // Create author 1 - automatically the logged-in user
        PaperAuthor::create([
            'paper_id' => $paper->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'author_email' => $user->email,
            'affiliation' => $user->affiliation,
        ]);

        // Create additional author records (skip index 0 as it's the logged-in user)
        if (isset($request->authors) && count($request->authors) > 1) {
            // Start from index 1, skip index 0 (the logged-in user)
            for ($i = 1; $i < count($request->authors); $i++) {
                $authorData = $request->authors[$i];
                if (!empty($authorData['name'])) {
                    PaperAuthor::create([
                        'paper_id' => $paper->id,
                        'user_id' => null, // We could match by email later
                        'author_name' => $authorData['name'],
                        'author_email' => $authorData['email'] ?? null,
                        'affiliation' => $authorData['affiliation'] ?? null,
                    ]);
                }
            }
        }

        Session::flash('success', 'Paper uploaded successfully! It is now pending review.');
        return redirect()->route('dashboard');
    }

    public function viewPaper($id)
    {
        $paper = Paper::with(['authors', 'uploader'])
                     ->where('id', $id)
                     ->where('uploaded_by', Auth::id())
                     ->firstOrFail();
        
        return view('dashboard.view-paper', compact('paper'));
    }

    public function editPaper($id)
    {
        $paper = Paper::with(['authors', 'categories'])
                     ->where('id', $id)
                     ->where('uploaded_by', Auth::id())
                     ->firstOrFail();
        
        $categories = Category::orderBy('name', 'asc')->get();
        
        return view('dashboard.edit-paper', compact('paper', 'categories'));
    }

    public function updatePaper(Request $request, $id)
    {
        $paper = Paper::where('id', $id)
                     ->where('uploaded_by', Auth::id())
                     ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255|regex:/.*[A-Za-z].*/',
            'abstract' => 'required|string',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
            'authors' => 'required|array|min:1',
            'authors.*.name' => 'required|string|max:150',
            'authors.*.email' => 'nullable|email|max:150',
            'authors.*.affiliation' => 'nullable|string|max:255',
        ]);

        // PDF file cannot be updated by researchers

        // Update paper record
        $paper->title = $request->title;
        $paper->abstract = $request->abstract;
        $paper->publication_year = $request->publication_year;
        $paper->save();

        // Update category
        $paper->categories()->sync([$request->category_id]);

        // Get the logged-in user
        $user = Auth::user();

        // Delete existing authors (except we'll recreate them)
        $paper->authors()->delete();

        // Recreate Author 1 - automatically the logged-in user
        PaperAuthor::create([
            'paper_id' => $paper->id,
            'user_id' => $user->id,
            'author_name' => $user->name,
            'author_email' => $user->email,
            'affiliation' => $user->affiliation,
        ]);

        // Create additional author records (skip index 0 as it's the logged-in user)
        if (isset($request->authors) && count($request->authors) > 1) {
            // Start from index 1, skip index 0 (the logged-in user)
            for ($i = 1; $i < count($request->authors); $i++) {
                $authorData = $request->authors[$i];
                if (!empty($authorData['name'])) {
                    PaperAuthor::create([
                        'paper_id' => $paper->id,
                        'user_id' => null,
                        'author_name' => $authorData['name'],
                        'author_email' => $authorData['email'] ?? null,
                        'affiliation' => $authorData['affiliation'] ?? null,
                    ]);
                }
            }
        }

        Session::flash('success', 'Paper updated successfully!');
        return redirect()->route('dashboard');
    }
}