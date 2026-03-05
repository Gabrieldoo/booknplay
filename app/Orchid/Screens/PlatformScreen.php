<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Label;
use Orchid\Support\Facades\Toast;
use App\Models\Booking;
use App\Models\User;

class PlatformScreen extends Screen
{
    public $name = 'Dashboard Admin';

    public function query(): array
    {

        $today = date('Y-m-d');

        $bookingHariIni = Booking::where('tanggal',$today)->count();

        $pendapatanHariIni = Booking::where('tanggal',$today)
            ->where('status','diterima')
            ->sum('total_harga');

        $bookingPending = Booking::where('status','pending')->count();

        $totalUser = User::count();

        return [

            'stats' => [
                'bookingHariIni' => $bookingHariIni,
                'pendapatanHariIni' => $pendapatanHariIni,
                'bookingPending' => $bookingPending,
                'totalUser' => $totalUser,
            ]

        ];
    }

    public function layout(): array
    {
        return [

            Layout::metrics([

                'Booking Hari Ini' => 'stats.bookingHariIni',

                'Pendapatan Hari Ini' => 'Rp '.number_format($this->query()['stats']['pendapatanHariIni']),

                'Booking Pending' => 'stats.bookingPending',

                'Total User' => 'stats.totalUser',

            ])

        ];
    }
}