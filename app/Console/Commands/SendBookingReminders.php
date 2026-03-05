<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Notifications\BookingReminderNotification;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Send reminders for bookings scheduled tomorrow';

    public function handle(): int
    {
        $bookings = Booking::query()
            ->with('user')
            ->whereDate('tanggal', now()->addDay()->toDateString())
            ->whereIn('status', ['pending', 'diterima'])
            ->get();

        foreach ($bookings as $booking) {
            $booking->user?->notify(new BookingReminderNotification($booking));
        }

        $this->info("Reminder sent: {$bookings->count()} booking(s).");

        return self::SUCCESS;
    }
}
