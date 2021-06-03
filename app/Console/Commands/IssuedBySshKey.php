<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IssuedBySshKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issued-by-sshKey';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '颁发sshKey';



    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}
