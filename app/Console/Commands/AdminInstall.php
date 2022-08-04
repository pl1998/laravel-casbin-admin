<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AdminInstall extends Command
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
    protected $description = '初始化部署命令';

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
        $this->call("key:gen");
        $this->call("migrate");
        $this->call("db:seed");
        $this->call("create:roles:to:users");
    }
}
