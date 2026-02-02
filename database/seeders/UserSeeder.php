<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create initial administrator account
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@studentrecord.com',
            'password' => Hash::make('password'),
        ]);
    }
}
