<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AuthUsers;
class Logouts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:Logouts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Desconectar usuarios a la media noche';

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
     * @return mixed
     */
    public function handle()
    {

        //php /var/www/html/crm/artisan registered:Logouts
        $token_user = AuthUsers::get();

		foreach ($token_user as $key => $value) {
            $value->delete();
        }
    }
}
