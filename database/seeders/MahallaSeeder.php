<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Mahalla;
use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MahallaSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = public_path('Street_datas/_mahallalar.xlsx');
        
        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // Remove header row
        array_shift($rows);

        // District code mapping
        $districtMapping = [
            '01' => 1, '02' => 2, '03' => 3, '04' => 4, '05' => 5, '06' => 6,
            '07' => 7, '08' => 8, '09' => 9, '10' => 10, '11' => 11, '12' => 12
        ];

        foreach ($rows as $row) {
            if (empty($row[1]) || empty($row[5])) continue; // Skip empty rows
            
            $mahallaName = trim($row[1]); // name column
            $districtCode = trim($row[5]); // district_code column
            
            if (isset($districtMapping[$districtCode])) {
                $districtId = $districtMapping[$districtCode];
                
                // Check if mahalla already exists
                $existingMahalla = Mahalla::where('district_id', $districtId)
                    ->where('name', $mahallaName)
                    ->first();
                
                if (!$existingMahalla) {
                    Mahalla::create([
                        'district_id' => $districtId,
                        'name' => $mahallaName,
                        'is_active' => true
                    ]);
                }
            }
        }
    }
}
