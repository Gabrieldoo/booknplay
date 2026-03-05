@extends('layouts.user')

@section('content')

<h3 class="mb-4">Booking Lapangan Badminton</h3>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- Informasi Transfer --}}
<div class="card mb-4 border-0 shadow">
    <div class="card-body bg-dark text-white rounded">
        <h5 class="mb-3">Informasi Pembayaran</h5>

        <div class="row g-3">
            <div class="col-md-4">
                <strong>Bank</strong>
                <p class="mb-1">BCA</p>
            </div>
            <div class="col-md-4">
                <strong>No Rekening</strong>
                <p class="mb-1">1234567890</p>
            </div>
            <div class="col-md-4">
                <strong>Atas Nama</strong>
                <p class="mb-1">BookNPlay</p>
            </div>
        </div>

        <hr class="border-light">

        <p class="small mb-0">
            Silakan transfer sesuai total harga.
            Setelah transfer, upload bukti pembayaran di bawah ini.
        </p>
    </div>
</div>


<form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="card p-3 p-md-4 shadow border-0">

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date"
                   name="tanggal"
                   value="{{ $tanggal ?? '' }}"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Jam Mulai</label>
            <input type="time"
                   name="jam_mulai"
                   value="{{ $jam_mulai ?? '' }}"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Durasi (Jam)</label>
            <select name="durasi" class="form-control" required>
                <option value="1">1 Jam</option>
                <option value="2">2 Jam</option>
                <option value="3">3 Jam</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Total Harga</label>
            <input type="text" id="totalHarga" class="form-control bg-light" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Bukti Pembayaran</label>
            <input type="file"
                   name="bukti_pembayaran"
                   class="form-control"
                   accept=".jpg,.jpeg,.png"
                   required>
            <small class="text-muted">
                Format: JPG, PNG. Maksimal 2MB.
            </small>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Konfirmasi Booking
        </button>

    </div>

</form>


<script>
document.addEventListener("DOMContentLoaded", function () {

    const hargaPerJam = 50000;
    const durasiSelect = document.querySelector('select[name="durasi"]');
    const totalInput = document.getElementById('totalHarga');

    function hitungTotal() {
        let durasi = parseInt(durasiSelect.value);
        let total = durasi * hargaPerJam;
        totalInput.value = "Rp " + total.toLocaleString('id-ID');
    }

    durasiSelect.addEventListener("change", hitungTotal);

    hitungTotal();
});
</script>

@endsection
