<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class salesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sales')->insert([
            [
                'date' => '2023-01-01',
                'product_name' => 'TV',
                'model_name' => 'HD-flat',
                'qty' => '10',
                'rate' => '10000',
                'amount' => '100000',
                'saleby' => 'Ramesh',
            ],
            [
                'date' => '2023-02-04',
                'product_name' => 'TV',
                'model_name' => 'HD-ultra',
                'qty' => '8 ',
                'rate' => '15000',
                'amount' => '900000',
                'saleby' => 'Ramesh',
            ],
            [
                'date' => '2023-03-05',
                'product_name' => 'OVEN',
                'model_name' => 'Nonstic',
                'qty' => '5',
                'rate' => '5000',
                'amount' => '250000',
                'saleby' => 'Ramesh',
            ],
            [
                'date' => '2023-06-02',
                'product_name' => 'Distv',
                'model_name' => 'tatasky',
                'qty' => '2',
                'rate' => '2000',
                'amount' => '4000',
                'saleby' => 'Ramesh',
            ],
            [
                'date' => '2023-10-15',                
                'product_name' => 'Friz',
                'model_name' => 'Ice Magic',
                'qty' => '10',
                'rate' => '12000',
                'amount' => '1200000',
                'saleby' => 'Suresh',
            ],
            [
                'date' => '2023-08-25',
                'product_name' => 'TV',
                'model_name' => 'HD-plasma',
                'qty' => '7',
                'rate' => '15000',
                'amount' => '1050000',
                'saleby' => 'Suresh',
            ],
            [
                'date' => '2023-06-11',
                'product_name' => 'Friz',
                'model_name' => 'Videocon',
                'qty' => '5',
                'rate' => '25000',
                'amount' => '125000',
                'saleby' => 'Suresh',
            ],
            [
                'date' => '2023-08-14',
                'product_name' => 'TV',
                'model_name' => 'HD-plasma',
                'qty' => '7',
                'rate' => '15000',
                'amount' => '1050000',
                'saleby' => 'Suresh',
            ],
            [
                'date' => '2023-05-04',
                'product_name' => 'TV',
                'model_name' => 'HD-plasma',
                'qty' => '7',
                'rate' => '15000',
                'amount' => '1050000',
                'saleby' => 'Suresh',
            ]
        ]);
    }
}
