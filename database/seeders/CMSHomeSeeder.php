<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CMSSection;
use App\Models\CMSItem;

class CMSHomeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Home Hero Section
        $hero = CMSSection::updateOrCreate(
            ['slug' => 'home-hero'],
            [
                'name' => 'Home Hero Section',
                'title' => 'Trusted Partner for Car Enthusiasts',
                'description' => 'CARSWAP is a trusted partner for car enthusiasts looking to swap or sell used cars. On our platform, you can easily upload your vehicle, trade it in, or find a buyer.',
                'status' => 1,
            ]
        );

        CMSItem::updateOrCreate(
            ['section_id' => $hero->id, 'title' => 'Call to Action'],
            [
                'description' => 'Join the CARSWAP community and make your car transactions seamless!',
                'order' => 0
            ]
        );

        // 2. Home Services (The 8 Grid Items)
        $services = CMSSection::updateOrCreate(
            ['slug' => 'home-services'],
            [
                'name' => 'Home Services / Features',
                'title' => 'Our Solutions for You',
                'description' => 'Explore the primary services offered by CARSWAP to make your experience better.',
                'status' => 1,
            ]
        );

        $service_items = [
            [
                'title' => 'Easy car exchange',
                'description' => 'CARSWAP provides full support in the process of car swapping. No matter what type of vehicle it is, we will help you find the ideal swap partner.',
                'icon' => 'bx-transfer-alt',
                'order' => 1
            ],
            [
                'title' => 'Car sales and advertising',
                'description' => 'Sell your used car quickly and easily on CARSWAP. Create your ad and reach your potential buyer today!',
                'icon' => 'bx-megaphone',
                'order' => 2
            ],
            [
                'title' => 'Car service and accessories',
                'description' => 'You can also find car repair shops and accessories dealers recommended by CARSWAP.',
                'icon' => 'bx-wrench',
                'order' => 3
            ],
            [
                'title' => 'Documentation management',
                'description' => 'We help you prepare and manage the necessary documents when buying or selling a car.',
                'icon' => 'bx-file',
                'order' => 4
            ],
            [
                'title' => 'HD images and virtual tour',
                'description' => 'Let us show your vehicle at its best! We will help you present your cars with HD quality images and soon with a virtual tour.',
                'icon' => 'bx-camera-movie',
                'order' => 5
            ],
            [
                'title' => 'Service recommendation and expert assistance',
                'description' => 'We know the best service centers and professionals. The partners we recommend provide proven, reliable support for your vehicle.',
                'icon' => 'bx-user-voice',
                'order' => 6
            ],
            [
                'title' => 'Sales of company cars',
                'description' => 'We also offer companies opportunities to exchange or sell their fleet vehicles.',
                'icon' => 'bx-buildings',
                'order' => 7
            ],
            [
                'title' => 'Other Services',
                'description' => 'We offer additional unique services to meet all your needs.',
                'icon' => 'bx-dots-horizontal-rounded',
                'order' => 8
            ]
        ];

        foreach ($service_items as $item) {
            CMSItem::updateOrCreate(
                ['section_id' => $services->id, 'title' => $item['title']],
                [
                    'description' => $item['description'],
                    'icon' => $item['icon'],
                    'order' => $item['order']
                ]
            );
        }
    }
}
