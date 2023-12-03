<?php

namespace Database\Seeders;
use App\Models\Harga;
use Illuminate\Database\Seeder;

class HargaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hargas = array(
            [
                'nama_customer' => 'arip',
                'alamat_customer' => 'bandung',
                'nama_penerima' => 'yudi',
                'alamat_penerima' => 'garut',
                'harga' => '100000'
                
            ],
            [
                'nama_customer' => 'yudi',
                'alamat_customer' => 'bandung',
                'nama_penerima' => 'arip',
                'alamat_penerima' => 'garut',
                
                'harga' => '200000'
            ]
        );

        array_map(function (array $harga) {
            Harga::query()->updateOrCreate(
                $harga
            );
        }, $hargas);
    }
}
