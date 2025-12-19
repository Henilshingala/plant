<?php
require_once 'config.php';
require_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero-section bg-success text-white py-5 mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold">About Plant Shop</h1>
                <p class="lead">Bringing nature's beauty into your home since 2024</p>
            </div>
        </div>
    </div>
</section>

<!-- Mission Section -->
<section class="mission-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-4">Our Mission</h2>
                <p class="lead">To provide high-quality plants and exceptional service to plant enthusiasts, helping them create beautiful and healthy indoor environments.</p>
                <p>We believe that everyone deserves to experience the joy and benefits of having plants in their lives. Our carefully curated collection of plants, combined with expert advice and support, makes it easy for anyone to start their plant journey.</p>
            </div>
            <div class="col-md-6">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-leaf fa-3x text-success mb-3"></i>
                                <h5>Quality Plants</h5>
                                <p>Carefully selected and nurtured</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-truck fa-3x text-success mb-3"></i>
                                <h5>Fast Delivery</h5>
                                <p>Safe and quick shipping</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-headset fa-3x text-success mb-3"></i>
                                <h5>Expert Support</h5>
                                <p>24/7 customer service</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                                <h5>Secure Payment</h5>
                                <p>Safe transactions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="team-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5">Our Team</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="team-image-wrapper">
                        <img src="assets/images/team/team1.jpg" class="card-img-top" alt="Team Member">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Postiwala Vatsal</h5>
                        <p class="card-text text-muted">Founder & CEO</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="team-image-wrapper">
                        <img src="assets/images/team/team2.jpg" class="card-img-top" alt="Team Member">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Shingala Henil</h5>
                        <p class="card-text text-muted">Plant Expert</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="team-image-wrapper">
                        <img src="assets/images/team/team3.jpg" class="card-img-top" alt="Team Member">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">Surti Deep</h5>
                        <p class="card-text text-muted">Customer Support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.team-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 100%; /* Creates a 1:1 aspect ratio */
    overflow: hidden;
}

.team-image-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures the image covers the area without distortion */
}
</style>

<!-- Values Section -->
<section class="values-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Our Values</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-heart fa-3x text-success mb-3"></i>
                    <h5>Passion</h5>
                    <p>We love plants and share our passion with you</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-star fa-3x text-success mb-3"></i>
                    <h5>Quality</h5>
                    <p>Only the best plants for our customers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                    <h5>Trust</h5>
                    <p>Building lasting relationships with our customers</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-globe fa-3x text-success mb-3"></i>
                    <h5>Sustainability</h5>
                    <p>Eco-friendly practices in everything we do</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>

 