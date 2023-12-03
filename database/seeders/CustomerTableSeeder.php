<?php

namespace Database\Seeders;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = array(
            [
                'nama_customer' => 'arip',
                'alamat_customer' => 'bandung',
                'telepon_customer' => '08212182112'
                
            ],
            [
                'nama_customer' => 'yudi',
                'alamat_customer' => 'bandung',
                'telepon_customer' => '081239123789'
            ]
        );

        array_map(function (array $customer) {
            Customer::query()->updateOrCreate(
                $customer
            );
        }, $customers);
    }
}
