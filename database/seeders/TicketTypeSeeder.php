<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketType;

class TicketTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['general', 'عام', 500, 500, 1, true, 1],
            ['VIP', 'لاي متميز', 1000, 250, 2, true, 2],
            ['Ringside', 'مقعد مباشر', 2000, 50, 3, true, 3],
        ];

        foreach ($types as $i => $data) {
            TicketType::create([
                'name_en' => $data[0],
                'name_ar' => $data[1],
                'description_en' => match($data[0]) {
                    'general' => 'General admission seating in the stands.',
                    'VIP' => 'VIP seating with premium comfort and service.',
                    'Ringside' => 'Front-row seats closest to the octagon action.',
                },
                'description_ar' => match($data[0]) {
                    'general' => 'مقعد عام في الأسواص.',
                    'VIP' => 'مقعد VIP بخدمة متميزة وراحة مريحة.',
                    'Ringside' => 'مقاعد في الصفوف الأمامية بجوار الكوع.',
                },
                'price' => $data[2],
                'capacity' => $data[3],
                'is_active' => $data[4],
                'sort_order' => $data[5],
            ]);
        }
    }
}
