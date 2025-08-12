<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating users...');

        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@tutashhudud.uz'],
            [
                'name' => 'Super Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'permissions' => json_encode(['create', 'read', 'update', 'delete']),
                'is_active' => true,
                'remember_token' => Str::random(10),
            ]
        );

        $this->command->info('Super Admin created: admin@tutashhudud.uz / admin123');
        Log::info('Super Admin Created - Email: admin@tutashhudud.uz, Password: admin123');

        // Check if districts exist
        $districts = District::all();

        if ($districts->isEmpty()) {
            $this->command->warn('No districts found. Please run DistrictSeeder first.');
            return;
        }

        // Create District Admins
        $this->command->info('Creating district admins...');

        $credentials = [];

        foreach ($districts as $district) {
            // Generate email from district slug
            $email = strtolower(str_replace([' ', '-'], '', $district->slug)) . '@tutashhudud.uz';

            // Generate password
            $password = 'district' . rand(1000, 9999);

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $district->name . ' Admin',
                    'email_verified_at' => now(),
                    'password' => Hash::make($password),
                    'district_id' => $district->id,
                    'role' => 'district_admin',
                    'permissions' => json_encode(['create', 'read', 'update']),
                    'is_active' => true,
                    'remember_token' => Str::random(10),
                ]
            );

            // Store credentials for display
            $credentials[] = [
                'district' => $district->name,
                'email' => $email,
                'password' => $password
            ];

            // Log credentials for reference
            Log::info("District Admin Created - {$district->name}: Email: {$email}, Password: {$password}");
        }

        // Display all credentials in console
        $this->command->info('District Admin Credentials:');
        $this->command->table(
            ['District', 'Email', 'Password'],
            array_map(function ($cred) {
                return [$cred['district'], $cred['email'], $cred['password']];
            }, $credentials)
        );

        $this->command->info('Total users created: ' . User::count());
    }
}
