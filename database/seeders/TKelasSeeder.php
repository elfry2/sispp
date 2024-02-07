<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tableName = 't_kelas';

        $rows = [
            [
                'nm_kelas' => '1 TKJ 1',
                'jumlah_siswa' => 40,
            ],
            [
                'nm_kelas' => '2 TKJ 1',
                'jumlah_siswa' => 40,
            ],
            [
                'nm_kelas' => '3 TKJ 1',
                'jumlah_siswa' => 40,
            ],
        ];

        foreach($rows as $row)
            DB::table($tableName)->insert($row);
    }
}
