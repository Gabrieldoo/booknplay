<?php

namespace App\Services;

use App\Models\Booking;

class PaymentService
{
    public function initiatePayment(Booking $booking): array
    {
        // TODO: Place Midtrans Server Key in .env and config/services.php.
        // TODO: Build Midtrans Snap/API payload here.

        return [
            'provider' => 'midtrans',
            'booking_id' => $booking->id,
            'payment_status' => 'pending',
            'snap_token' => 'midtrans-snap-token-placeholder',
            'redirect_url' => route('userdashboard'),
        ];
    }

    public function handleCallback(array $payload): array
    {
        // TODO: Verify Midtrans callback signature before trusting payload.
        // TODO: Map Midtrans transaction status to local payment_status.

        $status = $payload['transaction_status'] ?? 'pending';
        $orderId = $payload['order_id'] ?? null;

        return [
            'order_id' => $orderId,
            'payment_status' => $status,
            'payment_method' => $payload['payment_type'] ?? null,
            'transaction_id' => $payload['transaction_id'] ?? null,
        ];
    }
}
