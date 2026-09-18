<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\TicketType;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TicketTypeSeeder::class,
            SettingSeeder::class,
        ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@mma.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Gate Man',
            'email' => 'gate@mma.test',
            'password' => Hash::make('password'),
            'role' => 'gate_man',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
