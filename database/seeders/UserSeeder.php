<?php

namespace Database\Seeders;

use App\Models\User;
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
     User::create([
         'name' => 'ars',
         'email' =>'ars@gmail.com',
         'password' => Hash::make(123),
         'phone' => 55,
         'status' => 1,
         'image' => 'dd'
     ]);
    }
}
