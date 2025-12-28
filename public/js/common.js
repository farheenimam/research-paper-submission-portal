// Common JavaScript functions for the application

// Author Management Functions (for upload/edit paper pages)
window.authorManagement = {
    index: 1,
    
    init: function(initialCount = 1) {
        this.index = initialCount;
        const addBtn = document.getElementById('add-author');
        if (addBtn) {
            addBtn.addEventListener('click', () => this.addAuthor());
        }
        this.updateRemoveButtons();
    },
    
    addAuthor: function() {
        const container = document.getElementById('authors-container');
        if (!container) return;
        
        const newAuthor = document.createElement('div');
        newAuthor.className = 'author-entry';
        newAuthor.setAttribute('data-author-index', this.index);
        
        newAuthor.innerHTML = `
            <div class="author-header">
                <h4>Author ${this.index + 1}</h4>
                <button type="button" class="remove-author" onclick="authorManagement.removeAuthor(${this.index})">Remove</button>
            </div>
            <div class="author-fields">
                <div class="form-group">
                    <label class="form-label">Author Name <span class="required">*</span></label>
                    <input type="text" name="authors[${this.index}][name]" class="form-control" 
                           placeholder="Full name of the author" required minlength="2" maxlength="150"
                           pattern="[A-Za-z\\s]{2,}" 
                           title="Author name must be at least 2 characters and contain only letters and spaces">
                </div>
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="authors[${this.index}][email]" class="form-control" 
                           placeholder="author@example.com" maxlength="150"
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\\.[a-z]{2,}$" 
                           title="Please enter a valid email address">
                </div>
                <div class="form-group">
                    <label class="form-label">Affiliation</label>
                    <input type="text" name="authors[${this.index}][affiliation]" class="form-control" 
                           placeholder="University, Organization, or Company" maxlength="255"
                           pattern=".{0,255}" title="Affiliation must not exceed 255 characters">
                </div>
            </div>
        `;
        
        container.appendChild(newAuthor);
        this.index++;
        this.updateRemoveButtons();
    },
    
    removeAuthor: function(index) {
        const authorEntry = document.querySelector(`[data-author-index="${index}"]`);
        if (authorEntry) {
            authorEntry.remove();
            this.updateAuthorNumbers();
            this.updateRemoveButtons();
        }
    },
    
    updateAuthorNumbers: function() {
        const authors = document.querySelectorAll('.author-entry');
        authors.forEach((author, index) => {
            const header = author.querySelector('h4');
            if (header) header.textContent = `Author ${index + 1}`;
        });
    },
    
    updateRemoveButtons: function() {
        const authors = document.querySelectorAll('.author-entry');
        const removeButtons = document.querySelectorAll('.remove-author');
        const display = authors.length > 1 ? 'inline-block' : 'none';
        removeButtons.forEach(button => button.style.display = display);
    }
};

// Save Paper Function (for search/saved papers pages)
window.toggleSavePaper = function(paperId, button) {
    fetch(`/search/save/${paperId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.textContent = data.saved ? '✓ Saved' : 'Save';
            button.classList.toggle('saved', data.saved);
            if (!data.saved && window.location.pathname.includes('saved-papers')) {
                button.closest('.paper-result-horizontal')?.remove();
            }
        } else {
            alert(data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the paper');
    });
};

// Copy Citation Function (for paper view page)
window.copyCitation = function() {
    const citationText = document.getElementById('citation-text')?.textContent;
    if (!citationText) return;
    
    navigator.clipboard.writeText(citationText).then(() => {
        const btn = document.getElementById('copy-citation-btn');
        if (btn) {
            const originalText = btn.textContent;
            btn.textContent = '✓ Copied!';
            setTimeout(() => { btn.textContent = originalText; }, 2000);
        }
    }).catch(err => console.error('Failed to copy:', err));
};


// Header Navigation (Profile dropdown only)
window.headerNav = {
    init: function() {
        const profileToggle = document.getElementById('profileToggle');
        const profileDropdown = document.getElementById('profileDropdown');
        
        // Profile dropdown toggle
        if (profileToggle && profileDropdown) {
            profileToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.remove('show');
                }
            });
        }
    }
};

// Profile Photo Upload
window.profilePhoto = {
    init: function() {
        const fileInput = document.getElementById('profile_photo');
        const uploadBtn = document.getElementById('upload-btn');
        
        if (fileInput && uploadBtn) {
            fileInput.addEventListener('change', function() {
                uploadBtn.style.display = (this.files && this.files[0]) ? 'inline-block' : 'none';
            });
        }
    }
};


// Initialize all common functionality on page load
document.addEventListener('DOMContentLoaded', function() {
    headerNav.init();
    profilePhoto.init();
});

