<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        use Illuminate\Support\Facades\Hash;
        
        User::updateOrCreate(
            ['email' => 'admin@pad.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123')
            ]
        );
    }
}
