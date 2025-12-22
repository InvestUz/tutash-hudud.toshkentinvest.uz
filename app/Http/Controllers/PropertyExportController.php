<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PropertyExportController extends Controller
{
    public function exportToZip(Request $request)
    {
        // Validate date inputs
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        // Build query with filters
        $query = Property::with(['district', 'mahalla', 'street', 'creator']);

        // Apply date range filters
        if ($request->filled('date_from')) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($request->filled('date_to')) {
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            $query->where('created_at', '<=', $dateTo);
        }

        // Apply other filters
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('usage_purpose')) {
            $query->where('usage_purpose', $request->usage_purpose);
        }

        if ($request->filled('has_tenant')) {
            $query->where('has_tenant', $request->has_tenant);
        }

        // Check user permissions
        $user = auth()->user();
        if ($user->role === 'district_admin') {
            $query->where('district_id', $user->district_id);
        } elseif ($user->role === 'user') {
            $query->where('created_by', $user->id);
        }

        $properties = $query->get();

        // Check if there are properties to export
        if ($properties->isEmpty()) {
            return back()->with('error', 'Tanlangan davrda ma\'lumotlar topilmadi.');
        }

        // Prepare data for Excel
        $excelData = $this->prepareExcelData($properties);

        // Generate XLSX file with date range in filename
        $dateRange = '';
        if ($request->filled('date_from') || $request->filled('date_to')) {
            $from = $request->filled('date_from') ? date('Y-m-d', strtotime($request->date_from)) : 'start';
            $to = $request->filled('date_to') ? date('Y-m-d', strtotime($request->date_to)) : 'end';
            $dateRange = "_{$from}_to_{$to}";
        }

        $fileName = 'properties_export' . $dateRange . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/temp/' . $fileName);

        // Create temp directory if not exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        // Include the SimpleXLSXGen library
        require_once app_path('Libraries/SimpleXLSXGen.php');

        // Create Excel file
        $xlsx = \Shuchkin\SimpleXLSXGen::fromArray($excelData);
        $xlsx->saveAs($filePath);

        // Download and delete
        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    private function prepareExcelData($properties)
    {
        // Headers with bold styling
        $data = [[
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">ID</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Kadastr raqami</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">STIR/PINFL</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Korxona/F.I.SH</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Tuman</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Mahalla</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Ko\'cha</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Uy raqami</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Rahbar</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Telefon</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Uzunlik (m)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Kenglik (m)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Hisoblangan maydon (m²)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Umumiy maydon (m²)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Hisoblash usuli</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Fasad uzunligi (m)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Terassa tomonlari (m)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Yo\'lgacha masofa (m)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Trotuargacha masofa (m)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Foydalanish maqsadi</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Faoliyat turi</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Terrassada qurilmalar</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Doimiy qurilmalar</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Ruxsatnoma</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Shartnoma raqami</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Shartnoma sanasi</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Shartnoma summasi</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Akt fayli</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Loyiha kodi fayli</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Ijarachi bormi</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Ijarachi STIR/PINFL</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Ijarachi nomi</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Ijarachi faoliyat turi</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Tutash hudud faoliyati</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Tutash hudud maydoni</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Tutash hudud qurilmalari</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Qo\'shimcha ma\'lumot</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Kenglik (GPS)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Uzunlik (GPS)</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Yaratilgan sana</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Yangilangan sana</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Yaratuvchi</style>',
            '<style font-size="14" font-weight="bold" bgcolor="#4472C4" color="#FFFFFF">Назорат ҳолати</style>',
        ]];

        // Add data rows
        foreach ($properties as $property) {
            $data[] = [
                $property->id,
                $property->building_cadastr_number,
                $property->owner_stir_pinfl,
                $property->owner_name,
                $property->district->name ?? '',
                $property->mahalla->name ?? '',
                $property->street->name ?? '',
                $property->house_number,
                $property->director_name,
                $property->phone_number,
                $property->area_length,
                $property->area_width,
                $property->calculated_land_area,
                $property->total_area,
                $property->area_calculation_method,
                $property->building_facade_length,
                $property->summer_terrace_sides,
                $property->distance_to_roadway,
                $property->distance_to_sidewalk,
                $property->usage_purpose,
                $property->activity_type,
                $property->terrace_buildings_available,
                $property->terrace_buildings_permanent,
                $property->has_permit,
                $property->shartnoma_raqami ?? '',
                $property->shartnoma_sanasi ? Carbon::parse($property->shartnoma_sanasi)->format('d.m.Y') : '',
                $property->shartnoma_summasi ?? '',

                $property->act_file ? 'Mavjud' : 'Mavjud emas',
                $property->design_code_file ? 'Mavjud' : 'Mavjud emas',
                $property->has_tenant ? 'Ha' : "Yo'q",
                $property->tenant_stir_pinfl ?? '',
                $property->tenant_name ?? '',
                $property->tenant_activity_type ?? '',
                $property->adjacent_activity_type,
                $property->adjacent_activity_land,
                $property->getAdjacentFacilitiesTextAttribute(),
                $property->additional_info,
                $property->latitude,
                $property->longitude,
                $property->created_at->format('Y-m-d H:i:s'),
                $property->updated_at->format('Y-m-d H:i:s'),
                $property->creator->name ?? '',
                $property->needs_monitoring ? 'Назоратга олиш керак' : 'Назоратга олиш керак эмас'
            ];
        }

        return $data;
    }
}
