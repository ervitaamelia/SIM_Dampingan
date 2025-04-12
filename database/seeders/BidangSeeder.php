<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bidang;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidangs = [
            ['nama_bidang' => 'Pertanian Terpadu'],
            ['nama_bidang' => 'Nelayan'],
            ['nama_bidang' => 'Buruh'],
            ['nama_bidang' => 'Difabel'],
            ['nama_bidang' => 'Perekonomian'],
            ['nama_bidang' => 'Daerah 3T'],
        ];

        // Insert data ke dalam tabel bidang
        foreach ($bidangs as $bidang) {
            Bidang::create($bidang);
        }
    }
}
