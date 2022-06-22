<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateModuleControllersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:modulecontrollers {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to initialize the module controllers';

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
        $name = $this->argument('name');

        $this->call('make:controller', [
            "name" => "Api/{$name}/{$name}IndexController",
            "--model" => $name,
            "--type" => 'invrequest',
        ]);
        $this->call('make:controller', [
            "name" => "Api/{$name}/{$name}StoreController",
            "--model" => $name,
            "--type" => 'invrequest',
        ]);
        $this->call('make:controller', [
            "name" => "Api/{$name}/{$name}ShowController",
            "--model" => $name,
            "--type" => 'invmodelsimple',
        ]);
        $this->call('make:controller', [
            "name" => "Api/{$name}/{$name}UpdateController",
            "--model" => $name,
            "--type" => 'invmodelrequest',
        ]);
        $this->call('make:controller', [
            "name" => "Api/{$name}/{$name}DestroyController",
            "--model" => $name,
            "--type" => 'invmodel',
        ]);
    }
}
