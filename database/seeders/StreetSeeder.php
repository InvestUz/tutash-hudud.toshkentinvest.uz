<?php

namespace Database\Seeders;

use App\Models\Mahalla;
use App\Models\Street;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StreetSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = public_path('Street_datas/_kochalar.xlsx');
        
        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove header row
        array_shift($rows);

        // District code mapping for mahalla lookup
        $districtMapping = [
            '01' => 1, '02' => 2, '03' => 3, '04' => 4, '05' => 5, '06' => 6,
            '07' => 7, '08' => 8, '09' => 9, '10' => 10, '11' => 11, '12' => 12
        ];

        foreach ($rows as $row) {
            if (empty($row[1]) || empty($row[5])) continue; // Skip empty rows
            
            $streetName = trim($row[1]); // Street name in Uzbek
            $districtCode = substr(trim($row[5]), 0, 2); // Extract district code from full code
            
            if (isset($districtMapping[$districtCode])) {
                $districtId = $districtMapping[$districtCode];
                
                // Find a mahalla in this district (we'll assign streets to first available mahalla)
                $mahalla = Mahalla::where('district_id', $districtId)->first();
                
                if ($mahalla) {
                    // Check if street already exists
                    $existingStreet = Street::where('mahalla_id', $mahalla->id)
                        ->where('name', $streetName)
                        ->first();
                    
                    if (!$existingStreet) {
                        Street::create([
                            'mahalla_id' => $mahalla->id,
                            'name' => $streetName,
                            'is_active' => true
                        ]);
                    }
                }
            }
        }
    }
}