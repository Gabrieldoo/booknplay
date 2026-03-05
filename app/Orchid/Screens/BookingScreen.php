<?php

namespace App\Orchid\Screens;

use App\Models\Booking;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Toast;
use Orchid\Support\Color;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\DropDown;

class BookingScreen extends Screen
{
    public $name = 'Manajemen Booking';

    public function query(): iterable
    {
        return [
            'bookings' => Booking::query()
                ->with(['user:id,name', 'court:id,nama_lapangan'])
                ->orderByDesc('tanggal')
                ->orderByDesc('jam_mulai')
                ->paginate(),
        ];
    }

    public function layout(): iterable
    {
        return [

            Layout::table('bookings', [

                TD::make('id','ID')
                    ->render(fn($b) => $b->id),

                TD::make('user.name', 'Pemesan')
                    ->render(fn ($b) => $b->user?->name ?? "-"),

                TD::make('court.nama_lapangan', 'Lapangan')
                    ->render(fn ($b) => $b->court?->nama_lapangan ?? "-"),

                TD::make('tanggal','Tanggal')
                    ->render(fn($b) => $b->tanggal),

                TD::make('jam_mulai','Mulai')
                    ->render(fn($b) => $b->jam_mulai),

                TD::make('jam_selesai','Selesai')
                    ->render(fn($b) => $b->jam_selesai),

                TD::make('total_harga','Total')
                    ->render(fn($b) => 'Rp '.number_format($b->total_harga ?? 0,0,',','.')),

                TD::make('status','Status')
                    ->render(function($b){

                        if($b->status == 'pending'){
                            return '<span class="badge bg-warning">Pending</span>';
                        }

                        if($b->status == 'diterima'){
                            return '<span class="badge bg-success">Diterima</span>';
                        }

                        if($b->status == 'ditolak'){
                            return '<span class="badge bg-danger">Ditolak</span>';
                        }

                        return $b->status;
                    }),

                TD::make('bukti_pembayaran','Bukti')
                    ->render(function($b){

                        if(!$b->bukti_pembayaran){
                            return '-';
                        }

                        return '<a href="/storage/'.$b->bukti_pembayaran.'" target="_blank">Lihat</a>';
                    }),

                TD::make('aksi','Aksi')
                    ->render(function($b){
                        return DropDown::make()
                            ->icon('bs.three-dots-vertical')
                            ->list([
                                Link::make('Edit')
                                    ->route('platform.booking.edit', $b->id)
                                    ->icon('bs.pencil'),
                                Button::make('Terima')
                                    ->method('terima')
                                    ->parameters(['id' => $b->id])
                                    ->type(Color::SUCCESS()),
                                Button::make('Tolak')
                                    ->method('tolak')
                                    ->parameters(['id' => $b->id])
                                    ->type(Color::DANGER()),
                            ]);
                    }),

            ])

        ];
    }

    public function terima($id)
    {
        Booking::findOrFail($id)->update([
            'status'=>'diterima'
        ]);

        Toast::info('Booking diterima');
    }

    public function tolak($id)
    {
        Booking::findOrFail($id)->update([
            'status'=>'ditolak'
        ]);

        Toast::warning('Booking ditolak');
    }
}
