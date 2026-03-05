<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->index(['user_id', 'tanggal'], 'bookings_user_tanggal_index');
            $table->index(['tanggal', 'jam_mulai', 'jam_selesai'], 'bookings_tanggal_jam_index');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_user_tanggal_index');
            $table->dropIndex('bookings_tanggal_jam_index');
        });
    }
};
