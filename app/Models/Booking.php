<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'court_id',
        'user_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'bukti_pembayaran',
        'total_harga',
        'payment_status',
        'payment_method',
        'transaction_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }
}
