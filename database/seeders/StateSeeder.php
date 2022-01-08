<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $active = Status::enabled()->value('id');

        $states = [
            [
                'id' => 775,
                'name' => 'Amazonas',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 776,
                'name' => 'Antioquia',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 777,
                'name' => 'Arauca',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 778,
                'name' => 'Atlantico',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 779,
                'name' => 'Bogota',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 780,
                'name' => 'Bolivar',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 781,
                'name' => 'Boyaca',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 782,
                'name' => 'Caldas',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 783,
                'name' => 'Caqueta',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 784,
                'name' => 'Casanare',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 785,
                'name' => 'Cauca',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 786,
                'name' => 'Cesar',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 787,
                'name' => 'Choco',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 788,
                'name' => 'Cordoba',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 789,
                'name' => 'Cundinamarca',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 790,
                'name' => 'Guainia',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 791,
                'name' => 'Guaviare',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 792,
                'name' => 'Huila',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 793,
                'name' => 'La Guajira',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 794,
                'name' => 'Magdalena',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 795,
                'name' => 'Meta',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 796,
                'name' => 'Narino',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 797,
                'name' => 'Norte de Santander',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 798,
                'name' => 'Putumayo',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 799,
                'name' => 'Quindio',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 800,
                'name' => 'Risaralda',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 801,
                'name' => 'San Andres y Providencia',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 802,
                'name' => 'Santander',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 803,
                'name' => 'Sucre',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 804,
                'name' => 'Tolima',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 805,
                'name' => 'Valle del Cauca',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 806,
                'name' => 'Vaupes',
                'country_id' => 47,
                'status_id' => $active,
            ],
            [
                'id' => 807,
                'name' => 'Vichada',
                'country_id' => 47,
                'status_id' => $active,
            ]
        ];

        DB::table('states')->insert($states);
    }
}
