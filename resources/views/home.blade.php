@extends('layout')

@section('content')
<section class="hero-wrap py-5">
    <div class="container py-4 py-md-5">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-12 col-lg-7">
                <div class="brand-chip d-inline-flex align-items-center gap-2 mb-3">
                    <img src="/logo.png" height="36" alt="BookNPlay logo">
                    <span>BookNPlay</span>
                </div>

                <h1 class="display-5 fw-bold lh-sm mb-3">
                    Booking Lapangan Badminton Lebih Nyaman
                </h1>

                <p class="lead text-light-emphasis mb-4 hero-copy">
                    Cek jadwal real-time, pesan lapangan dalam hitungan detik, dan gunakan chatbot untuk melihat slot kosong tanpa ribet.
                </p>

                <div class="d-flex flex-column flex-sm-row gap-3">
                    @auth
                        <a href="/booking" class="btn btn-primary btn-lg px-4">Booking Sekarang</a>
                        <a href="/userdashboard" class="btn btn-outline-light btn-lg px-4">Buka Dashboard</a>
                    @else
                        <a href="/register" class="btn btn-primary btn-lg px-4">Mulai Sekarang</a>
                        <a href="/login" class="btn btn-outline-light btn-lg px-4">Login</a>
                    @endauth
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="hero-panel p-4 p-md-5">
                    <h5 class="fw-semibold mb-3">Kenapa nyaman dipakai?</h5>
                    <div class="d-grid gap-3">
                        <div class="feature-box">
                            <h6 class="mb-1">Jadwal cepat dibaca</h6>
                            <p class="mb-0 small">Slot kosong dan terisi langsung terlihat.</p>
                        </div>
                        <div class="feature-box">
                            <h6 class="mb-1">Flow booking singkat</h6>
                            <p class="mb-0 small">Isi data, upload bukti, selesai dalam satu halaman.</p>
                        </div>
                        <div class="feature-box">
                            <h6 class="mb-1">Ramah mobile</h6>
                            <p class="mb-0 small">Layout tetap enak dipakai di HP dan desktop.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hero-wrap{
    position:relative;
    min-height:calc(100vh - 78px);
}
.hero-wrap::before{
    content:"";
    position:absolute;
    inset:0;
    background:url('/badminton.png') center/cover no-repeat;
    opacity:.20;
    filter:saturate(.95);
    pointer-events:none;
}
.hero-wrap > .container{
    position:relative;
    z-index:1;
}
.brand-chip{
    border:1px solid rgba(255,255,255,.22);
    border-radius:999px;
    padding:8px 14px;
    background:rgba(255,255,255,.08);
}
.hero-copy{
    max-width:620px;
}
.hero-panel{
    border:1px solid rgba(255,255,255,.18);
    border-radius:20px;
    background:rgba(7,23,40,.62);
    backdrop-filter:blur(8px);
}
.feature-box{
    border:1px solid rgba(255,255,255,.12);
    border-radius:14px;
    background:rgba(255,255,255,.06);
    padding:14px;
}
@media (max-width: 576px){
    .hero-wrap{
        min-height:auto;
    }
}
</style>
@endsection
