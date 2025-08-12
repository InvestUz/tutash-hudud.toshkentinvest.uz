<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@tutashhudud.uz',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'permissions' => ['create', 'read', 'update', 'delete'],
            'is_active' => true,
            'remember_token' => Str::random(10),
        ]);

        // Create District Admins
        $districts = District::all();
        foreach ($districts as $district) {
            // Generate random credentials for each district
            $email = strtolower(str_replace(' ', '', $district->slug)) . '@tutashhudud.uz';
            $password = 'district' . rand(1000, 9999);

            $user = User::create([
                'name' => $district->name . ' Admin',
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make($password),
                'district_id' => $district->id,
                'role' => 'district_admin',
                'permissions' => ['create', 'read', 'update'],
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]);

            // Log credentials for reference
            \Log::info("District Admin Created - {$district->name}: Email: {$email}, Password: {$password}");
        }
    }
}
