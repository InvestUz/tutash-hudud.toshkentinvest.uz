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

        foreach ($data as $row) {
            if (count($row) >= 6 && !empty($row[1]) && !empty($row[5])) {
                $streetName = trim($row[1]); // Street name column
                $fullCode = trim($row[5]); // Full code column
                
                // Extract district code from full code (first 2 characters)
                $districtCode = substr($fullCode, 0, 2);
                
                if (isset($districtMapping[$districtCode])) {
                    $districtId = $districtMapping[$districtCode];
                    
                    // Find first available mahalla in this district
                    $mahalla = Mahalla::where('district_id', $districtId)->first();
                    
                    if ($mahalla) {
                        Street::firstOrCreate(
                            [
                                'mahalla_id' => $mahalla->id,
                                'name' => $streetName
                            ],
                            ['is_active' => true]
                        );
                    }
                }
            }
        }
        
        $this->command->info('Streets seeded: ' . Street::count());
    }

    /**
     * Read XLSX file without external packages
     * XLSX files are ZIP archives containing XML files
     */
    private function readXLSX($filePath): array
    {
        $data = [];
        
        try {
            $zip = new ZipArchive;
            
            if ($zip->open($filePath) === TRUE) {
                // Read the main worksheet data (usually xl/worksheets/sheet1.xml)
                $xmlString = $zip->getFromName('xl/worksheets/sheet1.xml');
                
                if ($xmlString === false) {
                    $this->command->error("Could not read worksheet data from XLSX");
                    $zip->close();
                    return [];
                }

                // Read shared strings (xl/sharedStrings.xml) for string values
                $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
                $sharedStrings = [];
                
                if ($sharedStringsXml !== false) {
                    $sharedStrings = $this->parseSharedStrings($sharedStringsXml);
                }

                $zip->close();

                // Parse the worksheet XML
                $data = $this->parseWorksheetXML($xmlString, $sharedStrings);
                
            } else {
                $this->command->error("Could not open XLSX file: {$filePath}");
            }
            
        } catch (\Exception $e) {
            $this->command->error("Error reading XLSX file: " . $e->getMessage());
        }

        return $data;
    }

    /**
     * Parse shared strings XML to get actual string values
     */
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

    /**
     * Parse worksheet XML and extract cell data
     */
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
                
                foreach ($cells as $cell) {
                    $cellValue = '';
                    $cellType = $cell->getAttribute('t');
                    
                    $valueNodes = $cell->getElementsByTagName('v');
                    if ($valueNodes->length > 0) {
                        $value = $valueNodes->item(0)->nodeValue;
                        
                        // If cell type is 's' (shared string), get value from shared strings array
                        if ($cellType === 's' && isset($sharedStrings[$value])) {
                            $cellValue = $sharedStrings[$value];
                        } else {
                            $cellValue = $value;
                        }
                    }
                    
                    $rowData[] = $cellValue;
                }
                
                // Only add non-empty rows
                if (!empty(array_filter($rowData))) {
                    $data[] = $rowData;
                }
            }
            
        } catch (\Exception $e) {
            $this->command->error("Error parsing worksheet XML: " . $e->getMessage());
        }

        return $data;
    }
}
