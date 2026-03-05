<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService)
    {
    }

    public function initiate(Booking $booking): RedirectResponse
    {
        abort_unless((int) $booking->user_id === (int) auth()->id(), 403);

        $payment = $this->paymentService->initiatePayment($booking);

        $booking->update([
            'payment_status' => $payment['payment_status'],
            'payment_method' => $payment['provider'],
            'transaction_id' => $payment['snap_token'],
        ]);

        return redirect($payment['redirect_url'])
            ->with('success', 'Pembayaran diinisiasi (skeleton Midtrans aktif).');
    }

    public function callback(Request $request): JsonResponse
    {
        $result = $this->paymentService->handleCallback($request->all());

        if (! empty($result['order_id'])) {
            $booking = Booking::find($result['order_id']);

            if ($booking) {
                $booking->update([
                    'payment_status' => $result['payment_status'],
                    'payment_method' => $result['payment_method'],
                    'transaction_id' => $result['transaction_id'],
                ]);
            }
        }

        return response()->json(['message' => 'Callback processed (skeleton).']);
    }
}
