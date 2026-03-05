<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Notifications\BookingCancelledNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminBookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::query()->with(['user', 'court']);

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
                    ->orWhereHas('user', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('court', fn ($q) => $q->where('nama_lapangan', 'like', "%{$search}%"));
            });
        }

        $sortBy = $request->input('sort_by', 'tanggal');
        $sortDir = $request->input('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $allowedSorts = ['tanggal', 'jam_mulai', 'status', 'total_harga', 'created_at'];

        if (! in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'tanggal';
        }

        $bookings = $query->orderBy($sortBy, $sortDir)
            ->orderBy('jam_mulai')
            ->paginate(10)
            ->withQueryString();

        $courts = \App\Models\Court::orderBy('nama_lapangan')->get();

        return view('admin.bookings.index', compact('bookings', 'courts', 'sortBy', 'sortDir'));
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,diterima,ditolak,dibatalkan,selesai'],
        ]);

        $booking->update(['status' => $validated['status']]);

        if (in_array($validated['status'], ['ditolak', 'dibatalkan'], true)) {
            $booking->user?->notify(new BookingCancelledNotification($booking));
        }

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }
}
