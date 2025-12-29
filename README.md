# Research Portal

A comprehensive web-based platform for academic research paper management, publication, and discovery. This Laravel-based application enables researchers to upload, manage, and share their research papers while providing readers with an intuitive search and discovery interface.

## 📋 Table of Contents

- [About](#about)
- [Purpose](#purpose)
- [Technologies Used](#technologies-used)
- [Features](#features)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [User Roles](#user-roles)
- [Database Schema](#database-schema)
- [Security Features](#security-features)

## 🎯 About

Research Portal is a full-featured academic research management system that facilitates:
- **Research Paper Publication**: Researchers can upload and publish their academic papers
- **Paper Discovery**: Advanced search functionality to find research papers by title, category, and year
- **Paper Management**: Save favorite papers, track uploads, and manage research collections
- **Admin Control**: Comprehensive admin dashboard for managing users, papers, authors, and categories
- **Peer Review System**: Admin-based approval/rejection workflow for paper publication

## 🎯 Purpose

This platform serves as a centralized hub for:
1. **Researchers**: Upload, manage, and track their research publications
2. **Readers**: Discover, read, and save academic research papers
3. **Administrators**: Review, approve, and manage all platform content
4. **Academic Community**: Facilitate knowledge sharing and collaboration

## 🛠 Technologies Used

### Backend
- **Laravel 11.x** - PHP web framework
- **PHP 8.1+** - Server-side scripting language
- **SQLite** - Lightweight database (can be configured for MySQL/PostgreSQL)
- **Eloquent ORM** - Laravel's database abstraction layer

### Frontend
- **Blade Templating Engine** - Laravel's server-side templating
- **HTML5 & CSS3** - Modern web standards
- **JavaScript (Vanilla)** - Client-side interactivity
- **Vite** - Modern frontend build tool
- **Tailwind CSS 4.0** - Utility-first CSS framework

### Key Libraries & Tools
- **Axios** - HTTP client for AJAX requests
- **Laravel Vite Plugin** - Asset bundling
- **Laravel Session Management** - User session handling
- **Laravel Authentication** - User authentication system

## ✨ Features

### 🔐 Authentication & Authorization
- User registration with role-based access (Reader, Researcher, Admin)
- Secure login/logout functionality
- Session management with visit tracking
- Password encryption using bcrypt
- CSRF protection on all forms

### 📄 Paper Management
- **Upload Papers**: Researchers can upload PDF research papers
- **Paper Metadata**: Title, abstract, publication year, categories
- **PDF Preview**: In-browser PDF viewing using iframe
- **Download Support**: Direct PDF download functionality
- **Status Tracking**: Pending, Approved, Rejected status workflow

### 🔍 Search & Discovery
- **Advanced Search**: Search papers by title/keywords
- **Category Filtering**: Filter papers by research categories
- **Year Filtering**: Filter papers by publication year
- **Pagination**: Efficient pagination for large result sets
- **Save Papers**: Readers can save papers for later access

### 👤 User Profiles
- Profile management with photo upload
- User information (name, email, affiliation, bio)
- Profile photo management (upload/remove)
- Admin can view and update any user's profile

### 📊 Admin Dashboard
- **User Management**: View, delete users, view user details
- **Paper Management**: View, delete papers, approve/reject submissions
- **Author Management**: View and manage paper authors
- **Category Management**: Manage research categories
- **Review System**: Approve or reject pending papers
- **Statistics**: Track pending papers count

### 📱 Researcher Dashboard
- View all uploaded papers
- Upload new research papers
- Track paper status (pending/approved/rejected)
- View paper details with PDF preview
- Monitor paper statistics

### 📚 Reader Features
- Search and discover research papers
- Save papers to personal collection
- View saved papers list
- Access paper details and PDFs

### 🎨 User Interface
- Responsive design for all devices
- Modern, clean UI/UX
- Intuitive navigation
- Flash message notifications
- Breadcrumb navigation

## 📁 Project Structure

```
research2/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AdminController.php      # Admin dashboard & management
│   │       ├── AuthController.php        # Authentication (login/register)
│   │       ├── DashboardController.php  # Researcher dashboard
│   │       ├── PaperController.php       # Public paper viewing
│   │       ├── ProfileController.php    # User profile management
│   │       └── SearchController.php     # Search & saved papers
│   └── Models/
│       ├── User.php                     # User model
│       ├── Paper.php                    # Research paper model
│       ├── PaperAuthor.php             # Paper author model
│       ├── Category.php                # Category model
│       ├── SavedPaper.php              # Saved papers model
│       └── Role.php                    # User role model
├── database/
│   ├── migrations/                      # Database migrations
│   └── seeders/                        # Database seeders
├── public/
│   ├── css/                            # Compiled CSS files
│   ├── js/                             # JavaScript files
│   ├── papers/                         # Uploaded PDF files
│   └── uploads/profiles/               # User profile photos
├── resources/
│   ├── views/                          # Blade templates
│   │   ├── admin/                      # Admin views
│   │   ├── auth/                       # Login/Register views
│   │   ├── dashboard/                  # Researcher dashboard
│   │   ├── search/                     # Search results
│   │   ├── paper/                      # Paper viewing
│   │   └── profile/                    # Profile management
│   └── css/                            # Source CSS files
├── routes/
│   └── web.php                         # Application routes
└── config/                             # Configuration files
```

## 🚀 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js and npm
- SQLite (or MySQL/PostgreSQL)

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd research2
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install Node Dependencies
```bash
npm install
```

### Step 4: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### Step 5: Database Setup
```bash
# For SQLite (default)
touch database/database.sqlite

# Or configure MySQL/PostgreSQL in .env file
```

### Step 6: Run Migrations
```bash
php artisan migrate
php artisan db:seed
```

### Step 7: Build Assets
```bash
npm run build
# Or for development
npm run dev
```

### Step 8: Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## ⚙️ Configuration

### Database Configuration
Edit `.env` file:
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Or for MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=research_portal
DB_USERNAME=root
DB_PASSWORD=
```

### Session Configuration
Sessions are stored in the database by default. Configure in `config/session.php`:
```php
'driver' => env('SESSION_DRIVER', 'database'),
'lifetime' => env('SESSION_LIFETIME', 120),
```

### File Storage
- PDF files: `public/papers/`
- Profile photos: `public/uploads/profiles/`
- Ensure `public` directory is writable

## 📖 Usage

### For Researchers
1. **Register/Login**: Create an account or login
2. **Upload Paper**: Navigate to Dashboard → Upload Paper
3. **Fill Details**: Enter title, abstract, publication year, category
4. **Upload PDF**: Select and upload your research paper PDF
5. **Submit**: Paper will be submitted for admin review
6. **Track Status**: Monitor paper status in your dashboard

### For Readers
1. **Register/Login**: Create an account or login
2. **Search Papers**: Use the search bar to find papers
3. **Apply Filters**: Filter by category or year
4. **Save Papers**: Click "Save" to add papers to your collection
5. **View Details**: Click "View Details" to read full paper
6. **Download PDF**: Download papers directly

### For Administrators
1. **Login**: Use admin credentials
2. **Dashboard**: Access admin dashboard
3. **Review Papers**: View pending papers and approve/reject
4. **Manage Users**: View, delete, or update user profiles
5. **Manage Content**: Delete papers, authors, or categories as needed

## 👥 User Roles

### 1. **Reader (Role ID: 4)**
- Default role for new registrations
- Can search and view papers
- Can save papers to collection
- Access to search functionality

### 2. **Researcher (Role ID: 2)**
- Can upload research papers
- Can manage own papers
- Can view paper status
- Access to researcher dashboard

### 3. **Admin**
- Full system access
- Can approve/reject papers
- Can manage all users
- Can delete any content
- Access to admin dashboard

## 🗄️ Database Schema

### Core Tables
- **users**: User accounts and profiles
- **roles**: User role definitions
- **papers**: Research paper information
- **paper_authors**: Paper-author relationships
- **categories**: Research categories
- **paper_category**: Many-to-many relationship
- **saved_papers**: User saved papers
- **sessions**: Laravel session storage

### Relationships
- User → Papers (One-to-Many)
- Paper → Authors (Many-to-Many via PaperAuthor)
- Paper → Categories (Many-to-Many)
- User → SavedPapers (One-to-Many)
- User → Role (Many-to-One)

## 🔒 Security Features

- **CSRF Protection**: All forms protected with CSRF tokens
- **Password Hashing**: Bcrypt encryption for passwords
- **Session Security**: Session regeneration on login
- **Authentication Middleware**: Protected routes require login
- **File Upload Validation**: PDF file type and size validation
- **SQL Injection Prevention**: Eloquent ORM prevents SQL injection
- **XSS Protection**: Blade templating escapes output by default

## 📝 Key Features Explained

### PDF Display
- PDFs are stored in `public/papers/` directory
- Displayed using HTML `<iframe>` tag
- Browser's built-in PDF viewer renders the PDF
- Direct download links available

### Session Visit Tracking
- Tracks user visits per session
- Increments on login and page visits
- Displays visit count on search and admin pages
- Session-based (resets on logout)

### Paper Approval Workflow
1. Researcher uploads paper → Status: "pending"
2. Admin reviews paper → Views PDF and details
3. Admin approves/rejects → Status: "approved" or "rejected"
4. Approved papers appear in search results

## 🤝 Contributing

This is a research project. For contributions:
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Developer Notes

- Default admin email: `farheenimam@gmail.com`
- Session driver: Database (configurable)
- File storage: Public directory
- Default role for new users: Reader (ID: 4)

## 🐛 Troubleshooting

### Common Issues

**PDF not displaying:**
- Check file permissions on `public/papers/`
- Verify PDF file exists in correct location
- Check browser PDF viewer support

**Session not working:**
- Run `php artisan migrate` to create sessions table
- Check `config/session.php` configuration
- Clear cache: `php artisan cache:clear`

**File upload fails:**
- Check `public` directory permissions
- Verify `upload_max_filesize` in PHP.ini
- Check file type validation rules

---

**Built with ❤️ using Laravel**
