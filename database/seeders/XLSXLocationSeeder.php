<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Mahalla;
use App\Models\Street;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use ZipArchive;
use DOMDocument;

class XLSXLocationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Reading XLSX files and seeding data...');

        $this->seedDistricts();
        $this->seedMahallasFromXLSX();
        $this->seedStreetsFromXLSX();

        $this->command->info('XLSX seeding completed!');
    }

    private function seedDistricts(): void
    {
        $xlsxFile = public_path('Street_datas/_tumanlar.xlsx');

        if (!file_exists($xlsxFile)) {
            $this->command->error("Districts XLSX file not found: {$xlsxFile}");
            return;
        }

        $data = $this->readXLSX($xlsxFile);

        if (empty($data)) {
            $this->command->error("Could not read districts data from XLSX");
            return;
        }

        // Skip header row
        array_shift($data);

        foreach ($data as $row) {
            if (count($row) >= 2 && !empty($row[1])) {
                $districtName = trim($row[1]); // name_uz column

                District::firstOrCreate(
                    ['name' => $districtName],
                    [
                        'slug' => Str::slug($districtName),
                        'is_active' => true
                    ]
                );
            }
        }

        $this->command->info('Districts seeded: ' . District::count());
    }

    private function seedMahallasFromXLSX(): void
    {
        $xlsxFile = public_path('Street_datas/_mahallalar.xlsx');

        if (!file_exists($xlsxFile)) {
            $this->command->error("Mahallas XLSX file not found: {$xlsxFile}");
            return;
        }

        $data = $this->readXLSX($xlsxFile);

        if (empty($data)) {
            $this->command->error("Could not read mahallas data from XLSX");
            return;
        }

        // Skip header row
        array_shift($data);

        // District code mapping
        $districtMapping = [
            '01' => 1, '02' => 2, '03' => 3, '04' => 4, '05' => 5, '06' => 6,
            '07' => 7, '08' => 8, '09' => 9, '10' => 10, '11' => 11, '12' => 12
        ];

        foreach ($data as $row) {
            if (count($row) >= 6 && !empty($row[1]) && !empty($row[5])) {
                $mahallaName = trim($row[1]); // name column
                $districtCode = trim($row[5]); // district_code column

                if (isset($districtMapping[$districtCode])) {
                    $districtId = $districtMapping[$districtCode];

                    Mahalla::firstOrCreate(
                        [
                            'district_id' => $districtId,
                            'name' => $mahallaName
                        ],
                        ['is_active' => true]
                    );
                }
            }
        }

        $this->command->info('Mahallas seeded: ' . Mahalla::count());
    }

    private function seedStreetsFromXLSX(): void
    {
        $xlsxFile = public_path('Street_datas/_kochalar.xlsx');

        if (!file_exists($xlsxFile)) {
            $this->command->error("Streets XLSX file not found: {$xlsxFile}");
            return;
        }

        $data = $this->readXLSX($xlsxFile);

        if (empty($data)) {
            $this->command->error("Could not read streets data from XLSX");
            return;
        }

        // Skip header row
        array_shift($data);

        // District code mapping
        $districtMapping = [
            '01' => 1, '02' => 2, '03' => 3, '04' => 4, '05' => 5, '06' => 6,
            '07' => 7, '08' => 8, '09' => 9, '10' => 10, '11' => 11, '12' => 12
        ];

        $streetsProcessed = 0;
        $streetsSkipped = 0;

        foreach ($data as $rowIndex => $row) {
            // FIXED: Check if we have enough columns and required data
            if (count($row) >= 7 && !empty($row[1]) && !empty($row[6])) {
                $streetName = trim($row[1]); // name column (index 1)
                $districtCode = trim($row[6]); // district_code column (index 6 - last column)

                $this->command->info("Processing row {$rowIndex}: Street '{$streetName}' with district code '{$districtCode}'");

                // FIXED: Use the district_code directly from column 6
                if (isset($districtMapping[$districtCode])) {
                    $districtId = $districtMapping[$districtCode];

                    // Skip empty or invalid street names
                    if ($streetName === '----' || empty($streetName)) {
                        $streetsSkipped++;
                        continue;
                    }

                    // Create street with district_id
                    $street = Street::firstOrCreate([
                        'district_id' => $districtId,
                        'name' => $streetName
                    ], [
                        'is_active' => true
                    ]);

                    if ($street->wasRecentlyCreated) {
                        $streetsProcessed++;
                        $this->command->info("Created street: '{$streetName}' in district {$districtId}");
                    } else {
                        $this->command->info("Street already exists: '{$streetName}' in district {$districtId}");
                    }
                } else {
                    $this->command->warn("Unknown district code: '{$districtCode}' for street '{$streetName}'");
                    $streetsSkipped++;
                }
            } else {
                $this->command->warn("Skipping row {$rowIndex}: insufficient data or empty required fields");
                $streetsSkipped++;
            }
        }

        $this->command->info("Streets processing completed:");
        $this->command->info("- Processed: {$streetsProcessed}");
        $this->command->info("- Skipped: {$streetsSkipped}");
        $this->command->info("- Total streets in DB: " . Street::count());
        
        // Show distribution by district
        $this->command->info("Streets distribution by district:");
        $distribution = Street::selectRaw('district_id, COUNT(*) as count')
            ->groupBy('district_id')
            ->orderBy('district_id')
            ->get();
            
        foreach ($distribution as $dist) {
            $this->command->info("District {$dist->district_id}: {$dist->count} streets");
        }
    }

    private function readXLSX($filePath): array
    {
        $data = [];

        try {
            $zip = new ZipArchive;

            if ($zip->open($filePath) === TRUE) {
                $xmlString = $zip->getFromName('xl/worksheets/sheet1.xml');

                if ($xmlString === false) {
                    $this->command->error("Could not read worksheet data from XLSX");
                    $zip->close();
                    return [];
                }

                $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
                $sharedStrings = [];

                if ($sharedStringsXml !== false) {
                    $sharedStrings = $this->parseSharedStrings($sharedStringsXml);
                }

                $zip->close();
                $data = $this->parseWorksheetXML($xmlString, $sharedStrings);
            } else {
                $this->command->error("Could not open XLSX file: {$filePath}");
            }
        } catch (\Exception $e) {
            $this->command->error("Error reading XLSX file: " . $e->getMessage());
        }

        return $data;
    }

    private function parseSharedStrings($xmlString): array
    {
        $sharedStrings = [];

        try {
            $dom = new DOMDocument();
            $dom->loadXML($xmlString);
            $elements = $dom->getElementsByTagName('t');

            foreach ($elements as $element) {
                $sharedStrings[] = $element->nodeValue;
            }
        } catch (\Exception $e) {
            $this->command->error("Error parsing shared strings: " . $e->getMessage());
        }

        return $sharedStrings;
    }

    private function parseWorksheetXML($xmlString, $sharedStrings): array
    {
        $data = [];

        try {
            $dom = new DOMDocument();
            $dom->loadXML($xmlString);
            $rows = $dom->getElementsByTagName('row');

            foreach ($rows as $row) {
                $rowData = [];
                $cells = $row->getElementsByTagName('c');

                // Initialize array with empty values to maintain column positions
                $maxColumns = 10; // Adjust based on your data
                for ($i = 0; $i < $maxColumns; $i++) {
                    $rowData[$i] = '';
                }

                foreach ($cells as $cell) {
                    $cellValue = '';
                    $cellType = $cell->getAttribute('t');
                    $cellRef = $cell->getAttribute('r'); // Cell reference like A1, B1, etc.
                    
                    // Extract column index from cell reference
                    preg_match('/([A-Z]+)/', $cellRef, $matches);
                    if (isset($matches[1])) {
                        $columnIndex = $this->columnLetterToIndex($matches[1]);
                    } else {
                        continue;
                    }
                    
                    $valueNodes = $cell->getElementsByTagName('v');
                    
                    if ($valueNodes->length > 0) {
                        $value = $valueNodes->item(0)->nodeValue;

                        if ($cellType === 's' && isset($sharedStrings[$value])) {
                            $cellValue = $sharedStrings[$value];
                        } else {
                            $cellValue = $value;
                        }
                    }

                    $rowData[$columnIndex] = $cellValue;
                }

                // Only add non-empty rows
                if (!empty(array_filter($rowData))) {
                    $data[] = array_values($rowData); // Reset array keys
                }
            }
        } catch (\Exception $e) {
            $this->command->error("Error parsing worksheet XML: " . $e->getMessage());
        }

        return $data;
    }

    /**
     * Convert Excel column letter to numeric index (A=0, B=1, etc.)
     */
    private function columnLetterToIndex($letters): int
    {
        $index = 0;
        $letters = strtoupper($letters);
        
        for ($i = 0; $i < strlen($letters); $i++) {
            $index = $index * 26 + (ord($letters[$i]) - ord('A') + 1);
        }
        
        return $index - 1; // Convert to 0-based index
    }
}