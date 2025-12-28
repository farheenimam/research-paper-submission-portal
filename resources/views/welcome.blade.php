@extends('layout')

@section('title', 'Research Portal - Academic Research Platform')
@section('description', 'A comprehensive platform for academic research, collaboration, and knowledge sharing.')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    <span class="badge-line"></span>
                    <span class="badge-text">Research Portal</span>
                </div>
                <h1>The world's largest collection of open access research papers</h1>
                
                <div class="search-container">
                    <form class="search-form" action="{{ route('search') }}" method="GET">
                        <input type="text" 
                               class="search-input" 
                               placeholder="Search 402M papers from around the world"
                               name="search"
                               value="{{ request('search') }}">
                        <button type="submit" class="search-btn">SEARCH</button>
                    </form>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="world-map-image">
                    <!-- You can replace this with your actual image -->
                    <img src="{{ asset('images/map.png') }}" 
                         alt="Global Research Network" 
                         class="map-image">
                    
        
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services-section">
    <div class="container">
        <h2 class="text-center mb-20">Our Services</h2>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">📄</div>
                <h3>Research Publication</h3>
                <p>Submit and publish your research papers with our streamlined peer review process.</p>
                <a href="#" class="service-link">Learn More →</a>
            </div>
            
            <div class="service-card">
                <div class="service-icon">🔍</div>
                <h3>Research Discovery</h3>
                <p>Discover and access a vast collection of academic papers and research publications.</p>
                <a href="#" class="service-link">Explore →</a>
            </div>
            
            <div class="service-card">
                <div class="service-icon">👥</div>
                <h3>Collaboration</h3>
                <p>Connect with researchers worldwide and collaborate on groundbreaking projects.</p>
                <a href="#" class="service-link">Connect →</a>
            </div>
            
            <div class="service-card">
                <div class="service-icon">⭐</div>
                <h3>Peer Review</h3>
                <p>Participate in the peer review process and help maintain research quality standards.</p>
                <a href="#" class="service-link">Review →</a>
            </div>
        </div>
    </div>
</section>

<!-- Join Us Section -->
<section id="join-us" class="join-section">
    <div class="container">
        <h2 class="text-center mb-20">Join Our Community</h2>
        <p class="text-center mb-20">Choose how you'd like to contribute to the academic research community</p>
        
        <div class="join-options">
            <!-- Join as Researcher -->
            <div class="join-card researcher-card">
                <div class="join-icon">🔬</div>
                <h3>Join as a Researcher</h3>
                <p>Share your research and collaborate with peers.</p>
                
                <div class="benefits-list">
                    <div class="benefit-item">✓ Publish research papers</div>
                    <div class="benefit-item">✓ Access research database</div>
                    <div class="benefit-item">✓ Collaboration tools</div>
                </div>
                
                <a href="{{ route('registration') }}?role=researcher" class="btn btn-primary">Join as Researcher</a>
            </div>
            
            <!-- Join as Reader -->
            <div class="join-card reader-card">
                <div class="join-icon">📚</div>
                <h3>Join as a Reader</h3>
                <p>Access and explore a vast collection of research papers.</p>
                
                <div class="benefits-list">
                    <div class="benefit-item">✓ Browse research papers</div>
                    <div class="benefit-item">✓ Download publications</div>
                    <div class="benefit-item">✓ Stay updated with research</div>
                </div>
                
                <a href="{{ route('registration') }}?role=reader" class="btn btn-outline">Join as Reader</a>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2>About Research Portal</h2>
                <p>Research Portal is a comprehensive platform designed to facilitate academic research, collaboration, and knowledge sharing. We connect researchers and academic institutions worldwide to advance scientific discovery and innovation.</p>
                
                <div class="about-stats">
                    <div class="stat-item">
                        <div class="stat-number">10,000+</div>
                        <div class="stat-label">Research Papers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">5,000+</div>
                        <div class="stat-label">Researchers</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Institutions</div>
                    </div>
                </div>
            </div>
            
            <div class="about-image">
                <div class="placeholder-image">
                    <div class="image-content">
                        <div class="image-icon">🎓</div>
                        <p>Academic Excellence</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Support Section -->
<section id="support" class="support-section">
    <div class="container">
        <h2 class="text-center mb-20">Support & Help</h2>
        <div class="support-content">
            <div class="support-grid">
                <div class="support-card">
                    <h3>Help Center</h3>
                    <p>Find answers to frequently asked questions and get help with common issues.</p>
                    <a href="#" class="support-link">Visit Help Center →</a>
                </div>
                
                <div class="support-card">
                    <h3>Documentation</h3>
                    <p>Comprehensive guides and documentation to help you get started.</p>
                    <a href="#" class="support-link">View Documentation →</a>
                </div>
                
                <div class="support-card">
                    <h3>Contact Us</h3>
                    <p>Need additional assistance? Reach out to our support team.</p>
                    <a href="#" class="support-link">Contact Support →</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Start Your Research Journey?</h2>
            <p>Join thousands of researchers who are already part of our community.</p>
            @guest
                <a href="{{ route('welcome') }}#join-us" class="btn btn-primary">Get Started Today</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Explore Dashboard</a>
            @endguest
        </div>
    </div>
</section>
@endsection

@section('styles')
<link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
@endsection