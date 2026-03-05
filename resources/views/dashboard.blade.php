@extends('layouts.dashboard')

@section('content')

<h2 class="mb-4">Dashboard Admin</h2>

<div class="row g-3">

    <div class="col-12 col-md-6 col-xl-4">
        <div class="card-dark">
            <div>Total Booking</div>
            <div class="stat-number">
                {{ \App\Models\Booking::count() }}
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="card-dark">
            <div>Booking Hari Ini</div>
            <div class="stat-number">
                {{ \App\Models\Booking::whereDate('tanggal', today())->count() }}
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-4">
        <div class="card-dark">
            <div>Status Sistem</div>
            <div class="stat-number text-success">
                Aktif
            </div>
        </div>
    </div>

</div>

@endsection
