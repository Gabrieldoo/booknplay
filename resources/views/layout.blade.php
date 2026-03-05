<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BookNPlay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root{
            --bg-1:#071426;
            --bg-2:#11253c;
            --surface:rgba(255,255,255,0.08);
            --surface-border:rgba(255,255,255,0.14);
            --text:#f8fbff;
            --muted:#c7d5e6;
            --accent:#2f7df6;
        }
        body{
            min-height:100vh;
            color:var(--text);
            background:
                radial-gradient(circle at 15% 10%, rgba(47,125,246,0.20), transparent 32%),
                radial-gradient(circle at 90% 90%, rgba(56,189,248,0.16), transparent 28%),
                linear-gradient(135deg,var(--bg-1),var(--bg-2));
        }
        .site-navbar{
            background:rgba(4,14,27,0.70);
            border-bottom:1px solid rgba(255,255,255,0.10);
            backdrop-filter:blur(12px);
        }
        .site-navbar .nav-link{
            color:var(--muted);
            font-weight:600;
        }
        .site-navbar .nav-link:hover{
            color:#fff;
        }
        .btn-primary{
            background:var(--accent);
            border-color:var(--accent);
        }
        .btn-primary:hover{
            background:#256de1;
            border-color:#256de1;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg site-navbar sticky-top">
    <div class="container py-1">
        <a class="navbar-brand fw-bold d-flex align-items-center text-white" href="/">
            <img src="/logo.png" height="42" class="me-2" alt="BookNPlay logo">
            BookNPlay
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse mt-3 mt-lg-0" id="mainNav">
            <div class="ms-auto d-flex flex-column flex-lg-row gap-2 gap-lg-3 align-items-start align-items-lg-center">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('dashboard') }}" class="nav-link p-0">Dashboard</a>
                        <a href="{{ route('admin.bookings.index') }}" class="nav-link p-0">Manage Booking</a>
                    @else
                        <a href="{{ route('userdashboard') }}" class="nav-link p-0">Dashboard</a>
                        <a href="{{ route('booking') }}" class="nav-link p-0">Booking</a>
                        <a href="{{ route('chatbot') }}" class="nav-link p-0">Chatbot</a>
                    @endif
                    <a href="{{ route('contact') }}" class="nav-link p-0">Contact</a>
                    <form action="/logout" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-sm btn-danger">Logout</button>
                    </form>
                @else
                    <a href="{{ route('contact') }}" class="nav-link p-0">Contact</a>
                    <a href="/login" class="nav-link p-0">Login</a>
                    <a href="/register" class="btn btn-primary btn-sm">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@yield('content')
@include('partials.footer')
@include('partials.whatsapp-button')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .site-footer{
        background:rgba(3,10,19,0.85);
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
    .wa-float:hover{
        background:#188846;
        color:#fff;
    }
</style>
</body>
</html>
