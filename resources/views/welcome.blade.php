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
            
            <!-- Join as Reviewer -->
            <div class="join-card reviewer-card">
                <div class="join-icon">📋</div>
                <h3>Join as a Reviewer</h3>
                <p>Help maintain research quality through peer review.</p>
                
                <div class="benefits-list">
                    <div class="benefit-item">✓ Review research papers</div>
                    <div class="benefit-item">✓ Shape research standards</div>
                    <div class="benefit-item">✓ Academic recognition</div>
                </div>
                
                <a href="{{ route('registration') }}?role=reviewer" class="btn btn-outline">Join as Reviewer</a>
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
                <p>Research Portal is a comprehensive platform designed to facilitate academic research, collaboration, and knowledge sharing. We connect researchers, reviewers, and academic institutions worldwide to advance scientific discovery and innovation.</p>
                
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
                        <div class="stat-number">2,500+</div>
                        <div class="stat-label">Reviewers</div>
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

<!-- Call to Action Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Start Your Research Journey?</h2>
            <p>Join thousands of researchers and reviewers who are already part of our community.</p>
            @guest
                <a href="#join-us" class="btn btn-primary">Get Started Today</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-primary">Explore Dashboard</a>
            @endguest
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
/* Prevent horizontal overflow */
body {
    overflow-x: hidden;
    max-width: 100vw;
}

/* Hero Section */
.hero-section {
    background-color: #ffffff;
    padding: 60px 0 80px;
    min-height: 70vh;
    display: flex;
    align-items: center;
    overflow-x: hidden;
    width: 100%;
    max-width: 100%;
}

.hero-content {
    display: flex;
    align-items: center;
    gap: 60px;
    flex-wrap: wrap;
    width: 100%;
    max-width: 100%;
}

.hero-text {
    flex: 1;
    min-width: 0;
    max-width: 100%;
}

.hero-badge {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
}

.badge-line {
    width: 40px;
    height: 3px;
    background-color: #4a7c2a;
    margin-right: 15px;
}

.badge-text {
    color: #666666;
    font-size: 14px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.hero-content h1 {
    font-size: 48px;
    line-height: 1.2;
    color: #333333;
    margin-bottom: 40px;
    font-weight: 600;
    word-wrap: break-word;
    max-width: 100%;
}

.search-container {
    margin-bottom: 40px;
}

.search-form {
    display: flex;
    max-width: 100%;
    width: 100%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 5px;
    overflow: hidden;
}

.search-input {
    flex: 1;
    padding: 15px 20px;
    border: 2px solid #e0e0e0;
    border-right: none;
    font-size: 16px;
    outline: none;
    background-color: #ffffff;
}

.search-input:focus {
    border-color: #2d5016;
}

.search-btn {
    background-color: #2d5016;
    color: #ffffff;
    border: none;
    padding: 15px 25px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    letter-spacing: 1px;
}

.search-btn:hover {
    background-color: #1a3009;
}

.hero-visual {
    flex: 1;
    min-width: 0;
    max-width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.world-map-image {
    position: relative;
    width: 100%;
    max-width: 100%;
    height: auto;
}

.map-image {
    width: 100%;
    height: auto;
    max-width: 100%;
    object-fit: contain;
}

/* Services Section */
.services-section {
    padding: 80px 0;
    background-color: #f8f9fa;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

.services-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
}

.service-card {
    background: #ffffff;
    padding: 40px 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    flex: 1;
    min-width: 250px;
    max-width: 300px;
}

.service-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.service-card h3 {
    color: #2d5016;
    margin-bottom: 15px;
    font-size: 24px;
}

.service-card p {
    color: #666666;
    margin-bottom: 20px;
    line-height: 1.6;
}

.service-link {
    color: #2d5016;
    font-weight: bold;
    text-decoration: none;
}

.service-link:hover {
    color: #4a7c2a;
}

/* Join Section */
.join-section {
    padding: 80px 0;
    background-color: #ffffff;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

.join-options {
    display: flex;
    gap: 25px;
    justify-content: center;
    flex-wrap: wrap;
}

.join-card {
    background: #ffffff;
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 25px 20px;
    text-align: center;
    flex: 1;
    min-width: 280px;
    max-width: 320px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.join-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.researcher-card {
    border-color: #2d5016;
}

.reviewer-card {
    border-color: #4a7c2a;
}

.reader-card {
    border-color: #6c757d;
}

.join-icon {
    font-size: 48px;
    margin-bottom: 15px;
}

.join-card h3 {
    color: #2d5016;
    margin-bottom: 10px;
    font-size: 22px;
}

.join-card p {
    color: #666666;
    margin-bottom: 20px;
    line-height: 1.5;
    font-size: 14px;
}

.benefits-list {
    text-align: left;
    margin-bottom: 20px;
}

.benefit-item {
    color: #333333;
    margin-bottom: 8px;
    padding-left: 8px;
    font-size: 13px;
}

/* About Section */
.about-section {
    padding: 80px 0;
    background-color: #f8f9fa;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

.about-content {
    display: flex;
    gap: 60px;
    align-items: center;
    flex-wrap: wrap;
    width: 100%;
    max-width: 100%;
}

.about-text {
    flex: 1;
    min-width: 0;
    max-width: 100%;
}

.about-text h2 {
    color: #2d5016;
    margin-bottom: 20px;
    font-size: 36px;
}

.about-text p {
    color: #666666;
    margin-bottom: 40px;
    line-height: 1.6;
    font-size: 18px;
}

.about-stats {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 32px;
    font-weight: bold;
    color: #2d5016;
    margin-bottom: 5px;
}

.stat-label {
    color: #666666;
    font-size: 14px;
}

.about-image {
    flex: 1;
    min-width: 0;
    max-width: 100%;
}

.placeholder-image {
    background-color: #2d5016;
    border-radius: 15px;
    height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
}

.image-content {
    text-align: center;
}

.image-icon {
    font-size: 64px;
    margin-bottom: 15px;
}

/* CTA Section */
.cta-section {
    background-color: #2d5016;
    color: #ffffff;
    padding: 60px 0;
    text-align: center;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
}

.cta-content h2 {
    color: #ffffff;
    margin-bottom: 15px;
    font-size: 36px;
}

.cta-content p {
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 30px;
    font-size: 18px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .hero-content {
        gap: 40px;
    }
    
    .hero-content h1 {
        font-size: 42px;
    }
}

@media (max-width: 992px) {
    .hero-content {
        flex-direction: column;
        gap: 40px;
        text-align: center;
    }
    
    .hero-text {
        width: 100%;
    }
    
    .hero-visual {
        width: 100%;
    }
    
    .world-map-image {
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 40px 0 60px;
    }
    
    .hero-content h1 {
        font-size: 32px;
    }
    
    .hero-content {
        flex-direction: column;
        gap: 30px;
        text-align: center;
    }
    
    .hero-text {
        width: 100%;
        padding: 0 15px;
    }
    
    .search-form {
        flex-direction: column;
        max-width: 100%;
    }
    
    .search-input {
        border-right: 2px solid #e0e0e0;
        border-bottom: none;
        border-radius: 5px 5px 0 0;
    }
    
    .search-btn {
        border-top: none;
        border-radius: 0 0 5px 5px;
    }
    
    .hero-visual {
        width: 100%;
        padding: 0 15px;
    }
    
    .world-map-image {
        max-width: 100%;
    }
    
    .map-image {
        max-width: 100%;
    }
    
    .hero-content p {
        font-size: 18px;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    .services-grid {
        flex-direction: column;
        align-items: center;
    }
    
    .join-options {
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }
    
    .join-card {
        min-width: 100%;
        max-width: 100%;
        padding: 20px 15px;
    }
    
    .join-icon {
        font-size: 40px;
        margin-bottom: 12px;
    }
    
    .join-card h3 {
        font-size: 20px;
    }
    
    .join-card p {
        font-size: 13px;
        margin-bottom: 15px;
    }
    
    .benefits-list {
        margin-bottom: 15px;
    }
    
    .benefit-item {
        font-size: 12px;
        margin-bottom: 6px;
    }
    
    .about-content {
        flex-direction: column;
        text-align: center;
    }
    
    .about-stats {
        justify-content: center;
    }
    
    .cta-content h2 {
        font-size: 28px;
    }
}
</style>
@endsection