<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Cache;

class ChatbotController extends Controller
{
    public function index(Request $request)
{
    $tanggal = $request->query('tanggal');
    $jam_mulai = $request->query('jam'); // UBAH DI SINI

    return view('chatbot', compact('tanggal','jam_mulai'));
}
   public function proses(Request $request)
{
    $pertanyaan = strtolower(trim($request->pertanyaan));

    // Greeting
    if (str_contains($pertanyaan, 'halo') || str_contains($pertanyaan, 'hai')) {
        return response()->json([
            'jawaban' => "Halo Saya asisten BookNPlay. Silakan ketik tanggal dan jam. Contoh: 10-02-2026 14:00"
        ]);
    }

    // Regex tanggal + jam
    if (preg_match('/(\d{1,2})[-\/](\d{1,2})[-\/](\d{4})\s+(\d{1,2}):(\d{2})/', $pertanyaan, $match)) {

        $hari  = str_pad($match[1], 2, '0', STR_PAD_LEFT);
        $bulan = str_pad($match[2], 2, '0', STR_PAD_LEFT);
        $tahun = $match[3];
        $jam   = str_pad($match[4], 2, '0', STR_PAD_LEFT);
        $menit = $match[5];

        $tanggal = "$tahun-$bulan-$hari";
        $jamMulai = "$jam:$menit:00";
        $jamSelesai = date('H:i:s', strtotime($jamMulai . ' +1 hour'));

        $isTerisi = Cache::remember(
            "booking:availability:{$tanggal}:{$jam}:{$menit}",
            now()->addSeconds(30),
            fn () => Booking::query()
                ->where('tanggal', $tanggal)
                ->where('jam_mulai', '<', $jamSelesai)
                ->where('jam_selesai', '>', $jamMulai)
                ->exists()
        );

        if ($isTerisi) {
            return response()->json([
                'status' => 'terisi',
                'jawaban' => " Lapangan tanggal "
                    . date('d-m-Y', strtotime($tanggal))
                    . " jam $jam:$menit sudah TERISI."
            ]);
        }

        return response()->json([
            'status' => 'kosong',
            'tanggal' => $tanggal,
            'jam' => "$jam:$menit",
            'jawaban' => " Lapangan tanggal "
                . date('d-m-Y', strtotime($tanggal))
                . " jam $jam:$menit MASIH KOSONG."
        ]);
    }

    return response()->json([
        'jawaban' => "Format tidak dikenali. Gunakan format: 10-02-2026 14:00"
    ]);
}
}
