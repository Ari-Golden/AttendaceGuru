<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'password'=>bcrypt('12345678'),

        ]);
        $admin->assignRole('admin');
        $guru = User::create([
            'name'=>'Guru',
            'email'=>'guru@gmail.com',
            'password'=>bcrypt('12345678'),
            ]);
            $guru->assignRole('guru');
    }
}
