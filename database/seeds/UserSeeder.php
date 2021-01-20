<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
                   'name'=>'admin',
                   'email'=>'pltruenine@163.com',
                   'password'=>Hash::make('123456'),
                   'created_at'=>now()->toDate(),
                   'updated_at'=>now()->toDate(),
       ]);
    }
}
