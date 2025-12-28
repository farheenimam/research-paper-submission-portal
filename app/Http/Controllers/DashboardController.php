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
    /**
     * Display the researcher's dashboard with their uploaded papers
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Auth::user() - Gets the currently logged-in user object
        // Returns User model instance or null if not logged in
        $user = Auth::user();
        
        // Paper::where() - Filters papers from database
        // ->where('uploaded_by', $user->id) - Only get papers uploaded by this user
        // ->with('authors') - Eager loading: Load authors relationship to avoid N+1 queries
        // ->orderBy('created_at', 'desc') - Sort papers by creation date, newest first
        // ->get() - Execute query and return Collection of Paper models (all columns included)
        $papers = Paper::where('uploaded_by', $user->id)
                      ->with('authors')
                      ->orderBy('created_at', 'desc')
                      ->get();
        
        // view() - Returns a Blade view
        // 'dashboard.index' - Path to view file: resources/views/dashboard/index.blade.php
        // compact('user', 'papers') - Creates array ['user' => $user, 'papers' => $papers]
        // This passes variables to the view
        return view('dashboard.index', compact('user', 'papers'));
    }

    /**
     * Show the form to upload a new research paper
     * 
     * @return \Illuminate\View\View
     */
    public function uploadPaper()
    {
        // Category::orderBy('name', 'asc') - Get all categories sorted alphabetically
        // ->get() - Execute query and return Collection of Category models
        $categories = Category::orderBy('name', 'asc')->get();
        
        // Return upload paper form view with categories list
        return view('dashboard.upload-paper', compact('categories'));
    }

    /**
     * Save a new research paper to database
     * 
     * @param Request $request - Contains form data (title, abstract, pdf_file, etc.)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePaper(Request $request)
    {
        // $request->validate() - Validates form input according to rules
        // If validation fails, automatically redirects back with errors
        // 'required' - Field must be present
        // 'string' - Must be text
        // 'max:255' - Maximum 255 characters
        // 'regex:/.*[A-Za-z].*/' - Must contain at least one letter
        // 'file|mimes:pdf|max:10240' - Must be PDF file, max 10MB
        // 'exists:categories,id' - Category ID must exist in categories table
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
        // $request->file('pdf_file') - Get uploaded file from form
        // Returns UploadedFile object
        $pdfFile = $request->file('pdf_file');
        
        // time() - Current Unix timestamp (e.g., 1703123456)
        // getClientOriginalName() - Original filename from user's computer
        // Combine to create unique filename: "1703123456_research_paper.pdf"
        $filename = time() . '_' . $pdfFile->getClientOriginalName();
        $pdfPath = 'papers/' . $filename;
        
        // public_path('papers') - Returns full path: C:\...\public\papers
        // move() - Moves uploaded file to public/papers directory
        $pdfFile->move(public_path('papers'), $filename);

        // Create paper record in database
        // Paper::create() - Creates new Paper record and saves to database
        // Returns the created Paper model instance
        // Auth::id() - Gets logged-in user's ID (shorter than Auth::user()->id)
        $paper = Paper::create([
            'title' => $request->title,              // From form input
            'abstract' => $request->abstract,         // From form textarea
            'pdf_path' => $pdfPath,                  // Path we created above
            'publication_year' => $request->publication_year,  // From form
            'status' => 'pending',                    // Default status (waiting for admin approval)
            'uploaded_by' => Auth::id(),             // Current user's ID
        ]);

        // Attach category to paper (many-to-many relationship)
        // $paper->categories() - Access the categories relationship
        // ->attach($id) - Links paper to category in pivot table (paper_category)
        // Creates entry: paper_id = $paper->id, category_id = $request->category_id
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

        // Session::flash() - Store message in session for one request only
        // Message will be shown on next page load, then automatically removed
        Session::flash('success', 'Paper uploaded successfully! It is now pending review.');
        
        // redirect()->route('dashboard') - Redirects to named route
        // 'dashboard' is defined in routes/web.php
        return redirect()->route('dashboard');
    }

    /**
     * Show details of a specific paper
     * 
     * @param int $id - Paper ID from URL
     * @return \Illuminate\View\View
     */
    public function viewPaper($id)
    {
        // Paper::with(['authors', 'uploader']) - Eager load relationships
        // ->where('id', $id) - Find paper with this ID
        // ->where('uploaded_by', Auth::id()) - Only if uploaded by current user (security)
        // ->firstOrFail() - Get first result or throw 404 error if not found
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

        // Update category (many-to-many relationship)
        // ->sync([$id]) - Replaces all categories with this one
        // Removes old category links and creates new one
        // Note: sync() requires array, so wrap single ID in array
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