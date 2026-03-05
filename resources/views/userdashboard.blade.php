@extends('layouts.user')

@section('content')

<h3 class="mb-4">Riwayat Booking Saya</h3>

<div class="card p-3 bg-dark text-white border-0 shadow">
    <div class="table-responsive">
    <table class="table table-dark table-striped align-middle mb-0">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
                <th>Bukti</th>
                <th>Total</th>
                
            </tr>
        </thead>
        <tbody>
@forelse($bookings as $booking)
    <tr>
        <td>
            {{ \Carbon\Carbon::parse($booking->tanggal)->format('d-m-Y') }}
        </td>

        <td>
            {{ substr($booking->jam_mulai,0,5) }}
            -
            {{ substr($booking->jam_selesai,0,5) }}
        </td>

        <td>
            @if($booking->status == 'pending')
                <span class="badge bg-warning text-dark">Pending</span>
            @elseif($booking->status == 'diterima')
                <span class="badge bg-success">Diterima</span>
            @elseif($booking->status == 'ditolak')
                <span class="badge bg-danger">Ditolak</span>
            @else
                <span class="badge bg-secondary">Unknown</span>
            @endif
        </td>

        <td>
            @if($booking->bukti_pembayaran)
                <a href="{{ asset('storage/'.$booking->bukti_pembayaran) }}"
                   target="_blank"
                   class="btn btn-sm btn-outline-light">
                   Lihat
                </a>
            @else
                -
            @endif
        </td>

        <td>
            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">
            Belum ada booking
        </td>
    </tr>
@endforelse
</tbody>
    </table>
    </div>

    <hr class="my-5">

<h4 class="mb-3">Jadwal Hari {{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</h4>

<form method="GET" class="mb-4">
    <input type="date" name="tanggal"
           value="{{ $tanggal }}"
           class="form-control"
           style="max-width: 280px;"
           onchange="this.form.submit()">
</form>

<div class="row">

@for($jam = 8; $jam <= 22; $jam++)

@php
    $jamMulai = str_pad($jam,2,'0',STR_PAD_LEFT).":00:00";
    $jamSelesai = str_pad($jam+1,2,'0',STR_PAD_LEFT).":00:00";

    $terisi = $jadwal->first(function($b) use ($jamMulai, $jamSelesai) {
        return $b->jam_mulai < $jamSelesai && $b->jam_selesai > $jamMulai;
    });
@endphp

<div class="col-6 col-md-4 col-lg-2 mb-3">
    @if($terisi)
        <div class="card bg-danger text-white text-center p-3">
            <strong>{{ substr($jamMulai,0,5) }}</strong>
            <div>Terisi</div>
        </div>
    @else
        <a href="{{ route('booking', ['tanggal'=>$tanggal, 'jam_mulai'=>substr($jamMulai,0,5)]) }}"
           class="text-decoration-none">
            <div class="card bg-success text-white text-center p-3">
                <strong>{{ substr($jamMulai,0,5) }}</strong>
                <div>Kosong</div>
            </div>
        </a>
    @endif
</div>

@endfor

</div>
</div>

@endsection
