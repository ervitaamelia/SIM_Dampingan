<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Provinsi;

class ProvinsiSeeder extends Seeder
{
    public function run()
    {
        $url = "https://wilayah.id/api/provinces.json";

        // Ambil data dari API
        $response = Http::get($url);
        $data = $response->json();

        if (!isset($data['data'])) {
            $this->command->error("Gagal mengambil data provinsi dari API.");
            return;
        }

        // Loop data provinsi dan masukkan ke database
        foreach ($data['data'] as $provinsi) {
            Provinsi::updateOrCreate(
                ['kode' => $provinsi['code']],  // 'code' dari API
                ['nama' => $provinsi['name']]  // 'name' dari API
            );
        }

        $this->command->info("Data provinsi berhasil diimport.");
    }
}
