<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // OWNER
        // =========================
        User::create([
            'name' => 'Owner',
            'email' => 'owner@rentapp.test',
            'password' => 'password',
            'role' => 'owner',
        ]);

        // =========================
        // KARYAWAN 1
        // =========================
        $userKaryawan1 = User::create([
            'name' => 'Budi',
            'email' => 'budi@rentapp.test',
            'password' => 'password',
            'role' => 'karyawan',
        ]);

        Karyawan::create([
            'user_id' => $userKaryawan1->id,
            'nama' => 'Budi',
        ]);

        // =========================
        // KARYAWAN 2
        // =========================
        $userKaryawan2 = User::create([
            'name' => 'Kamdi',
            'email' => 'kamdi@rentapp.test',
            'password' => 'password',
            'role' => 'karyawan',
        ]);

        Karyawan::create([
            'user_id' => $userKaryawan2->id,
            'nama' => 'Kamdi',
        ]);
    }
}
