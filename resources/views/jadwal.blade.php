@extends('layouts.user')

@section('content')

<h3 class="mb-4">Kalender Jadwal Badminton</h3>

<form method="GET" class="mb-4">
    <input type="date" name="tanggal"
           value="{{ $tanggal }}"
           class="form-control w-25 d-inline"
           onchange="this.form.submit()">
</form>

<div class="row">

@for($jam = 8; $jam <= 22; $jam++)

@php
    $jamMulai = str_pad($jam,2,'0',STR_PAD_LEFT).":00:00";
    $jamSelesai = str_pad($jam+1,2,'0',STR_PAD_LEFT).":00:00";

    $terisi = $bookings->first(function($b) use ($jamMulai, $jamSelesai) {
        return $b->jam_mulai < $jamSelesai && $b->jam_selesai > $jamMulai;
    });
@endphp

<div class="col-md-2 mb-3">
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

@endsection