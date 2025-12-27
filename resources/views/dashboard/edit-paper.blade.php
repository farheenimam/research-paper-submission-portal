@extends('layout')

@section('title', 'Update Paper - Research Portal')
@section('description', 'Update your research paper on Research Portal.')

@section('content')
<div class="upload-container">
    <div class="container">
        <div class="upload-header">
            <h1>Update Research Paper</h1>
            <p>Update your research paper information</p>
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

        <form method="POST" action="{{ route('dashboard.update-paper', $paper->id) }}" enctype="multipart/form-data" class="upload-form">
            @csrf
            @method('PUT')
            
            <!-- Paper Information -->
            <div class="form-section">
                <h3>Paper Information</h3>
                
                <div class="form-group">
                    <label for="title" class="form-label">Paper Title <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control" 
                           placeholder="Enter the title of your research paper" 
                           value="{{ old('title', $paper->title) }}" 
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
                              title="Abstract must be at least 50 characters long">{{ old('abstract', $paper->abstract) }}</textarea>
                    <div class="help-text">Provide a detailed summary of your research, methodology, and findings.</div>
                </div>

                <div class="form-group">
                    <label for="publication_year" class="form-label">Publication Year <span class="required">*</span></label>
                    <input type="number" id="publication_year" name="publication_year" class="form-control" 
                           min="1900" 
                           max="{{ date('Y') + 1 }}" 
                           value="{{ old('publication_year', $paper->publication_year) }}" 
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
                                <option value="{{ $category->id }}" 
                                    {{ old('category_id', $paper->categories->first()->id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <div class="help-text">Select the category that best describes your research paper</div>
                </div>

                <div class="form-group">
                    <label class="form-label">PDF File</label>
                    @if($paper->pdf_path)
                        <div class="current-file" style="margin-bottom: 10px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            <strong>Current PDF:</strong> 
                            <a href="{{ asset($paper->pdf_path) }}" target="_blank" style="color: #2d5016; text-decoration: underline;">
                                View Current PDF
                            </a>
                        </div>
                    @endif
                    <div class="help-text" style="color: #666666; font-style: italic;">PDF file cannot be updated. If you need to change the PDF, please contact support.</div>
                </div>
            </div>

            <!-- Authors Information -->
            <div class="form-section">
                <h3>Authors Information</h3>
                <p class="section-description">Add all authors who contributed to this research paper.</p>
                
                <div id="authors-container">
                    @php
                        $authors = $paper->authors->sortBy('id');
                        $firstAuthor = $authors->first();
                    @endphp
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
                                       value="{{ old('authors.0.name', $firstAuthor->author_name ?? Auth::user()->name) }}" required maxlength="150" readonly>
                                <div class="help-text">This is automatically set to your name (Author 1)</div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="authors[0][email]" class="form-control" 
                                       placeholder="author@example.com" 
                                       value="{{ old('authors.0.email', $firstAuthor->author_email ?? Auth::user()->email) }}" 
                                       maxlength="150" 
                                       readonly
                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                       title="Please enter a valid email address">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Affiliation</label>
                                <input type="text" name="authors[0][affiliation]" class="form-control" 
                                       placeholder="University, Organization, or Company" 
                                       value="{{ old('authors.0.affiliation', $firstAuthor->affiliation ?? Auth::user()->affiliation) }}" 
                                       maxlength="255" 
                                       readonly
                                       pattern=".{0,255}"
                                       title="Affiliation must not exceed 255 characters">
                            </div>
                        </div>
                    </div>
                    
                    @php
                        $additionalAuthors = $authors->skip(1);
                    @endphp
                    @foreach($additionalAuthors as $author)
                        @php
                            $authorIndex = $loop->index + 1;
                        @endphp
                        <div class="author-entry" data-author-index="{{ $authorIndex }}">
                            <div class="author-header">
                                <h4>Author {{ $authorIndex + 1 }}</h4>
                                <button type="button" class="remove-author" onclick="removeAuthor({{ $authorIndex }})">Remove</button>
                            </div>
                            
                            <div class="author-fields">
                                <div class="form-group">
                                    <label class="form-label">Author Name <span class="required">*</span></label>
                                    <input type="text" name="authors[{{ $authorIndex }}][name]" class="form-control" 
                                           placeholder="Full name of the author" 
                                           value="{{ old('authors.' . $authorIndex . '.name', $author->author_name) }}" 
                                           required 
                                           minlength="2"
                                           maxlength="150"
                                           pattern="[A-Za-z\s]{2,}"
                                           title="Author name must be at least 2 characters and contain only letters and spaces">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="authors[{{ $authorIndex }}][email]" class="form-control" 
                                           placeholder="author@example.com" 
                                           value="{{ old('authors.' . $authorIndex . '.email', $author->author_email) }}" 
                                           maxlength="150"
                                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                           title="Please enter a valid email address">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Affiliation</label>
                                    <input type="text" name="authors[{{ $authorIndex }}][affiliation]" class="form-control" 
                                           placeholder="University, Organization, or Company" 
                                           value="{{ old('authors.' . $authorIndex . '.affiliation', $author->affiliation) }}" 
                                           maxlength="255"
                                           pattern=".{0,255}"
                                           title="Affiliation must not exceed 255 characters">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <button type="button" id="add-author" class="btn btn-outline">Add Another Author</button>
            </div>

            <!-- Submit Section -->
            <div class="form-actions">
                <a href="{{ route('dashboard') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Paper</button>
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
document.addEventListener('DOMContentLoaded', function() {
    authorManagement.init({{ $paper->authors->count() }});
});
</script>
@endsection

