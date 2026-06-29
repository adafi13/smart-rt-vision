<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KkExtractor
{
    /**
     * Mengirim gambar KK ke Gemini dan mengembalikan JSON terstruktur.
     *
     * @param string $imagePath Path absolut ke file gambar
     * @return array|null
     */
    public function extract(string $imagePath): ?array
    {
        $apiKey = config('services.gemini.api_key');
        
        if (empty($apiKey)) {
            Log::error('Gemini API key is not set.');
            return null;
        }

        if (!file_exists($imagePath)) {
            Log::error("File not found: {$imagePath}");
            return null;
        }

        $imageData = base64_encode(file_get_contents($imagePath));
        $mimeType = mime_content_type($imagePath) ?: 'image/jpeg';

        $prompt = <<<EOT
Anda adalah sistem ekstraksi data otomatis untuk Kartu Keluarga (KK) Indonesia.
Tugas Anda adalah membaca gambar Kartu Keluarga yang dilampirkan dan mengembalikan hasilnya secara KETAT dalam format JSON yang telah ditentukan.

PERATURAN PENTING:
1. JANGAN berikan teks pengantar, penutup, atau penjelasan apa pun.
2. JANGAN gunakan markdown fence (seperti ```json ... ```). Langsung outputkan objek JSON.
3. Struktur JSON harus persis seperti berikut ini, jangan menambah atau mengurangi properti:

{
  "nomor_kk": "string 16 digit",
  "alamat": "string",
  "rt": "string",
  "rw": "string",
  "desa_kelurahan": "string",
  "kecamatan": "string",
  "kabupaten_kota": "string",
  "provinsi": "string",
  "kode_pos": "string",
  "anggota": [
    {
      "nik": "string 16 digit",
      "nama": "string",
      "jenis_kelamin": "Laki-laki | Perempuan",
      "tempat_lahir": "string",
      "tanggal_lahir": "YYYY-MM-DD",
      "agama": "string",
      "pendidikan": "string",
      "pekerjaan": "string",
      "status_perkawinan": "string",
      "hubungan_keluarga": "string",
      "kewarganegaraan": "string",
      "nama_ayah": "string",
      "nama_ibu": "string"
    }
  ]
}

4. Jika gambar yang diunggah BUKAN dokumen Kartu Keluarga (KK) Indonesia, atau gambarnya terlalu buram/gelap/terpotong sehingga data tidak bisa dibaca sama sekali, Anda WAJIB mengembalikan JSON error berikut (JANGAN kembalikan struktur di atas):
{
  "error": "Gambar tidak terdeteksi sebagai Kartu Keluarga atau terlalu buram untuk dibaca oleh AI. Pastikan foto dokumen KK jelas, terang, dan tidak terpotong."
}

Jika ada bagian kecil yang tidak terbaca, gunakan string kosong ("") namun tetap kembalikan struktur data KK. Hanya gunakan objek "error" jika keseluruhan dokumen tidak terbaca atau bukan KK. Pastikan array anggota memuat seluruh anggota keluarga yang terdaftar.
EOT;

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $imageData
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
            ]
        ];

        $models = ['gemini-flash-latest', 'gemini-3.5-flash', 'gemini-2.5-flash', 'gemini-2.0-flash'];
        
        foreach ($models as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            try {
                $response = Http::timeout(60)->post($url, $payload);

                if ($response->failed()) {
                    Log::warning("Gemini API request failed for model {$model}: " . $response->body());
                    continue; // Coba model berikutnya
                }

                $data = $response->json();
                $textResult = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

                $json = json_decode($textResult, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $textResult = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $textResult);
                    $json = json_decode($textResult, true);
                    
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        Log::error("Gagal mem-parsing JSON dari Gemini ({$model}): " . json_last_error_msg());
                        return null; // Jika parsing gagal, jangan coba model lain
                    }
                }

                return $json; // Sukses, kembalikan hasil JSON

            } catch (\Exception $e) {
                Log::warning("Error calling Gemini ({$model}): " . $e->getMessage());
                continue; // Coba model berikutnya
            }
        }
        
        Log::error("Semua model Gemini gagal diakses.");
        return null;
    }
}
