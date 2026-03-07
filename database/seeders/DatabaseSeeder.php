<?php

namespace Database\Seeders;

use App\Models\User;
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
        $admin = User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'admin@example.com',
            'phone_number' => '1234567890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $token = $admin->createToken('admin_token')->plainTextToken;
        echo "Admin token: $token\n";
    }
}
