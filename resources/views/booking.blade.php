@extends('layouts.user')

@section('content')
<h3 class="mb-4">Booking Lapangan</h3>

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card p-3 p-md-4 shadow border-0 mb-4">
            <h5 class="mb-3">Form Booking</h5>
            <form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Lapangan</label>
                    <select name="court_id" class="form-select" required>
                        @foreach($courts as $court)
                            <option value="{{ $court->id }}" @selected(old('court_id', $selectedCourtId) == $court->id)>
                                {{ $court->nama_lapangan }} - Rp {{ number_format($court->harga_per_jam, 0, ',', '.') }}/jam
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $tanggal ?? '') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jam_mulai ?? '') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Durasi (Jam)</label>
                    <select name="durasi" class="form-select" id="durasiSelect" required>
                        @for($i=1; $i<=6; $i++)
                            <option value="{{ $i }}" @selected(old('durasi') == $i)>{{ $i }} Jam</option>
                        @endfor
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Total Harga</label>
                    <input type="text" id="totalHarga" class="form-control bg-light" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bukti Pembayaran</label>
                    <input type="file" name="bukti_pembayaran" class="form-control" accept=".jpg,.jpeg,.png" required>
                    <small class="text-muted">Format: JPG/PNG, maksimum 2MB.</small>
                </div>

                <button type="submit" class="btn btn-primary w-100">Konfirmasi Booking</button>
            </form>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <h5 class="mb-3">Informasi Lapangan</h5>
                @if($selectedCourt)
                    <p class="mb-1"><strong>{{ $selectedCourt->nama_lapangan }}</strong></p>
                    <p class="mb-1 text-muted">Olahraga: {{ $selectedCourt->jenis_olahraga }}</p>
                    <p class="mb-3 text-muted">Harga: Rp {{ number_format($selectedCourt->harga_per_jam, 0, ',', '.') }}/jam</p>
                @endif

                <h6 class="mb-2">Rating Rata-rata</h6>
                @php
                    $rounded = (int) round($averageRating ?? 0);
                @endphp
                <div class="mb-3 fs-5 text-warning">
                    @for($i=1; $i<=5; $i++)
                        @if($i <= $rounded)
                            &#9733;
                        @else
                            &#9734;
                        @endif
                    @endfor
                    <span class="text-dark fs-6">({{ number_format($averageRating ?? 0, 1) }})</span>
                </div>

                <h6 class="mb-2">Review Terbaru</h6>
                <div class="small" style="max-height: 280px; overflow:auto;">
                    @forelse($reviews as $review)
                        <div class="border rounded p-2 mb-2">
                            <div class="text-warning">
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= (int) $review->rating)
                                        &#9733;
                                    @else
                                        &#9734;
                                    @endif
                                @endfor
                            </div>
                            <div>{{ $review->comment ?: '-' }}</div>
                            <div class="text-muted">oleh {{ $review->user->name ?? 'User' }}</div>
                        </div>
                    @empty
                        <div class="text-muted">Belum ada review untuk lapangan ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const priceMap = {
        @foreach($courts as $court)
            {{ $court->id }}: {{ (int) $court->harga_per_jam }},
        @endforeach
    };

    const courtSelect = document.querySelector('select[name="court_id"]');
    const durasiSelect = document.getElementById('durasiSelect');
    const totalInput = document.getElementById('totalHarga');

    function hitungTotal() {
        const courtId = parseInt(courtSelect.value, 10);
        const durasi = parseInt(durasiSelect.value, 10);
        const hargaPerJam = priceMap[courtId] || 0;
        const total = durasi * hargaPerJam;
        totalInput.value = 'Rp ' + total.toLocaleString('id-ID');
    }

    courtSelect.addEventListener('change', hitungTotal);
    durasiSelect.addEventListener('change', hitungTotal);
    hitungTotal();

    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endsection
