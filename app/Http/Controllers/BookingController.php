<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Court;
use App\Notifications\BookingCancelledNotification;
use App\Notifications\BookingSuccessNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $courts = Court::query()->orderBy('nama_lapangan')->get();
        $selectedCourtId = (int) $request->input('court_id', $courts->first()->id ?? 0);

        $selectedCourt = $courts->firstWhere('id', $selectedCourtId);

        $reviews = Booking::query()
            ->with(['review.user'])
            ->where('court_id', $selectedCourtId)
            ->whereHas('review')
            ->latest('tanggal')
            ->limit(10)
            ->get()
            ->pluck('review');

        $averageRating = $reviews->avg('rating');

        return view('booking', [
            'tanggal' => $request->query('tanggal'),
            'jam_mulai' => $request->query('jam_mulai'),
            'courts' => $courts,
            'selectedCourtId' => $selectedCourtId,
            'selectedCourt' => $selectedCourt,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'court_id' => ['required', 'exists:courts,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'durasi' => ['required', 'integer', 'min:1', 'max:6'],
            'bukti_pembayaran' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $court = Court::findOrFail((int) $validated['court_id']);
        $jamMulai = $validated['jam_mulai'].':00';
        $jamSelesai = date('H:i:s', strtotime($jamMulai.' +'.$validated['durasi'].' hour'));
        $totalHarga = (int) $validated['durasi'] * (int) $court->harga_per_jam;

        $isConflict = Booking::query()
            ->where('court_id', $court->id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->whereNotIn('status', ['ditolak', 'dibatalkan'])
            ->exists();

        if ($isConflict) {
            return back()->withInput()->with('error', 'Jadwal lapangan sudah terisi pada jam tersebut.');
        }

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $booking = Booking::create([
            'court_id' => $court->id,
            'user_id' => auth()->id(),
            'tanggal' => $validated['tanggal'],
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'status' => 'pending',
            'total_harga' => $totalHarga,
            'payment_status' => 'unpaid',
            'bukti_pembayaran' => $path,
        ]);

        $request->user()?->notify(new BookingSuccessNotification($booking));

        return redirect()->route('userdashboard')->with('success', 'Booking berhasil dibuat.');
    }

    public function userDashboard(Request $request): View
    {
        $query = Booking::query()
            ->with(['court', 'review'])
            ->where('user_id', auth()->id());

        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->string('date'));
        }

        if ($request->filled('field')) {
            $query->where('court_id', (int) $request->input('field'));
        }

        if ($request->filled('search')) {
            $search = (string) $request->string('search');
            $query->where(function ($builder) use ($search): void {
                $builder->where('status', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhereHas('court', fn ($q) => $q->where('nama_lapangan', 'like', "%{$search}%"));
            });
        }

        $sortBy = $request->input('sort_by', 'tanggal');
        $sortDir = $request->input('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['tanggal', 'jam_mulai', 'created_at', 'total_harga', 'status'];

        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'tanggal';
        }

        $bookings = $query->orderBy($sortBy, $sortDir)
            ->orderBy('jam_mulai')
            ->paginate(10)
            ->withQueryString();

        $tanggal = $request->query('tanggal', now()->toDateString());
        $selectedCourtId = (int) $request->query('court_id', Court::query()->value('id'));

        $jadwal = Booking::query()
            ->select(['id', 'jam_mulai', 'jam_selesai'])
            ->whereDate('tanggal', $tanggal)
            ->where('court_id', $selectedCourtId)
            ->whereNotIn('status', ['ditolak', 'dibatalkan'])
            ->orderBy('jam_mulai')
            ->get();

        $notifications = $request->user()
            ?->notifications()
            ->latest()
            ->limit(8)
            ->get() ?? collect();

        $courts = Court::orderBy('nama_lapangan')->get();

        return view('userdashboard', compact(
            'bookings',
            'jadwal',
            'tanggal',
            'notifications',
            'courts',
            'sortBy',
            'sortDir',
            'selectedCourtId'
        ));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        abort_unless((int) $booking->user_id === (int) auth()->id(), 403);

        if (in_array($booking->status, ['dibatalkan', 'ditolak'], true)) {
            return back()->with('error', 'Booking sudah dibatalkan sebelumnya.');
        }

        $booking->update([
            'status' => 'dibatalkan',
            'payment_status' => 'cancelled',
        ]);

        $booking->user?->notify(new BookingCancelledNotification($booking));

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function calendar(Request $request): View
    {
        $tanggal = $request->query('tanggal', now()->toDateString());
        $courts = Court::orderBy('nama_lapangan')->get();
        $selectedCourtId = (int) $request->query('court_id', $courts->first()->id ?? 0);

        $bookings = Booking::query()
            ->whereDate('tanggal', $tanggal)
            ->where('court_id', $selectedCourtId)
            ->whereNotIn('status', ['ditolak', 'dibatalkan'])
            ->orderBy('jam_mulai')
            ->get();

        return view('jadwal', compact('tanggal', 'bookings', 'courts', 'selectedCourtId'));
    }
}
