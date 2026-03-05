<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless((int) $booking->user_id === (int) auth()->id(), 403);

        if ($booking->review) {
            return back()->with('error', 'Booking ini sudah memiliki review.');
        }

        if ($booking->tanggal->isFuture()) {
            return back()->with('error', 'Review hanya bisa diberikan setelah jadwal booking lewat.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'booking_id' => $booking->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih, review berhasil disimpan.');
    }
}
