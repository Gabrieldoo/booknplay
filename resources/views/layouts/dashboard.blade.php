<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - BookNPlay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            margin:0;
            color:#f8fbff;
            background:#0a1628;
            font-family:Arial,sans-serif;
        }
        .admin-nav{
            background:#0f1e34;
            border-bottom:1px solid rgba(255,255,255,.10);
        }
        .admin-nav .nav-link{
            color:#c3d2e5;
            font-weight:600;
        }
        .admin-nav .nav-link:hover{
            color:#fff;
        }
        .card-dark{
            background:#14243d;
            border:none;
            border-radius:14px;
            padding:20px;
        }
        .stat-number{
            font-size:28px;
            font-weight:700;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg admin-nav">
    <div class="container-fluid px-3 px-md-4">
        <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="/dashboard">
            <img src="/logo.png" height="34" alt="BookNPlay logo">
            BookNPlay
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse mt-3 mt-lg-0" id="adminNav">
            <div class="navbar-nav me-auto">
                <a href="/dashboard" class="nav-link">Dashboard</a>
                <a href="{{ route('admin.bookings.index') }}" class="nav-link">Manage Booking</a>
                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
            </div>
            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-2">
                <span class="text-secondary">Welcome, {{ auth()->user()->name ?? 'Admin' }}</span>
                <form action="/logout" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<main class="container-fluid px-3 px-md-4 py-4">
    @yield('content')
</main>
@include('partials.footer')
@include('partials.whatsapp-button')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .site-footer{
        background:#07101f;
        border-top:1px solid rgba(255,255,255,.12);
    }
    .wa-float{
        position:fixed;
        right:20px;
        bottom:20px;
        z-index:1040;
        background:#1fa855;
        color:#fff;
        text-decoration:none;
        font-weight:600;
        border-radius:999px;
        padding:10px 16px;
        box-shadow:0 10px 30px rgba(0,0,0,.25);
    }
</style>
</body>
</html>
