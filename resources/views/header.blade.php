
<header class="header">
    
    <div class="container">
        <nav class="navbar">
            <!-- Logo/Brand -->
            <a href="{{ route('welcome') }}" class="navbar-brand">
                <div class="logo-icon">R</div>
                Research Portal
            </a>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-toggle" id="mobileToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <!-- Navigation Menu -->
            <ul class="navbar-nav" id="navbarNav">
                @guest
                    <!-- Navigation items only for guests (not logged in) -->
                    <li class="nav-item">
                        <a href="{{ route('welcome') }}" class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#services" class="nav-link">
                            Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#about" class="nav-link">
                            About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('welcome') }}#join-us" class="nav-link">
                            Join Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link nav-login-btn">
                            Login
                        </a>
                    </li>
                @endguest

                @auth
                    @if(Auth::user()->email === 'farheenimam@gmail.com')
                        <!-- Admin navigation links -->
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}#users" class="nav-link">
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}#papers" class="nav-link">
                                Papers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}#authors" class="nav-link">
                                Authors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}#categories" class="nav-link">
                                Categories
                            </a>
                        </li>
                    @elseif(Auth::user()->role_id == 4)
                        <!-- Search link for readers (role_id 4) -->
                        <li class="nav-item">
                            <a href="{{ route('search') }}" class="nav-link {{ request()->routeIs('search') ? 'active' : '' }}">
                                Search
                            </a>
                        </li>
                    @elseif(Auth::user()->role_id == 3)
                        <!-- Articles link for reviewers (role_id 3) -->
                        <li class="nav-item">
                            <a href="{{ route('reviewer.articles') }}" class="nav-link {{ request()->routeIs('reviewer.articles') ? 'active' : '' }}">
                                Articles
                            </a>
                        </li>
                    @else
                        <!-- Recent research work link for non-reviewers -->
                        <li class="nav-item">
                            <a href="{{ route('paper.recent') }}" class="nav-link {{ request()->routeIs('paper.recent') ? 'active' : '' }}">
                                Recent research work
                            </a>
                        </li>
                        <!-- My Research link for non-reviewers -->
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                                My Research
                            </a>
                        </li>
                    @endif

                    <!-- Logged in user profile -->
                    <li class="nav-item user-profile">
                        <button class="profile-toggle" id="profileToggle">
                            <div class="profile-avatar">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset(Auth::user()->profile_photo) }}" alt="Profile" class="profile-img">
                                @else
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <span style="margin-left: 8px;">▼</span>
                        </button>
                        
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                👤 My Profile
                            </a>
                            @if(Auth::user()->email === 'farheenimam@gmail.com')
                                <!-- Admin user - no additional dropdown items -->
                            @elseif(Auth::user()->role_id == 4)
                                <a href="{{ route('reader.saved-papers') }}" class="dropdown-item">
                                    📄 Saved Papers
                                </a>
                            @elseif(Auth::user()->role_id == 3)
                                <a href="{{ route('reviewer.history') }}" class="dropdown-item">
                                    📄 My History
                                </a>
                            @else
                                <a href="{{ route('dashboard.upload-paper') }}" class="dropdown-item">
                                    📤 Upload Research
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                🚪 Logout
                            </a>
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @else
                    <!-- No buttons for guest users -->
                @endauth
            </ul>
        </nav>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const navbarNav = document.getElementById('navbarNav');
    
    if (mobileToggle && navbarNav) {
        mobileToggle.addEventListener('click', function() {
            mobileToggle.classList.toggle('active');
            navbarNav.classList.toggle('show');
        });
    }
    
    // Profile dropdown toggle
    const profileToggle = document.getElementById('profileToggle');
    const profileDropdown = document.getElementById('profileDropdown');
    
    if (profileToggle && profileDropdown) {
        profileToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileToggle.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });
    }
    
    // Close mobile menu when clicking on nav links
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                mobileToggle.classList.remove('active');
                navbarNav.classList.remove('show');
            }
        });
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            mobileToggle.classList.remove('active');
            navbarNav.classList.remove('show');
            profileDropdown.classList.remove('show');
        }
    });
});
</script>