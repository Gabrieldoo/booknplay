<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Cache;

class BookingController extends Controller
{
    public function index(Request $request)
{
    $tanggal = $request->query('tanggal');
    $jam_mulai = $request->query('jam_mulai');
    $jam_selesai = $request->query('jam_selesai');

    return view('booking', compact('tanggal','jam_mulai','jam_selesai'));
}

public function store(Request $request)
{
    $request->validate([
        'tanggal' => 'required|date',
        'jam_mulai' => 'required|date_format:H:i',
        'durasi' => 'required|integer',
        'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $hargaPerJam = 50000;

    $jamMulai = $request->jam_mulai . ":00";
    $jamSelesai = date('H:i:s', strtotime($jamMulai . ' +'.$request->durasi.' hour'));

    $totalHarga = $request->durasi * $hargaPerJam;

    // Upload bukti
    $path = $request->file('bukti_pembayaran')
            ->store('bukti_pembayaran', 'public');

    // Cek bentrok
    $bentrok = Booking::where('tanggal', $request->tanggal)
        ->where('jam_mulai', '<', $jamSelesai)
        ->where('jam_selesai', '>', $jamMulai)
        ->exists();

    if ($bentrok) {
        return back()->with('error', 'Jadwal sudah terisi.');
    }

    Booking::create([
        'court_id' => 1,
        'user_id' => auth()->id(),
        'tanggal' => $request->tanggal,
        'jam_mulai' => $jamMulai,
        'jam_selesai' => $jamSelesai,
        'status' => 'pending',
        'total_harga' => $totalHarga,
        'bukti_pembayaran' => $path,
    ]);

    Cache::forget($this->userBookingsCacheKey((int) auth()->id()));
    Cache::forget($this->dateScheduleCacheKey($request->tanggal));

    return back()->with('success', 'Booking berhasil dibuat.');
}

public function userDashboard(Request $request)
{
    $tanggal = $request->query('tanggal') ?? now()->toDateString();
    $userId = (int) auth()->id();

    $bookings = Cache::remember(
        $this->userBookingsCacheKey($userId),
        now()->addSeconds(30),
        fn () => Booking::query()
            ->select(['id', 'tanggal', 'jam_mulai', 'jam_selesai', 'status', 'bukti_pembayaran', 'total_harga'])
            ->where('user_id', $userId)
            ->latest('tanggal')
            ->latest('jam_mulai')
            ->get()
    );

    $jadwal = Cache::remember(
        $this->dateScheduleCacheKey($tanggal),
        now()->addSeconds(30),
        fn () => Booking::query()
            ->select(['id', 'jam_mulai', 'jam_selesai'])
            ->where('tanggal', $tanggal)
            ->orderBy('jam_mulai')
            ->get()
    );

    return view('userdashboard', compact('bookings', 'jadwal', 'tanggal'));
}

private function userBookingsCacheKey(int $userId): string
{
    return "booking:user:{$userId}:history";
}

private function dateScheduleCacheKey(string $tanggal): string
{
    return "booking:schedule:{$tanggal}";
}
}
