<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Sales Methods
        $salesMethods = ['Direct Sale', 'Interchangeable', 'Auction', 'Consignment'];
        foreach ($salesMethods as $name) {
            DB::table('sales_methods')->updateOrInsert(['name' => $name]);
        }

        // 2. Body Types (Design)
        $bodyTypes = ['SUV', 'Coupe', 'Sedan', 'Hatchback', 'Convertible', 'Wagon', 'Van', 'Pickup', 'Minivan'];
        foreach ($bodyTypes as $name) {
            DB::table('body_types')->updateOrInsert(['name' => $name]);
        }

        // 3. Fuel Types
        $fuelTypes = ['Petrol', 'Diesel', 'Electric', 'Hybrid', 'Plug-in Hybrid', 'LPG', 'CNG', 'Hydrogen'];
        foreach ($fuelTypes as $name) {
            DB::table('fuel_types')->updateOrInsert(['name' => $name]);
        }

        // 4. Transmissions (Gearbox type)
        $transmissions = ['Automatic', 'Manual', 'Semi-automatic', 'Stepless (CVT)'];
        foreach ($transmissions as $name) {
            DB::table('transmissions')->updateOrInsert(['name' => $name]);
        }

        // 5. Drive Types (Drive)
        $driveTypes = ['Front-wheel', 'Back-wheel', 'All-wheel (4x4)', 'Switchable 4WD'];
        foreach ($driveTypes as $name) {
            DB::table('drive_types')->updateOrInsert(['name' => $name]);
        }

        // 6. Vehicle Statuses
        $statuses = ['Novel', 'Used', 'Failed/Damaged', 'Showroom car', 'Test car'];
        foreach ($statuses as $name) {
            DB::table('vehicle_statuses')->updateOrInsert(['name' => $name]);
        }

        // 7. Document Types
        $docTypes = ['Valid Hungarian documents', 'Foreign documents', 'Temporary documents', 'No documents'];
        foreach ($docTypes as $name) {
            DB::table('document_types')->updateOrInsert(['name' => $name]);
        }

        // 8. Colors (Exterior & Interior)
        $colors = [
            ['name' => 'White', 'type' => 'exterior'],
            ['name' => 'Black', 'type' => 'exterior'],
            ['name' => 'Grey', 'type' => 'exterior'],
            ['name' => 'Silver', 'type' => 'exterior'],
            ['name' => 'Blue', 'type' => 'exterior'],
            ['name' => 'Red', 'type' => 'exterior'],
            ['name' => 'Brown', 'type' => 'exterior'],
            ['name' => 'Green', 'type' => 'exterior'],
            ['name' => 'Beige', 'type' => 'exterior'],
            ['name' => 'Yellow', 'type' => 'exterior'],
            ['name' => 'Black', 'type' => 'interior'],
            ['name' => 'Beige', 'type' => 'interior'],
            ['name' => 'Grey', 'type' => 'interior'],
            ['name' => 'Brown', 'type' => 'interior'],
            ['name' => 'White', 'type' => 'interior'],
            ['name' => 'Red', 'type' => 'interior'],
        ];
        foreach ($colors as $color) {
            DB::table('colors')->updateOrInsert($color);
        }

        // 9. Properties (Vehicle features)
        $properties = [
            'Air conditioning',
            'ABS',
            'ESP',
            'Navigation system',
            'Cruise control',
            'Leather seats',
            'Heated seats',
            'Parking sensors',
            'Reverse camera',
            'Alloy wheels',
            'Sunroof',
            'Panoramic roof',
            'LED headlights',
            'Xenon headlights',
            'Bluetooth',
            'Electric windows',
            'Central locking',
            'ISOFIX',
            'Tow bar',
            'Tuner/Radio'
        ];
        foreach ($properties as $name) {
            DB::table('properties')->updateOrInsert(['name' => $name]);
        }
    }
}
