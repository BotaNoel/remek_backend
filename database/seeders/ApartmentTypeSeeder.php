<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ApartmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('apartment_types')->insert([
            ['name' => 'Apartman'],
            ['name' => 'Luxus ingatlan'],
            ['name' => 'Szálloda'],
            ['name' => 'Családi ház'],
        ]);
    }
}
