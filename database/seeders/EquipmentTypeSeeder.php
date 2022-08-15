<?php

namespace Database\Seeders;

use App\Models\EquipmentType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EquipmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $equipmentTypes = [
            [
                'id' => 1,
                'name' => 'TP-Link TL-WR74',
                'mask' => 'XXAAAAAXAA',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'D-Link DIR-300',
                'mask' => 'NXXAAXZXaa',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'D-Link DIR-300 S',
                'mask' => 'NXXAAXZXXX',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        EquipmentType::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        EquipmentType::insert($equipmentTypes);
    }
}
