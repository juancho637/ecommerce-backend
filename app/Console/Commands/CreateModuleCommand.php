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
    protected $signature = 'make:module {name}';

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

        $this->call('make:route', [
            "name" => "{$name}",
        ]);

        $this->call('make:resource', [
            "name" => "{$name}Resource",
        ]);

        $this->call('make:policy', [
            "name" => "{$name}Policy",
            "-m" => $name,
        ]);

        $this->call('make:test', [
            "name" => "Api/{$name}/{$name}IndexTest",
        ]);
        $this->call('make:test', [
            "name" => "Api/{$name}/{$name}StoreTest",
        ]);
        $this->call('make:test', [
            "name" => "Api/{$name}/{$name}ShowTest",
        ]);
        $this->call('make:test', [
            "name" => "Api/{$name}/{$name}UpdateTest",
        ]);
        $this->call('make:test', [
            "name" => "Api/{$name}/{$name}DestroyTest",
        ]);
    }
}
