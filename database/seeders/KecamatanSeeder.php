<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Kecamatan;
use App\Models\Kabupaten;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua kabupaten yang ada di database
        $kabupatens = Kabupaten::all();

        foreach ($kabupatens as $kabupaten) {
            $url = "https://wilayah.id/api/districts/{$kabupaten->kode}.json";
            
            try {
                // Ambil data kecamatan dari API
                $response = Http::get($url);
                $data = $response->json();

                if (!isset($data['data'])) {
                    Log::error("Gagal mengambil data kecamatan untuk kode kabupaten: {$kabupaten->kode}");
                    continue;
                }

                foreach ($data['data'] as $kecamatan) {
                    Kecamatan::updateOrCreate(
                        ['kode' => $kecamatan['code']],
                        [
                            'nama' => $kecamatan['name'],
                            'kode_kabupaten' => $kabupaten->kode,
                        ]
                    );
                }

                $this->command->info("Data kecamatan untuk kabupaten {$kabupaten->nama} berhasil disimpan.");
            } catch (\Exception $e) {
                Log::error("Error mengambil data dari API: " . $e->getMessage());
            }
        }
    }
}

