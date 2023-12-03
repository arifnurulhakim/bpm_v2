<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Daftar_muat;
class DaftarMuatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $daftar_muats = array(
            [
                'kode_daftar_muat' => '100',
                'nomor_sa' => '111',
                'supir' => 'mustofa',
                'no_mobil' => 'D 123 DD',
                'nama_customer' => 'arip',
                'nama_penerima' => 'yudi',
                'jumlah_barang' => '10',
                'berat_barang' => '10',
                'harga' => '100000',
                'total_harga' => '10000000',

            ],
            [
                'kode_daftar_muat' => '100',
                'nomor_sa' => '112',
                'supir' => 'mustofa',
                'no_mobil' => 'D 123 DD',
                'nama_customer' => 'arip',
                'nama_penerima' => 'yudi',
                'jumlah_barang' => '10',
                'berat_barang' => '10',
                'harga' => '100000',
                'total_harga' => '10000000',

            ]
        );

        array_map(function (array $daftar_muat) {
            Daftar_muat::query()->updateOrCreate(
                $daftar_muat
            );
        }, $daftar_muats);
    }
}
