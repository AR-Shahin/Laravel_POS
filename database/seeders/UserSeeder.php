<?php

namespace Database\Seeders;

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
        DB::table('users')->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '852',
            'image' => 'admin@gmail.com',
            'status' => 1,
            'password' => Hash::make('123'),
        ]);
    }
}
