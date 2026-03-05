<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard - BookNPlay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            margin:0;
            color:#f7fbff;
            background:#081426;
            font-family:Arial,sans-serif;
        }
        .side-panel{
            background:#0f1c31;
            border-right:1px solid rgba(255,255,255,.08);
        }
        .menu-link{
            display:block;
            color:#bdcde0;
            padding:10px 12px;
            border-radius:10px;
            text-decoration:none;
            margin-bottom:6px;
            font-weight:600;
        }
        .menu-link:hover,
        .menu-link.active{
            background:#1e3354;
            color:#fff;
        }
        .main-shell{
            min-height:100vh;
        }
        .topbar{
            background:#0f1c31;
            border-bottom:1px solid rgba(255,255,255,.08);
        }
        @media (min-width: 992px){
            .desktop-sidebar{
                width:250px;
                min-height:100vh;
                position:sticky;
                top:0;
            }
        }
    </style>
</head>
<body>
<div class="d-lg-none topbar p-3 d-flex justify-content-between align-items-center">
    <div class="fw-semibold">BookNPlay</div>
    <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">Menu</button>
</div>

<div class="offcanvas offcanvas-start text-bg-dark d-lg-none" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title">BookNPlay</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <a class="menu-link {{ request()->routeIs('userdashboard') ? 'active' : '' }}" href="{{ route('userdashboard') }}">Dashboard</a>
        <a class="menu-link {{ request()->routeIs('booking*') ? 'active' : '' }}" href="{{ route('booking') }}">Booking</a>
        <a class="menu-link {{ request()->routeIs('booking.calendar') ? 'active' : '' }}" href="{{ route('booking.calendar') }}">Calendar</a>
        <a class="menu-link {{ request()->routeIs('chatbot*') ? 'active' : '' }}" href="{{ route('chatbot') }}">Chatbot</a>
        <a class="menu-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>
        <form action="/logout" method="POST" class="mt-4">
            @csrf
            <button class="btn btn-danger w-100">Logout</button>
        </form>
    </div>
</div>

<div class="container-fluid px-0 main-shell">
    <div class="row g-0">
        <aside class="col-lg-auto d-none d-lg-block desktop-sidebar side-panel p-3">
            <div class="mb-4 d-flex align-items-center gap-2 fw-bold">
                <img src="/logo.png" height="34" alt="BookNPlay logo">
                <span>BookNPlay</span>
            </div>

            <a class="menu-link {{ request()->routeIs('userdashboard') ? 'active' : '' }}" href="{{ route('userdashboard') }}">Dashboard</a>
            <a class="menu-link {{ request()->routeIs('booking*') ? 'active' : '' }}" href="{{ route('booking') }}">Booking</a>
            <a class="menu-link {{ request()->routeIs('booking.calendar') ? 'active' : '' }}" href="{{ route('booking.calendar') }}">Calendar</a>
            <a class="menu-link {{ request()->routeIs('chatbot*') ? 'active' : '' }}" href="{{ route('chatbot') }}">Chatbot</a>
            <a class="menu-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a>

            <form action="/logout" method="POST" class="mt-4">
                @csrf
                <button class="btn btn-danger w-100">Logout</button>
            </form>
        </aside>

        <main class="col p-3 p-md-4 p-lg-5">
            <div class="d-none d-lg-flex justify-content-between align-items-center mb-4">
                <span class="text-secondary">User Panel</span>
                <span>{{ auth()->user()->name }}</span>
            </div>
            @yield('content')
        </main>
    </div>
</div>
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
