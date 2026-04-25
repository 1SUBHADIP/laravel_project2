<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@cclms.com'],
            [
                'name' => 'CCLMS Administrator',
                'email' => 'admin@cclms.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create a test admin user
        User::updateOrCreate(
            ['email' => 'nayaksubhadip741@gmail.com'],
            [
                'name' => 'Admin',
                'email' => 'nayaksubhadip741@gmail.com',
                'password' => Hash::make('Subhadip@741'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->line('Email: admin@cclms.com | Password: admin123');
        $this->command->line('Email: nayaksubhadip741@gmail.com | Password: Subhadip@741');
    }
}
