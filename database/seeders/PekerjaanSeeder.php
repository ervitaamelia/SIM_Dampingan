<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pekerjaan;

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pekerjaans = [
            ['nama_pekerjaan' => 'Petani'],
            ['nama_pekerjaan' => 'Peternak'],
            ['nama_pekerjaan' => 'Nelayan'],
            ['nama_pekerjaan' => 'Buruh Pabrik'],
            ['nama_pekerjaan' => 'Buruh Tani'],
            ['nama_pekerjaan' => 'Wirausaha'],
            ['nama_pekerjaan' => 'Pedagang'],
            ['nama_pekerjaan' => 'Karyawan Swasta'],
            ['nama_pekerjaan' => 'PNS'],
            ['nama_pekerjaan' => 'TNI/Polri'],
            ['nama_pekerjaan' => 'Guru/Dosen'],
            ['nama_pekerjaan' => 'Pelajar/Mahasiswa'],
            ['nama_pekerjaan' => 'Lainnya'],
        ];

        foreach ($pekerjaans as $pekerjaan) {
            Pekerjaan::create($pekerjaan);
        }
    }
}