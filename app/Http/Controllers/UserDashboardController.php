<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class UserDashboardController extends Controller
{
    
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->get();

        return view('userdashboard', compact('bookings'));
    }
}
