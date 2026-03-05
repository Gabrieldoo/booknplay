@extends('layouts.user')

@section('content')
<h3 class="mb-4">Dashboard User</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow p-3 bg-dark text-white">
            <h5 class="mb-3">Riwayat Booking Saya</h5>

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-3">
                    <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm">
                </div>
                <div class="col-md-3">
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
                        <option value="total_harga" @selected($sortBy === 'total_harga')>Total</option>
                        <option value="status" @selected($sortBy === 'status')>Status</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_dir" class="form-select form-select-sm">
                        <option value="desc" @selected($sortDir === 'desc')>Desc</option>
                        <option value="asc" @selected($sortDir === 'asc')>Asc</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari booking" class="form-control form-control-sm">
                </div>
                <div class="col-12">
                    <button class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route('userdashboard') }}" class="btn btn-sm btn-outline-light">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle mb-2">
                    <thead>
                        <tr>
                            <th>ID</th>
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
                            <td>{{ $booking->court->nama_lapangan ?? '-' }}</td>
                            <td>{{ $booking->tanggal->format('d-m-Y') }}</td>
                            <td>{{ substr($booking->jam_mulai, 0, 5) }} - {{ substr($booking->jam_selesai, 0, 5) }}</td>
                            <td>
                                <span class="badge bg-secondary text-uppercase">{{ $booking->status }}</span>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark text-uppercase">{{ $booking->payment_status }}</span>
                            </td>
                            <td>Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    @if($booking->bukti_pembayaran)
                                        <a href="{{ asset('storage/'.$booking->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-light">Bukti</a>
                                    @endif

                                    @if(!in_array($booking->status, ['dibatalkan', 'ditolak', 'selesai']))
                                        <form method="POST" action="{{ route('booking.cancel', $booking) }}" onsubmit="return confirm('Batalkan booking ini?')">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                        </form>
                                    @endif

                                    @if(!$booking->review && $booking->tanggal->isPast())
                                        <button class="btn btn-sm btn-outline-warning" data-bs-toggle="collapse" data-bs-target="#review-{{ $booking->id }}">Review</button>
                                    @endif

                                    @if($booking->payment_status === 'unpaid')
                                        <form method="POST" action="{{ route('payments.initiate', $booking) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success">Bayar</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if(!$booking->review && $booking->tanggal->isPast())
                            <tr class="collapse" id="review-{{ $booking->id }}">
                                <td colspan="8">
                                    <form method="POST" action="{{ route('booking.review', $booking) }}" class="row g-2">
                                        @csrf
                                        <div class="col-md-3">
                                            <select name="rating" class="form-select form-select-sm" required>
                                                <option value="">Rating</option>
                                                <option value="5">5 &#9733;</option>
                                                <option value="4">4 &#9733;</option>
                                                <option value="3">3 &#9733;</option>
                                                <option value="2">2 &#9733;</option>
                                                <option value="1">1 &#9733;</option>
                                            </select>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" name="comment" class="form-control form-control-sm" placeholder="Komentar review...">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-sm btn-warning w-100">Kirim</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada booking.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $bookings->links() }}
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow p-3 h-100">
            <h5 class="mb-3">Notifikasi</h5>
            <div style="max-height: 330px; overflow:auto;">
                @forelse($notifications as $notification)
                    <div class="border rounded p-2 mb-2 {{ $notification->read_at ? 'bg-light' : 'bg-warning-subtle' }}">
                        <div class="small">{{ $notification->data['message'] ?? '-' }}</div>
                        <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                        @if(!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="mt-1">
                                @csrf
                                <button class="btn btn-sm btn-outline-dark">Tandai Dibaca</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="text-muted">Belum ada notifikasi.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card p-3 bg-dark text-white border-0 shadow">
    <h4 class="mb-3">Kalender Jadwal Hari {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</h4>

    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control">
        </div>
        <div class="col-md-3">
            <select name="court_id" class="form-select">
                @foreach($courts as $court)
                    <option value="{{ $court->id }}" @selected($selectedCourtId == $court->id)>{{ $court->nama_lapangan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-light w-100">Lihat</button>
        </div>
    </form>

    <div class="row">
        @for($jam = 8; $jam <= 22; $jam++)
            @php
                $jamMulai = str_pad($jam, 2, '0', STR_PAD_LEFT).':00:00';
                $jamSelesai = str_pad($jam + 1, 2, '0', STR_PAD_LEFT).':00:00';
                $terisi = $jadwal->first(fn ($b) => $b->jam_mulai < $jamSelesai && $b->jam_selesai > $jamMulai);
            @endphp

            <div class="col-6 col-md-3 col-lg-2 mb-3">
                @if($terisi)
                    <div class="card bg-danger text-white text-center p-3">
                        <strong>{{ substr($jamMulai, 0, 5) }}</strong>
                        <div>Booked</div>
                    </div>
                @else
                    <a href="{{ route('booking', ['tanggal' => $tanggal, 'jam_mulai' => substr($jamMulai, 0, 5), 'court_id' => $selectedCourtId]) }}" class="text-decoration-none">
                        <div class="card bg-success text-white text-center p-3">
                            <strong>{{ substr($jamMulai, 0, 5) }}</strong>
                            <div>Available</div>
                        </div>
                    </a>
                @endif
            </div>
        @endfor
    </div>
</div>
@endsection
