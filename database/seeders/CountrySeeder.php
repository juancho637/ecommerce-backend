<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $active = Status::enabled()->value('id');

        $companies = [
            [
                'id' => 47,
                'status_id' => $active,
                'short_name' => 'CO',
                'name' => 'Colombia',
                'phone_code' => '+57',
            ],
        ];

        DB::table('countries')->insert($companies);
    }
}
