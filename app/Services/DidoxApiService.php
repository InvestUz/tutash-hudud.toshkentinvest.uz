<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DidoxApiService
{
    private $baseUrl;
    private $apiKey;
    private $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.didox.base_url', 'https://api-partners.didox.uz/v1');
        $this->apiKey = config('services.didox.api_key');
        $this->timeout = config('services.didox.timeout', 30);
    }

    /**
     * Validate individual (physical person) by PINFL
     * FIXED: Uses same endpoint as legal entity - GET /profile/{pinfl}
     */
    public function validateIndividual(string $pinfl): array
    {
        try {
            // FIXED: Use same endpoint pattern as legal entity
            $url = "{$this->baseUrl}/profile/{$pinfl}";

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Accept' => 'application/json'
                ])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'data' => $data,
                    'is_individual' => true,
                    'name' => $data['fullName'] ?? $data['name'] ?? '',
                    'address' => $data['address'] ?? '',
                    'region_id' => $data['regionId'] ?? '',
                    'district_id' => $data['districtId'] ?? '',
                    'is_active' => isset($data['statusCode']) ? $data['statusCode'] == 0 : true,
                    'passport' => $data['passport'] ?? '',
                    'pinfl' => $data['personalNum'] ?? $pinfl
                ];
            }

            Log::warning('Didox API error for individual', [
                'pinfl' => $pinfl,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'API request failed',
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Didox API exception for individual', [
                'pinfl' => $pinfl,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate legal entity (company) by TIN
     * Uses GET method with TIN in URL path
     */
    public function validateLegalEntity(string $tin): array
    {
        try {
            $url = "{$this->baseUrl}/profile/{$tin}";

            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Accept' => 'application/json'
                ])
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'data' => $data,
                    'is_individual' => false,
                    'name' => $data['name'] ?? $data['shortName'] ?? '',
                    'short_name' => $data['shortName'] ?? '',
                    'full_name' => $data['name'] ?? '',
                    'tin' => $data['tin'] ?? '',
                    'address' => $data['address'] ?? '',
                    'director' => $data['director'] ?? '',
                    'director_tin' => $data['directorTin'] ?? '',
                    'director_pinfl' => $data['directorPinfl'] ?? '',
                    'accountant' => $data['accountant'] ?? '',
                    'region_id' => $data['regionId'] ?? '',
                    'district_id' => $data['districtId'] ?? '',
                    'is_active' => isset($data['statusCode']) ? $data['statusCode'] == 0 : true,
                    'registration_date' => $data['regDate'] ?? '',
                    'oked' => $data['oked'] ?? '',
                    'vat_registered' => $data['vat'] ?? false
                ];
            }

            Log::warning('Didox API error for legal entity', [
                'tin' => $tin,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'API request failed',
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('Didox API exception for legal entity', [
                'tin' => $tin,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Auto-detect and validate STIR/PINFL
     * Both use the same endpoint: GET /profile/{number}
     */
    // public function validateStirPinfl(string $stirPinfl): array
    // {
    //     $cleaned = preg_replace('/[^0-9]/', '', $stirPinfl);

    //     if (strlen($cleaned) === 14) {
    //         // Individual (PINFL) - 14 digits
    //         return $this->validateIndividual($cleaned);
    //     } elseif (strlen($cleaned) === 9) {
    //         // Legal entity (TIN) - 9 digits
    //         return $this->validateLegalEntity($cleaned);
    //     }

    //     return [
    //         'success' => false,
    //         'error' => 'Invalid STIR/PINFL format. Must be 9 digits (TIN) or 14 digits (PINFL)',
    //         'provided_length' => strlen($cleaned)
    //     ];
    // }

    public function validateStirPinfl(string $stirPinfl): array
    {
        // Trim whitespace and remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', trim($stirPinfl));

        // Return early if empty
        if (empty($cleaned)) {
            return [
                'success' => false,
                'error' => 'STIR/PINFL bo\'sh bo\'lmasligi kerak',
                'provided_length' => 0
            ];
        }

        if (strlen($cleaned) === 14) {
            // Individual (PINFL) - 14 digits
            return $this->validateIndividual($cleaned);
        } elseif (strlen($cleaned) === 9) {
            // Legal entity (TIN) - 9 digits
            return $this->validateLegalEntity($cleaned);
        }

        return [
            'success' => false,
            'error' => 'Noto\'g\'ri STIR/PINFL formati. 9 raqam (STIR) yoki 14 raqam (PINFL) bo\'lishi kerak. Kiritilgan: ' . strlen($cleaned) . ' ta raqam',
            'provided_length' => strlen($cleaned)
        ];
    }

    /**
     * Check if API is available
     */
    public function checkApiStatus(): bool
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Accept' => 'application/json'
                ])
                ->get("{$this->baseUrl}/health");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Didox API health check failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
