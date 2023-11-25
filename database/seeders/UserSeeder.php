<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                [
                    'name' =>'admin',
                    'email'=> 'admin@abc.com',
                    'email_verified_at' => now(),
                    'password'=> Hash::make('abc12345678'),
                    'gender'=>'M',
                    'role'=>'admin',
                    'created_at' => now(),
                ],
                [
                    'name' =>'manager1',
                    'email'=> 'manager1@abc.com',
                    'email_verified_at' =>now(),
                    'password'=> Hash::make('abc12345678'),
                    'gender'=>'M',
                    'role'=>'manager',
                    'created_at' => now(),
                ],
                [
                    'name' =>'user1',
                    'email'=> 'user1@abc.com',
                    'email_verified_at' =>now(),
                    'password'=> Hash::make('abc12345678'),
                    'gender'=>'M',
                    'role'=>'user',
                    'created_at' => now(),
                ]
            ]);
    }
}
