<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Court;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@booknplay.test'],
            [
                'name' => 'BookNPlay Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@booknplay.test'],
            [
                'name' => 'BookNPlay User',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        Court::updateOrCreate(
            ['nama_lapangan' => 'Lapangan A'],
            ['jenis_olahraga' => 'Badminton', 'harga_per_jam' => 50000]
        );

        Court::updateOrCreate(
            ['nama_lapangan' => 'Lapangan B'],
            ['jenis_olahraga' => 'Badminton', 'harga_per_jam' => 60000]
        );
    }
}
