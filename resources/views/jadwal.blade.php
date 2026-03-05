@extends('layouts.user')

@section('content')
<h3 class="mb-4">Booking Calendar View</h3>

<div class="card border-0 shadow p-3">
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" required>
        </div>
        <div class="col-md-4">
            <select name="court_id" class="form-select" required>
                @foreach($courts as $court)
                    <option value="{{ $court->id }}" @selected($selectedCourtId == $court->id)>{{ $court->nama_lapangan }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Tampilkan</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 200px;">Jam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @for($jam = 8; $jam <= 22; $jam++)
                @php
                    $jamMulai = str_pad($jam, 2, '0', STR_PAD_LEFT).':00:00';
                    $jamSelesai = str_pad($jam + 1, 2, '0', STR_PAD_LEFT).':00:00';
                    $terisi = $bookings->first(fn ($b) => $b->jam_mulai < $jamSelesai && $b->jam_selesai > $jamMulai);
                @endphp
                <tr>
                    <td>{{ substr($jamMulai, 0, 5) }} - {{ substr($jamSelesai, 0, 5) }}</td>
                    <td>
                        @if($terisi)
                            <span class="badge bg-danger">Booked</span>
                        @else
                            <span class="badge bg-success">Available</span>
                        @endif
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection
