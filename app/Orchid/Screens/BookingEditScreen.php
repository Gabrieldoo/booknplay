<?php

namespace App\Orchid\Screens;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;

class BookingEditScreen extends Screen
{

    public $name = 'Edit Booking';
    public $description = 'Ubah data booking';

    public function query(Booking $booking): array
    {
        $this->description = 'Booking ID : ' . $booking->id;

        return [
            'booking' => $booking
        ];
    }


    public function commandBar(): array
    {
        return [
            Button::make('Update Booking')
                ->icon('check')
                ->method('update'),
        ];
    }

    public function layout(): array
    {
        return [

            Layout::rows([

                Select::make('booking.user_id')
                    ->title('Nama Pemesan')
                    ->fromModel(User::class, 'name')
                    ->required(),

                Select::make('booking.court_id')
                    ->title('Lapangan')
                    ->fromModel(Court::class, 'nama_lapangan')
                    ->required(),

                Input::make('booking.tanggal')
                    ->title('Tanggal')
                    ->type('date')
                    ->required(),

                Input::make('booking.jam_mulai')
                    ->title('Jam Mulai')
                    ->type('time')
                    ->required(),

                Input::make('booking.jam_selesai')
                    ->title('Jam Selesai')
                    ->type('time')
                    ->required(),

                Select::make('booking.status')
                    ->title('Status')
                    ->options([
                        'pending' => 'Pending',
                        'diterima' => 'Diterima',
                        'ditolak' => 'Ditolak',
                    ])
                    ->required(),

                Input::make('booking.total_harga')
                    ->title('Total Harga')
                    ->type('number')
                    ->min(0),

                Input::make('booking.bukti_pembayaran')
                    ->title('Path Bukti Pembayaran')
                    ->placeholder('contoh: bukti_pembayaran/file.jpg'),

            ])

        ];
    }

    public function update(Booking $booking, Request $request)
    {
        $oldUserId = (int) $booking->user_id;
        $oldTanggal = (string) $booking->tanggal;

        $validated = $request->validate([
            'booking.user_id' => ['required', 'exists:users,id'],
            'booking.court_id' => ['required', 'exists:courts,id'],
            'booking.tanggal' => ['required', 'date'],
            'booking.jam_mulai' => ['required', 'date_format:H:i'],
            'booking.jam_selesai' => ['required', 'date_format:H:i'],
            'booking.status' => ['required', 'in:pending,diterima,ditolak'],
            'booking.total_harga' => ['nullable', 'integer', 'min:0'],
            'booking.bukti_pembayaran' => ['nullable', 'string', 'max:255'],
        ])['booking'];

        $jamMulai = Carbon::createFromFormat('H:i', $validated['jam_mulai'])->format('H:i:s');
        $jamSelesai = Carbon::createFromFormat('H:i', $validated['jam_selesai'])->format('H:i:s');

        if ($jamSelesai <= $jamMulai) {
            Alert::error('Jam selesai harus lebih besar dari jam mulai.');
            return back();
        }

        $bentrok = Booking::query()
            ->where('id', '!=', $booking->id)
            ->where('court_id', $validated['court_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('jam_mulai', '<', $jamSelesai)
            ->where('jam_selesai', '>', $jamMulai)
            ->exists();

        if ($bentrok) {
            Alert::error('Jadwal bentrok dengan booking lain di lapangan yang sama.');
            return back();
        }

        $booking->update([
            'user_id' => $validated['user_id'],
            'court_id' => $validated['court_id'],
            'tanggal' => $validated['tanggal'],
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'status' => $validated['status'],
            'total_harga' => $validated['total_harga'] ?? 0,
            'bukti_pembayaran' => $validated['bukti_pembayaran'] ?? null,
        ]);

        Cache::forget("booking:user:{$oldUserId}:history");
        Cache::forget("booking:schedule:{$oldTanggal}");
        Cache::forget("booking:user:{$booking->user_id}:history");
        Cache::forget("booking:schedule:{$booking->tanggal}");

        Alert::info('Booking berhasil diupdate');

        return redirect()->route('platform.booking');
    }

}
