<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_reminder',
            'booking_id' => $this->booking->id,
            'message' => 'Pengingat: booking #'.$this->booking->id.' akan dimulai besok.',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingat Booking')
            ->line('Pengingat booking lapangan Anda untuk besok.')
            ->line('Tanggal: '.$this->booking->tanggal->format('d-m-Y'))
            ->line('Jam: '.substr($this->booking->jam_mulai, 0, 5).' - '.substr($this->booking->jam_selesai, 0, 5))
            ->action('Lihat Dashboard', route('userdashboard'));
    }
}
