<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'sergio.nader@gmail.com'],
            [
                'name' => 'Sergio Nader',
                'password' => Hash::make('test1234'),
                'is_admin' => true,
            ]
        );
    }
}
