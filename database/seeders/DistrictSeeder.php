<?php

// DistrictSeeder.php
namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            ['code' => '01', 'name' => 'Uchtepa'],
            ['code' => '02', 'name' => 'Bektemir'],
            ['code' => '03', 'name' => 'Chilonzor'],
            ['code' => '04', 'name' => 'Yashnobod'],
            ['code' => '05', 'name' => 'Yakkasaroy'],
            ['code' => '06', 'name' => 'Sergeli'],
            ['code' => '07', 'name' => 'Yunusobod'],
            ['code' => '08', 'name' => 'Olmazor'],
            ['code' => '09', 'name' => 'Mirzo Ulugbek'],
            ['code' => '10', 'name' => 'Shayxontohur'],
            ['code' => '11', 'name' => 'Mirobod'],
            ['code' => '12', 'name' => 'Yangihayot']
        ];

        foreach ($districts as $district) {
            District::create([
                'name' => $district['name'],
                'slug' => Str::slug($district['name']),
                'is_active' => true
            ]);
        }
    }
}