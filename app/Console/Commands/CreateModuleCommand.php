<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to initialize a module';

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

        $this->call('make:model', [
            "name" => "{$name}",
            "-m" => true,
            "-s" => true,
            "-f" => true,
        ]);

        $this->call('make:request', [
            "name" => "Api/{$name}/Store{$name}Request",
        ]);

        $this->call('make:request', [
            "name" => "Api/{$name}/Update{$name}Request",
        ]);

        $this->call('make:controller', [
            "name" => "Api/{$name}/{$name}Controller",
            "-m" => $name,
            "--api" => true,
        ]);

        $this->call('make:resource', [
            "name" => "{$name}Resource",
        ]);

        $this->call('make:policy', [
            "name" => "{$name}Policy",
            "-m" => $name,
        ]);

        $this->call('make:test', [
            "name" => "Api/{$name}/{$name}Test",
        ]);
    }
}
