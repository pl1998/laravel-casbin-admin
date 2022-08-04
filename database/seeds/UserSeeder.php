<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    protected $users = [
      ['email'=>'pltruenine@163.com','password'=>'123456','name'=>'admin'],
      ['email'=>'demo@163.com','password'=>'123456','name'=>'demo'],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->users as $user) {
            $this->createUser($user['email'],$user['name'],$user['password']);
        }
    }

    public function createUser($email,$name,$password){
        if(User::query()->where('email',$email)->doesntExist()){
            User::query()->create([
                'name'=>$name,
                'email'=>$email,
                'password'=>Hash::make($password),
                'email_verified_at'=>now()->toDateTimeString(),
            ]);
        }
    }


}
