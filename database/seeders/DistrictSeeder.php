<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            'Uchtepa',
            'Bektemir',
            'Chilonzor',
            'Yashnobod',
            'Yakkasaroy',
            'Sergeli',
            'Yunusobod',
            'Olmazor',
            'Mirzo Ulugbek',
            'Shayxontohur',
            'Mirobod',
            'Yangihayot'
        ];

        foreach ($districts as $district) {
            District::create([
                'name' => $district,
                'slug' => Str::slug($district),
                'is_active' => true
            ]);
        }
    }
}
