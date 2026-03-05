@extends('layout')

@section('content')
<section class="container py-5">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h3 class="mb-3">Contact BookNPlay</h3>
                    <p class="text-muted">Hubungi admin untuk pertanyaan booking, kerja sama, atau kendala pembayaran.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><strong>Email:</strong> admin@booknplay.test</li>
                        <li class="mb-2"><strong>Phone:</strong> +62 812-0000-0000</li>
                        <li><strong>Address:</strong> Jakarta, Indonesia</li>
                    </ul>
                    <a class="btn btn-success mt-3" href="https://wa.me/6281234567890" target="_blank" rel="noopener">Contact Admin via WhatsApp</a>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <h5 class="mb-3">Location</h5>
                    <div class="ratio ratio-16x9 rounded overflow-hidden">
                        <iframe src="https://www.google.com/maps?q=-6.2088,106.8456&hl=en&z=14&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
