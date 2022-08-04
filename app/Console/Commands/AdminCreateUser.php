<?php

namespace App\Console\Commands;


use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AdminCreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置admin用户密码';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::query()->where('name','admin')->first();

        if($users) {
            $users->password = Hash::make('123456');
            $users->save();
            $this->info("Success 密码重置成功!");
        } else {
            $email = 'pltruenine@163.com';
            $password = '123456';
            User::query()->create([
               'name'=>'admin',
               'email'=>$email,
               'password'=>Hash::make($password),
               'email_verified_at'=>now()->toDateTimeString(),
            ]);
            $this->info("用户创建成功 ! 账号:$email 密码:$password !");
        }

        $result = User::query()->where('name','admin')
            ->update([
                'password'=>Hash::make(123456)
            ]);
        if($result) {

        }else{
            $this->error("Error 用户不存在");
        }
    }
}
