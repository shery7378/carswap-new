<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\FuelType;
use App\Models\Transmission;

class VehicleSeeder extends Seeder
{
    public function run()
    {
        // Ensure some basic types exist
        $fuelBenzin = FuelType::updateOrCreate(['name' => 'BENZIN']);
        $fuelHybrid = FuelType::updateOrCreate(['name' => 'BENZIN / GÁZ (LPG)']);
        $fuelDiesel = FuelType::updateOrCreate(['name' => 'DÍZEL']);
        $fuelElectric = FuelType::updateOrCreate(['name' => 'ELEKTROMOS']);

        $transAuto = Transmission::updateOrCreate(['name' => 'AUTOMATA']);
        $transAuto8 = Transmission::updateOrCreate(['name' => 'AUTOMATA (8 FOKOZATÚ)']);
        $transAuto9 = Transmission::updateOrCreate(['name' => 'AUTOMATA (9 FOKOZATÚ)']);

        $vehicles = [
            [
                'title' => 'AIXAM CROSSLINE',
                'brand' => 'AIXAM',
                'model' => 'CROSSLINE',
                'year' => 1952,
                'price' => 4444,
                'mileage' => 7777,
                'fuel_type_id' => $fuelHybrid->id,
                'transmission_id' => $transAuto9->id,
                'cylinder_capacity' => '77777',
                'performance' => '7777',
                'location' => 'Európa',
                'is_featured' => true,
                'main_image' => '1.jpg'
            ],
            [
                'title' => 'LAMBORGHINI AVENTADOR',
                'brand' => 'LAMBORGHINI',
                'model' => 'AVENTADOR',
                'year' => 2023,
                'price' => 82000000,
                'mileage' => 12000,
                'fuel_type_id' => $fuelBenzin->id,
                'transmission_id' => $transAuto->id,
                'cylinder_capacity' => '6498',
                'performance' => '400',
                'location' => 'Budapest, Magyarország',
                'is_featured' => true,
                'main_image' => '2.jpg'
            ],
            [
                'title' => 'ACURA RL',
                'brand' => 'ACURA',
                'model' => 'RL',
                'year' => 2008,
                'price' => 68987,
                'mileage' => 66666,
                'fuel_type_id' => $fuelBenzin->id,
                'transmission_id' => $transAuto8->id,
                'cylinder_capacity' => '3500',
                'performance' => '222',
                'location' => 'Debrecen, Magyarország',
                'is_featured' => false,
                'main_image' => '3.jpg'
            ],
            [
                'title' => 'ACURA RL',
                'brand' => 'ACURA',
                'model' => 'RL',
                'year' => 2015,
                'price' => 4444,
                'mileage' => 111111,
                'fuel_type_id' => $fuelHybrid->id,
                'transmission_id' => $transAuto8->id,
                'cylinder_capacity' => '2400',
                'performance' => '111',
                'location' => 'New York, Egyesült Államok',
                'is_featured' => false,
                'main_image' => '4.jpg'
            ],
            [
                'title' => 'AIWAYS U6',
                'brand' => 'AIWAYS',
                'model' => 'U6',
                'year' => 2022,
                'price' => 4444,
                'mileage' => 11111,
                'fuel_type_id' => $fuelHybrid->id,
                'transmission_id' => $transAuto->id,
                'cylinder_capacity' => '1500',
                'performance' => '111',
                'location' => 'Delhi, India',
                'is_featured' => false,
                'main_image' => '5.jpg'
            ],
            [
                'title' => 'AIXAM CROSSOVER',
                'brand' => 'AIXAM',
                'model' => 'CROSSOVER',
                'year' => 2020,
                'price' => 33333,
                'mileage' => 11111,
                'fuel_type_id' => $fuelBenzin->id,
                'transmission_id' => $transAuto8->id,
                'cylinder_capacity' => '1000',
                'performance' => '111',
                'location' => 'Florida, Egyesült Államok',
                'is_featured' => false,
                'main_image' => '1.jpg'
            ],
            [
                'title' => 'AIXAM CROSSLINE',
                'brand' => 'AIXAM',
                'model' => 'CROSSLINE',
                'year' => 2019,
                'price' => 4444,
                'mileage' => 333,
                'fuel_type_id' => $fuelHybrid->id,
                'transmission_id' => $transAuto->id,
                'cylinder_capacity' => '1200',
                'performance' => '88',
                'location' => 'Európa',
                'is_featured' => true,
                'main_image' => '2.jpg'
            ],
            [
                'title' => 'ALFA ROMEO 159',
                'brand' => 'ALFA ROMEO',
                'model' => '159',
                'year' => 2010,
                'price' => 55555,
                'mileage' => 180000,
                'fuel_type_id' => $fuelDiesel->id,
                'transmission_id' => $transAuto8->id,
                'cylinder_capacity' => '2000',
                'performance' => '125',
                'location' => 'Budapest, Magyarország',
                'is_featured' => false,
                'main_image' => '3.jpg'
            ],
            [
                'title' => 'AIXAM CITY',
                'brand' => 'AIXAM',
                'model' => 'CITY',
                'year' => 2018,
                'price' => 3333,
                'mileage' => 33333,
                'fuel_type_id' => $fuelHybrid->id,
                'transmission_id' => $transAuto->id,
                'cylinder_capacity' => '333',
                'performance' => '333',
                'location' => 'Szeged, Magyarország',
                'is_featured' => false,
                'main_image' => '4.jpg'
            ],
            [
                'title' => 'AIWAYS 5',
                'brand' => 'AIWAYS',
                'model' => '5',
                'year' => 2021,
                'price' => 77676,
                'mileage' => 25000,
                'fuel_type_id' => $fuelElectric->id,
                'transmission_id' => $transAuto8->id,
                'cylinder_capacity' => '-',
                'performance' => '150',
                'location' => 'Budapest, Magyarország',
                'is_featured' => false,
                'main_image' => '5.jpg'
            ]
        ];

        foreach ($vehicles as $v) {
            $brand = Brand::firstOrCreate(['name' => $v['brand']]);
            $model = VehicleModel::firstOrCreate(['name' => $v['model'], 'brand_id' => $brand->id]);

            Vehicle::create([
                'title' => $v['title'],
                'brand_id' => $brand->id,
                'model_id' => $model->id,
                'year' => $v['year'],
                'price' => $v['price'],
                'mileage' => $v['mileage'],
                'fuel_type_id' => $v['fuel_type_id'],
                'transmission_id' => $v['transmission_id'],
                'cylinder_capacity' => $v['cylinder_capacity'],
                'performance' => $v['performance'],
                'location' => $v['location'],
                'is_featured' => $v['is_featured'],
                'main_image' => 'vehicles/' . $v['main_image'],
                'ad_status' => 'published',
                'user_id' => 1 // Assuming 1 is default admin
            ]);
        }
    }
}
