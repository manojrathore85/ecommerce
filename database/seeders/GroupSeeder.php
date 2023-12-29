<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            [
                'name' => 'Direct Income',
                'parent_id' => 0,
                'nature' => 'Income',
                'order' =>0,
                'created_at' => now(),
            ],
            [
                'name' => 'Direct Expences',
                'parent_id' => 0,
                'nature' => 'Expences',
                'order' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'Indirect Income',
                'parent_id' => 0,
                'nature' => 'Income',
                'order' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'Indirect Expences',
                'parent_id' => 0,
                'nature' => 'Expences',
                'order' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'Fixed Assets',
                'parent_id' => 0,
                'nature' => 'Assets',
                'order' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'Current Assets',
                'parent_id' => 0,
                'nature' => 'Assets',
                'order' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'Current Libilities',
                'parent_id' => 5,
                'nature' => 'Assets',
                'order' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'Sundry Debetors',
                'parent_id' => 0,
                'nature' => 'Assets',
                'order' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'Sundry Creditors',
                'parent_id' => 6,
                'nature' => 'Libilities',
                'order' => 0,
                'created_at' => now(),
            ]
            
            ]);
    }
}
