<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateModuleTestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:moduletests {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to initialize the module tests';

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

        $this->call('make:test', [
            "name" => "Api/{$name}/Index{$name}Test",
        ]);
        $this->call('make:test', [
            "name" => "Api/{$name}/Store{$name}Test",
        ]);
        $this->call('make:test', [
            "name" => "Api/{$name}/Show{$name}Test",
        ]);
        $this->call('make:test', [
            "name" => "Api/{$name}/Update{$name}Test",
        ]);
        $this->call('make:test', [
            "name" => "Api/{$name}/Destroy{$name}Test",
        ]);
    }
}
