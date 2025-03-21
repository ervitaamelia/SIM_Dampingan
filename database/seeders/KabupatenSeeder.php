<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Kabupaten;
use App\Models\Provinsi;

class KabupatenSeeder extends Seeder
{
    public function run()
    {
        $provinsiList = Provinsi::all(); // Ambil semua provinsi di database

        foreach ($provinsiList as $provinsi) {
            $url = "https://wilayah.id/api/regencies/{$provinsi->kode}.json";

            // Ambil data dari API
            $response = Http::get($url);
            $data = $response->json();

            if (!isset($data['data'])) {
                $this->command->error("Gagal mengambil data kabupaten untuk Provinsi: {$provinsi->nama}");
                continue;
            }

            // Loop data kabupaten dan masukkan ke database
            foreach ($data['data'] as $kabupaten) {
                Kabupaten::updateOrCreate(
                    ['kode' => $kabupaten['code']],  // 'code' dari API
                    [
                        'nama' => $kabupaten['name'],  // 'name' dari API
                        'kode_provinsi' => $provinsi->kode
                    ]
                );
            }

            $this->command->info("Data kabupaten untuk {$provinsi->nama} berhasil diimport.");
        }
    }
}
