@extends('layout')

@section('title', 'Upload Paper - Research Portal')
@section('description', 'Upload your research paper to Research Portal for peer review.')

@section('content')
<div class="upload-container">
    <div class="container">
        <div class="upload-header">
            <h1>Upload Research Paper</h1>
            <p>Submit your research paper for peer review and publication</p>
        </div>

        @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.store-paper') }}" enctype="multipart/form-data" class="upload-form">
            @csrf
            
            <!-- Paper Information -->
            <div class="form-section">
                <h3>Paper Information</h3>
                
                <div class="form-group">
                    <label for="title" class="form-label">Paper Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" 
                           placeholder="Enter the title of your research paper" 
                           value="{{ old('title') }}" 
                           required 
                           minlength="5"
                           maxlength="255"
                           pattern=".{5,255}"
                           title="Title must be between 5 and 255 characters">
                </div>

                <div class="form-group">
                    <label for="abstract" class="form-label">Abstract <span class="required">*</span></label>
                    <textarea id="abstract" name="abstract" class="form-control abstract-field" 
                              placeholder="Provide a comprehensive abstract of your research paper" 
                              required
                              minlength="50"
                              title="Abstract must be at least 50 characters long">{{ old('abstract') }}</textarea>
                    <div class="help-text">Provide a detailed summary of your research, methodology, and findings.</div>
                </div>

                <div class="form-group">
                    <label for="publication_year" class="form-label">Publication Year <span class="required">*</span></label>
                    <input type="number" id="publication_year" name="publication_year" class="form-control" 
                           min="1900" 
                           max="{{ date('Y') + 1 }}" 
                           value="{{ old('publication_year', date('Y')) }}" 
                           required
                           step="1"
                           title="Publication year must be between 1900 and {{ date('Y') + 1 }}">
                </div>

                <div class="form-group">
                    <label for="category_id" class="form-label">Category <span class="required">*</span></label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="">Select a category</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <div class="help-text">Select the category that best describes your research paper</div>
                </div>

                <div class="form-group">
                    <label for="pdf_file" class="form-label">PDF File <span class="required">*</span></label>
                    <input type="file" id="pdf_file" name="pdf_file" class="form-control" 
                           accept=".pdf,application/pdf" 
                           required
                           title="Please upload a PDF file (max 10MB)">
                    <div class="help-text">Upload your research paper in PDF format. Maximum file size: 10MB</div>
                </div>
            </div>

            <!-- Authors Information -->
            <div class="form-section">
                <h3>Authors Information</h3>
                <p class="section-description">Add all authors who contributed to this research paper.</p>
                
                <div id="authors-container">
                    <div class="author-entry" data-author-index="0">
                        <div class="author-header">
                            <h4>Author 1</h4>
                            <button type="button" class="remove-author" onclick="removeAuthor(0)" style="display: none;">Remove</button>
                        </div>
                        
                        <div class="author-fields">
                            <div class="form-group">
                                <label class="form-label">Author Name <span class="required">*</span></label>
                                <input type="text" name="authors[0][name]" class="form-control" 
                                       placeholder="Full name of the author" 
                                       value="{{ old('authors.0.name', Auth::user()->name) }}" required maxlength="150" readonly>
                                <div class="help-text">This is automatically set to your name (Author 1)</div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="authors[0][email]" class="form-control" 
                                       placeholder="author@example.com" 
                                       value="{{ old('authors.0.email', Auth::user()->email) }}" 
                                       maxlength="150" 
                                       readonly
                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                       title="Please enter a valid email address">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Affiliation</label>
                                <input type="text" name="authors[0][affiliation]" class="form-control" 
                                       placeholder="University, Organization, or Company" 
                                       value="{{ old('authors.0.affiliation', Auth::user()->affiliation) }}" 
                                       maxlength="255" 
                                       readonly
                                       pattern=".{0,255}"
                                       title="Affiliation must not exceed 255 characters">
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="add-author" class="btn btn-outline">Add Another Author</button>
            </div>

            <!-- Submit Section -->
            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Upload Paper</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script>
let authorIndex = 1;

document.getElementById('add-author').addEventListener('click', function() {
    const container = document.getElementById('authors-container');
    const newAuthor = document.createElement('div');
    newAuthor.className = 'author-entry';
    newAuthor.setAttribute('data-author-index', authorIndex);
    
    newAuthor.innerHTML = `
        <div class="author-header">
            <h4>Author ${authorIndex + 1}</h4>
            <button type="button" class="remove-author" onclick="removeAuthor(${authorIndex})">Remove</button>
        </div>
        
        <div class="author-fields">
            <div class="form-group">
                <label class="form-label">Author Name <span class="required">*</span></label>
                <input type="text" name="authors[${authorIndex}][name]" class="form-control" 
                       placeholder="Full name of the author" 
                       required 
                       minlength="2"
                       maxlength="150"
                       pattern="[A-Za-z\s]{2,}"
                       title="Author name must be at least 2 characters and contain only letters and spaces">
            </div>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="authors[${authorIndex}][email]" class="form-control" 
                       placeholder="author@example.com" 
                       maxlength="150"
                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                       title="Please enter a valid email address">
            </div>
            
            <div class="form-group">
                <label class="form-label">Affiliation</label>
                <input type="text" name="authors[${authorIndex}][affiliation]" class="form-control" 
                       placeholder="University, Organization, or Company" 
                       maxlength="255"
                       pattern=".{0,255}"
                       title="Affiliation must not exceed 255 characters">
            </div>
        </div>
    `;
    
    container.appendChild(newAuthor);
    authorIndex++;
    
    // Show remove button for first author if there are multiple authors
    updateRemoveButtons();
});

function removeAuthor(index) {
    const authorEntry = document.querySelector(`[data-author-index="${index}"]`);
    if (authorEntry) {
        authorEntry.remove();
        updateAuthorNumbers();
        updateRemoveButtons();
    }
}

function updateAuthorNumbers() {
    const authors = document.querySelectorAll('.author-entry');
    authors.forEach((author, index) => {
        const header = author.querySelector('h4');
        header.textContent = `Author ${index + 1}`;
    });
}

function updateRemoveButtons() {
    const authors = document.querySelectorAll('.author-entry');
    const removeButtons = document.querySelectorAll('.remove-author');
    
    if (authors.length > 1) {
        removeButtons.forEach(button => button.style.display = 'inline-block');
    } else {
        removeButtons.forEach(button => button.style.display = 'none');
    }
}
</script>
@endsection