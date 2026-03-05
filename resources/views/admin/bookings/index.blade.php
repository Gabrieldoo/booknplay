@extends('layouts.dashboard')

@section('content')
<h3 class="mb-4">Manage Bookings</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card border-0 shadow-sm p-3 mb-3">
    <form method="GET" class="row g-2">
        <div class="col-md-2">
            <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
            <select name="field" class="form-select form-select-sm">
                <option value="">Semua Lapangan</option>
                @foreach($courts as $court)
                    <option value="{{ $court->id }}" @selected(request('field') == $court->id)>{{ $court->nama_lapangan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort_by" class="form-select form-select-sm">
                <option value="tanggal" @selected($sortBy === 'tanggal')>Tanggal</option>
                <option value="jam_mulai" @selected($sortBy === 'jam_mulai')>Jam</option>
                <option value="status" @selected($sortBy === 'status')>Status</option>
                <option value="total_harga" @selected($sortBy === 'total_harga')>Total</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="sort_dir" class="form-select form-select-sm">
                <option value="desc" @selected($sortDir === 'desc')>Desc</option>
                <option value="asc" @selected($sortDir === 'asc')>Asc</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari booking/user/lapangan" class="form-control form-control-sm">
        </div>
        <div class="col-md-1">
            <button class="btn btn-sm btn-primary w-100">Filter</button>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm p-2">
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Lapangan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($bookings as $booking)
                <tr>
                    <td>#{{ $booking->id }}</td>
                    <td>{{ $booking->user->name ?? '-' }}</td>
                    <td>{{ $booking->court->nama_lapangan ?? '-' }}</td>
                    <td>{{ $booking->tanggal->format('d-m-Y') }}</td>
                    <td>{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</td>
                    <td><span class="badge bg-secondary text-uppercase">{{ $booking->status }}</span></td>
                    <td><span class="badge bg-info text-dark text-uppercase">{{ $booking->payment_status }}</span></td>
                    <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="d-flex gap-1">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm" required>
                                @foreach(['pending','diterima','ditolak','dibatalkan','selesai'] as $status)
                                    <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-dark">Update</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Data booking tidak ditemukan.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-2">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
