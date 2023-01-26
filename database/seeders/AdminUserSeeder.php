<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@bondstein.com',
            'phone' => '01737962059',
            'address' => 'bondstein',
            'image' => 'sddasds.jpg',
            'password' => Hash::make('1234'),
            'role_id' => 1
        ]);
    }
}
