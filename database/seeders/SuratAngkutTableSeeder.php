<?php

namespace Database\Seeders;
use App\Models\Surat_angkut;
use Illuminate\Database\Seeder;

class SuratAngkutTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $surat_angkuts = array(
            [
                'nomor_sa' => '111',
                'kode_tanda_penerima' => '111',
                'nama_customer' => 'arip',
                'alamat_customer' => 'bandung',
                'telepon_customer' => '082118114645',
                'nama_barang' => 'kain',
                'jumlah_barang' => '10',
                'berat_barang' => '10',
                'nama_penerima' => 'yudi',
                'alamat_penerima' => 'garut',
                'telepon_penerima' => '082118114645',
                'supir' => 'mustofa',
                'no_mobil' => 'D 123 DD',
                'keterangan' => '',
                'harga' => '100000'
            ],
            [
                'nomor_sa' => '112',
                'kode_tanda_penerima' => '112',
                'nama_customer' => 'arip',
                'alamat_customer' => 'bandung',
                'telepon_customer' => '082118114645',
                'nama_barang' => 'frozenfood',
                'jumlah_barang' => '20',
                'berat_barang' => '5',
                'nama_penerima' => 'yudi',
                'alamat_penerima' => 'garut',
                'telepon_penerima' => '082118114645',
                'supir' => 'mustofa',
                'no_mobil' => 'D 123 DD',
                'keterangan' => '',
                'harga' => '100000'
            ]
        );

        array_map(function (array $surat_angkut) {
            Surat_angkut::query()->updateOrCreate(
                $surat_angkut
            );
        }, $surat_angkuts);
    }
}
