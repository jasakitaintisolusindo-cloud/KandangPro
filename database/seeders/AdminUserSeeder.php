<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'priasojo@jasfarm.co.id'],
            [
                'name' => 'Priasojo',
                'password' => 'admin123',
            ]
        );

        $this->command->info('User priasojo@jasfarm.co.id created successfully with password admin123');
    }
}
