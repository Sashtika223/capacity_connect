<footer class="footer-corporate py-5 mt-auto text-white" style="background-color: #0A192F !important; border-top: 3px solid #C5A059;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="logo-icon bg-warning text-dark p-2 rounded-3 d-inline-flex">
                        <i class="bi bi-award-fill fs-5"></i>
                    </div>
                    <h5 class="mb-0 text-white font-weight-bold">CAPACITY <span class="text-warning">CONNECT</span></h5>
                </div>
                <p class="mb-3 text-muted">Centralized platform for workforce learning, disaster response competency development, knowledge preservation, and organizational capacity building.</p>
                <div class="d-flex gap-2">
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-white mb-3 text-uppercase font-weight-bold">Quick Links</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ url('/') }}">Home</a></li>
                    <li class="mb-2"><a href="#about">About Us</a></li>
                    <li class="mb-2"><a href="{{ route('trainee.courses') }}">Course Catalog</a></li>
                    <li class="mb-2"><a href="{{ route('subject-matching.index') }}">Subject Matchmaker</a></li>
                    <li class="mb-2"><a href="{{ route('contact.index') }}">Contact Us</a></li>
                    <li class="mb-2"><a href="{{ route('register') }}">Portal Registration</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                <h6 class="text-white mb-3 text-uppercase font-weight-bold">Enterprise System</h6>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="{{ route('login') }}">Competency Risk Radar</a></li>
                    <li class="mb-2"><a href="{{ route('login') }}">Hidden Expert Discovery</a></li>
                    <li class="mb-2"><a href="{{ route('login') }}">Knowledge Graph</a></li>
                    <li class="mb-2"><a href="{{ route('login') }}">Offline PWA Field Sync</a></li>
                    <li class="mb-2"><a href="{{ route('verify.certificate', 'CC-CERT-2026-8841') }}">Certificate Verification</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-12">
                <h6 class="text-white mb-3 text-uppercase font-weight-bold">Institutional Updates</h6>
                <p class="text-muted mb-3 small">Subscribe for official updates, emergency response alerts, and training announcements.</p>
                <form action="#" method="POST" onsubmit="event.preventDefault(); alert('Thank you for subscribing to Capacity Connect institutional updates.');">
                    <div class="input-group">
                        <input type="email" class="form-control form-control-sm border-0" placeholder="Official Email Address" required>
                        <button class="btn btn-cc-gold btn-sm" type="submit">Join</button>
                    </div>
                </form>
            </div>
        </div>

        <hr class="my-4 border-secondary opacity-25">
        
        <div class="row align-items-center text-center text-md-start">
            <div class="col-md-6">
                <p class="mb-0 text-muted small">&copy; {{ date('Y') }} Capacity Connect LMS. All rights reserved. Official Government & Enterprise Portal.</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <a href="#" class="me-3 small">Privacy Policy</a>
                <a href="#" class="me-3 small">Terms of Service</a>
                <a href="#" class="small">Security Controls</a>
            </div>
        </div>
    </div>
</footer>
