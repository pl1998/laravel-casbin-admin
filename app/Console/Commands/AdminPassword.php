<?php

namespace App\Console\Commands;


use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:install';

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
       $result = User::query()->where('name','admin')
           ->update([
              'password'=>Hash::make(123456)
           ]);
       if($result) {
           $this->info("Success 密码重置成功!");
       }else{
           $this->error("Error 用户不存在");
       }
    }
}
