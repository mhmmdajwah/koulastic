<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Acara;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // Membuat user admin
        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123')
        ]);

        // Membuat acara
        // Acara::create([
        //     'nama_acara' => 'Seminar Wisuda',
        //     'lokasi' => 'Gedung Serba Guna',
        //     'harga' => 100000,
        //     'tanggal_mulai' => '2023-06-01',
        //     'tanggal_selesai' => '2023-06-02',
        //     'status' => true,
        //     'catatan' => 'Catatan acara'
        // ]);
    }
}
