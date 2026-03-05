@extends('layouts.dashboard')

@section('content')
<h2 class="mb-4">Dashboard Admin</h2>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card-dark">
            <div>Total Bookings</div>
            <div class="stat-number">{{ number_format($totalBookings) }}</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-dark">
            <div>Total Users</div>
            <div class="stat-number">{{ number_format($totalUsers) }}</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-dark">
            <div>Total Revenue</div>
            <div class="stat-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card-dark">
            <h5 class="mb-3">Bookings Per Month</h5>
            <canvas id="bookingChart" height="120"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-dark mb-3">
            <h5 class="mb-3">New Users</h5>
            <canvas id="userChart" height="150"></canvas>
        </div>
        <div class="card-dark">
            <h5 class="mb-3">Revenue Trend</h5>
            <canvas id="revenueChart" height="150"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = @json($chartLabels);

new Chart(document.getElementById('bookingChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Bookings',
            data: @json($bookingsPerMonth),
            backgroundColor: '#60a5fa'
        }]
    }
});

new Chart(document.getElementById('userChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'New Users',
            data: @json($usersPerMonth),
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34,197,94,0.2)',
            fill: true,
            tension: 0.3
        }]
    }
});

new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Revenue',
            data: @json($revenuePerMonth),
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245,158,11,0.2)',
            fill: true,
            tension: 0.3
        }]
    }
});
</script>
@endsection
