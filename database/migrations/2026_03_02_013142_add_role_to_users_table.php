<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasRole = Schema::hasColumn('users', 'role');

        Schema::table('users', function (Blueprint $table) use ($hasRole): void {
            if (! $hasRole) {
                $table->string('role')->default('user')->index();
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $hasRole = Schema::hasColumn('users', 'role');

        Schema::table('users', function (Blueprint $table) use ($hasRole): void {
            if ($hasRole) {
                $table->dropColumn('role');
            }
        });
    }
};
