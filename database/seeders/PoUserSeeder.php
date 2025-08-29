<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create PO users
        User::create([
            'name' => 'PO Manager',
            'email' => 'po@example.com',
            'password' => Hash::make('password'),
            'role' => 'po',
        ]);

        User::create([
            'name' => 'PO Staff 1',
            'email' => 'po1@example.com',
            'password' => Hash::make('password'),
            'role' => 'po',
        ]);

        User::create([
            'name' => 'PO Staff 2',
            'email' => 'po2@example.com',
            'password' => Hash::make('password'),
            'role' => 'po',
        ]);

        $this->command->info('PO users created successfully!');
    }
}
