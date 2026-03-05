<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('bookings', 'total_harga')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->unsignedBigInteger('total_harga')->default(0)->after('status');
            });
        }

        if (! Schema::hasColumn('bookings', 'payment_status')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->string('payment_status')->default('unpaid')->after('total_harga');
            });
        }

        if (! Schema::hasColumn('bookings', 'payment_method')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->string('payment_method')->nullable()->after('payment_status');
            });
        }

        if (! Schema::hasColumn('bookings', 'transaction_id')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->string('transaction_id')->nullable()->after('payment_method');
            });
        }
    }

    public function down(): void
    {
        $hasTransactionId = Schema::hasColumn('bookings', 'transaction_id');
        $hasPaymentMethod = Schema::hasColumn('bookings', 'payment_method');
        $hasPaymentStatus = Schema::hasColumn('bookings', 'payment_status');
        $hasTotalHarga = Schema::hasColumn('bookings', 'total_harga');

        Schema::table('bookings', function (Blueprint $table) use ($hasTransactionId, $hasPaymentMethod, $hasPaymentStatus, $hasTotalHarga): void {
            if ($hasTransactionId) {
                $table->dropColumn('transaction_id');
            }

            if ($hasPaymentMethod) {
                $table->dropColumn('payment_method');
            }

            if ($hasPaymentStatus) {
                $table->dropColumn('payment_status');
            }

            if ($hasTotalHarga) {
                $table->dropColumn('total_harga');
            }
        });
    }
};
