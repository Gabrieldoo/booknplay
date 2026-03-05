<footer class="site-footer mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <h6 class="text-white">About Us</h6>
                <p class="text-light small mb-0">BookNPlay adalah platform booking lapangan olahraga yang memudahkan pemesanan jadwal secara cepat dan transparan.</p>
            </div>
            <div class="col-md-6 col-lg-3">
                <h6 class="text-white">Contact</h6>
                <ul class="list-unstyled small text-light mb-0">
                    <li>Email: admin@booknplay.test</li>
                    <li>Phone: +62 812-0000-0000</li>
                    <li><a href="{{ route('contact') }}" class="link-light">Contact Page</a></li>
                </ul>
            </div>
            <div class="col-md-6 col-lg-3">
                <h6 class="text-white">Social Media</h6>
                <ul class="list-unstyled small mb-0">
                    <li><a href="#" class="link-light">Instagram</a></li>
                    <li><a href="#" class="link-light">Facebook</a></li>
                    <li><a href="#" class="link-light">YouTube</a></li>
                </ul>
            </div>
            <div class="col-md-6 col-lg-3">
                <h6 class="text-white">Location Map</h6>
                <div class="ratio ratio-4x3 rounded overflow-hidden border border-secondary-subtle">
                    <iframe src="https://www.google.com/maps?q=-6.2088,106.8456&hl=en&z=14&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <hr class="border-secondary my-4">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 small text-light">
            <span>&copy; {{ date('Y') }} BookNPlay. All rights reserved.</span>
            <span>
                <a href="#" class="link-light text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="link-light text-decoration-none">Terms & Conditions</a>
            </span>
        </div>
    </div>
</footer>
