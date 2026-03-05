<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $totalBookings = Booking::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRevenue = Booking::whereIn('status', ['diterima', 'selesai'])
            ->sum('total_harga');

        $months = collect(CarbonPeriod::create(now()->subMonths(5)->startOfMonth(), '1 month', now()->startOfMonth()))
            ->map(fn ($date) => $date->format('Y-m'))
            ->values();

        $bookingsPerMonth = $months->map(function (string $month): int {
            return Booking::whereYear('tanggal', (int) substr($month, 0, 4))
                ->whereMonth('tanggal', (int) substr($month, 5, 2))
                ->count();
        });

        $usersPerMonth = $months->map(function (string $month): int {
            return User::whereYear('created_at', (int) substr($month, 0, 4))
                ->whereMonth('created_at', (int) substr($month, 5, 2))
                ->count();
        });

        $revenuePerMonth = $months->map(function (string $month): int {
            return Booking::whereIn('status', ['diterima', 'selesai'])
                ->whereYear('tanggal', (int) substr($month, 0, 4))
                ->whereMonth('tanggal', (int) substr($month, 5, 2))
                ->sum('total_harga');
        });

        return view('dashboard', [
            'totalBookings' => $totalBookings,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'chartLabels' => $months->map(fn (string $month) => Carbon::parse($month.'-01')->format('M Y')),
            'bookingsPerMonth' => $bookingsPerMonth,
            'usersPerMonth' => $usersPerMonth,
            'revenuePerMonth' => $revenuePerMonth,
        ]);
    }
}
