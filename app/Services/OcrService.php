<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OcrService
{
    /**
     * Mengekstrak data ijazah dari gambar menggunakan Gemini API.
     * 
     * @param string $filePath Absolute path ke file gambar.
     * @param string $mimeType Mime type (image/jpeg, image/png, application/pdf)
     * @return array|null Mengembalikan array data terstruktur atau null jika gagal.
     */
    public function extractData(string $filePath, string $mimeType): ?array
    {
        $apiKey = config('services.gemini.api_key');
        
        if (empty($apiKey)) {
            Log::error('OCR Service: GEMINI_API_KEY is not set.');
            return null;
        }

        // Gemini Vision API endpoint (dikembalikan ke gemini-flash-latest sesuai dokumentasi terbaru)
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}";

        // Baca isi file dan konversi ke base64
        $fileContent = file_get_contents($filePath);
        $base64Data = base64_encode($fileContent);

        // Jika PDF, kita perlu perlakuan khusus atau pastikan gemini-1.5-flash mendukungnya
        // Gemini 1.5 Flash mensupport application/pdf secara native.
        
        $prompt = "Tolong ekstrak data dari dokumen ijazah berikut. Kembalikan HANYA dalam format JSON tulen tanpa backticks atau format markdown tambahan. Struktur JSON harus memiliki key berikut dan valuenya berupa string:
        - nama (Kepada/Nama lulusan)
        - nim (NIM / Tahun Masuk, tulis persis seperti di ijazah misalnya '210511011 / 2021')
        - nik (NIK, jika tidak ada kosongkan)
        - tempat_tanggal_lahir (Tempat, tanggal lahir persis seperti dokumen, contoh: 'Cirebon, 23 September 2002')
        - nama_institusi (Nama universitas/institusi penerbit ijazah)
        - fakultas
        - prodi (Program Studi)
        - gelar (Gelar akademik, misalnya 'Sarjana Teknik (S.T.)')
        - nomor_ijazah (Nomor Seri Ijazah)
        - tanggal_lulus (Cari tanggal kelulusan di kalimat 'Telah dinyatakan lulus tanggal...', ubah ke format YYYY-MM-DD. Contoh 10 September 2025 -> 2025-09-10)
        - tanggal_diberikan (Cari tanggal ijazah diberikan di kalimat 'Diberikan di ... pada tanggal...', ubah ke format YYYY-MM-DD. Contoh 25 Oktober 2025 -> 2025-10-25)";

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inlineData' => [
                                'mimeType' => $mimeType,
                                'data' => $base64Data
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.1, // Rendah agar lebih akurat
                'responseMimeType' => 'application/json',
            ]
        ];

        try {
            // Tambahkan Retry 3x, jeda 2 detik jika terjadi RTO atau Limit (429) / Internal Error (500)
            $response = Http::timeout(60)
                ->retry(3, 2000, function ($exception, $request) {
                    return $exception instanceof \Illuminate\Http\Client\ConnectionException ||
                           ($exception instanceof \Illuminate\Http\Client\RequestException && $exception->response->serverError()) ||
                           ($exception instanceof \Illuminate\Http\Client\RequestException && $exception->response->status() === 429);
                })
                ->post($url, $payload);
            
            if ($response->successful()) {
                $jsonResult = $response->json();
                $extractedText = $jsonResult['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                // Pastikan membersihkan output jika model membandel memberi markdown
                $extractedText = trim($extractedText);
                $extractedText = str_replace(['```json', '```'], '', $extractedText);
                
                return json_decode(trim($extractedText), true);
            }
            
            Log::error('OCR Service API Error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('OCR Service Exception: ' . $e->getMessage());
        }

        return null;
    }
}
