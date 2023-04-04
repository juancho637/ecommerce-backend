<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateScoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scout:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update all scout records';

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
        $this->call('scout:flush', [
            'model' => 'App\\Models\\Country',
        ]);
        $this->call('scout:import', [
            'model' => 'App\\Models\\Country',
        ]);

        $this->call('scout:flush', [
            'model' => 'App\\Models\\State',
        ]);
        $this->call('scout:import', [
            'model' => 'App\\Models\\State',
        ]);

        $this->call('scout:flush', [
            'model' => 'App\\Models\\City',
        ]);
        $this->call('scout:import', [
            'model' => 'App\\Models\\City',
        ]);

        $this->call('scout:flush', [
            'model' => 'App\\Models\\Category',
        ]);
        $this->call('scout:import', [
            'model' => 'App\\Models\\Category',
        ]);

        $this->call('scout:flush', [
            'model' => 'App\\Models\\Tag',
        ]);
        $this->call('scout:import', [
            'model' => 'App\\Models\\Tag',
        ]);

        $this->call('scout:flush', [
            'model' => 'App\\Models\\ProductAttribute',
        ]);
        $this->call('scout:import', [
            'model' => 'App\\Models\\ProductAttribute',
        ]);

        $this->call('scout:flush', [
            'model' => 'App\\Models\\Product',
        ]);
        $this->call('scout:import', [
            'model' => 'App\\Models\\Product',
        ]);
    }
}
