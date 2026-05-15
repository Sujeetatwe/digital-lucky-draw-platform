<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WinningGiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gifts = [
            ['gift_name' => 'LCD T.V', 'quantity' => 1],
            ['gift_name' => 'WASHING MACHINE', 'quantity' => 1],
            ['gift_name' => 'REFRIGERATOR', 'quantity' => 1],
            ['gift_name' => 'COOLER', 'quantity' => 1],
            ['gift_name' => 'GRAIN MILL', 'quantity' => 1],
            ['gift_name' => 'WATER FILTER', 'quantity' => 1],
            ['gift_name' => 'OVEN', 'quantity' => 1],
            ['gift_name' => 'JUICER', 'quantity' => 1], // Corrected spelling from JUCIER
            ['gift_name' => 'TEA THERMOS', 'quantity' => 1], // Corrected spelling from TEA THERMS
            ['gift_name' => 'DINNER SET', 'quantity' => 1],
            ['gift_name' => 'PAITHANI', 'quantity' => 10],
            ['gift_name' => 'Helicopter Ride', 'quantity' => 5],
        ];

        $now = Carbon::now();

        foreach ($gifts as $gift) {
            DB::table('winning_gifts')->insert([
                'gift_name' => $gift['gift_name'],
                'quantity' => $gift['quantity'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
