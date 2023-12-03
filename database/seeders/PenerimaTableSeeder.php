<?php

namespace Database\Seeders;
use App\Models\Penerima;
use Illuminate\Database\Seeder;

class PenerimaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $penerimas = array(
            [
                'nama_penerima' => 'arip',
                'alamat_penerima' => 'bandung',
                'telepon_penerima' => '08212182112'
                
            ],
            [
                'nama_penerima' => 'yudi',
                'alamat_penerima' => 'bandung',
                'telepon_penerima' => '081239123789'
            ]
        );

        array_map(function (array $penerima) {
            Penerima::query()->updateOrCreate(
                $penerima
            );
        }, $penerimas);
    }
}
