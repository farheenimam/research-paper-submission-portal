<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    {{-- asset() - Generates URL for favicon image in public/images folder --}}
    {{-- ?v={{ time() }} - Cache busting: Forces browser to reload image --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v={{ time() }}">
    
    <link rel="stylesheet" href="css/main.css">
    
    <title>@yield('title', 'Research Portal - Academic Research Platform')</title>
    
    <!-- Meta Description -->
    <meta name="description" content="@yield('description', 'A comprehensive platform for academic research, collaboration, and knowledge sharing.')">
    
    <!-- CSS Files -->
    {{-- asset() - Laravel helper: Generates URL for files in public folder --}}
    {{-- asset('css/main.css') converts to: http://yoursite.com/css/main.css --}}
    {{-- Works for CSS, JS, images, PDFs - any file in public/ directory --}}
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/header.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    
    <!-- Additional CSS -->
    @stack('styles')
    
    <!-- Custom CSS for specific pages -->
    @yield('styles')
</head>
<body class="@yield('body-class')">
    <!-- Header -->
    @include('header')
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success" style="margin: 0; border-radius: 0;">
            <div class="container">
                {{ session('success') }}
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-error" style="margin: 0; border-radius: 0;">
            <div class="container">
                {{ session('error') }}
            </div>
        </div>
    @endif
    
    @if(session('info'))
        <div class="alert alert-info" style="margin: 0; border-radius: 0;">
            <div class="container">
                {{ session('info') }}
            </div>
        </div>
    @endif
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>Research Portal</h4>
                    <p>Advancing academic research through collaboration and innovation.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">📘</a>
                        <a href="#" aria-label="Twitter">🐦</a>
                        <a href="#" aria-label="LinkedIn">💼</a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="{{ route('welcome') }}">Home</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h5>Research</h5>
                    <ul>
                        <li><a href="#">Browse Papers</a></li>
                        <li><a href="#">Submit Research</a></li>
                        <li><a href="#">Peer Review</a></li>
                        <li><a href="#">Guidelines</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h5>Support</h5>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Research Portal. All rights reserved.</p>
                <p>Built with ❤️ for the academic community</p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    {{-- asset() - Generates URL for JavaScript files in public/js folder --}}
    {{-- asset('js/common.js') => http://site.com/js/common.js --}}
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Additional JavaScript -->
    @stack('scripts')
    
    <!-- Custom JavaScript for specific pages -->
    @yield('scripts')
</body>
</html>