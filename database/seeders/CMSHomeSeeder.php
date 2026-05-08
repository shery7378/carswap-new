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
                'name' => 'Főoldali bevezető szöveg',
                'title' => 'Megbízható partner az autórajongók számára',
                'description' => 'Ez a fő bevezető bekezdés, amely a kezdőlapon a logó mellett jelenik meg.',
                'status' => 1,
            ]
        );

        CMSItem::updateOrCreate(
            ['section_id' => $hero->id, 'title' => 'Fő tartalom'],
            [
                'description' => '<p>A CARSWAP&reg; megbízható partner az autórajongók számára, akik használt autót szeretnének cserélni vagy eladni. Platformunkon könnyedén feltöltheti járművét, beszámíttathatja azt, vagy vevőt találhat rá.</p><p><br></p><p>Csatlakozzon a CARSWAP&reg; közösségéhez, és tegye zökkenőmentessé autóügyleteit!</p>',
                'order' => 0
            ]
        );

        // 2. Home Services (The 8 Grid Items)
        $services = CMSSection::updateOrCreate(
            ['slug' => 'home-services'],
            [
                'name' => 'Főoldali szolgáltatások / Funkciók',
                'title' => 'Megoldásaink Önnek',
                'description' => 'Fedezze fel a CARSWAP által kínált szolgáltatásokat, amelyek jobbá teszik az élményt.',
                'status' => 1,
            ]
        );

        $service_items = [
            [
                'title' => 'Egyszerű autócsere',
                'description' => 'A CARSWAP teljes körű támogatást nyújt az autócsere folyamatában. Legyen szó bármilyen típusú járműről, segítünk megtalálni az ideális cserepartnert.',
                'icon' => 'bx-transfer-alt',
                'order' => 1
            ],
            [
                'title' => 'Autóeladás és hirdetés',
                'description' => 'Adja el használt autóját gyorsan és egyszerűen a CARSWAP-on. Hozza létre hirdetését, és érje el potenciális vásárlóját még ma!',
                'icon' => 'bx-megaphone',
                'order' => 2
            ],
            [
                'title' => 'Autószerviz és kiegészítők',
                'description' => 'A CARSWAP által ajánlott autójavító műhelyeket és kiegészítő-kereskedőket is megtalálhatja nálunk.',
                'icon' => 'bx-wrench',
                'order' => 3
            ],
            [
                'title' => 'Dokumentáció kezelése',
                'description' => 'Segítünk a szükséges dokumentumok elkészítésében és kezelésében autóvásárlás vagy -eladás során.',
                'icon' => 'bx-file',
                'order' => 4
            ],
            [
                'title' => 'HD képek és virtuális túra',
                'description' => 'Mutassuk meg járművét a legjobb formájában! Segítünk HD minőségű képekkel, és hamarosan virtuális túrával bemutatni autóit.',
                'icon' => 'bx-camera-movie',
                'order' => 5
            ],
            [
                'title' => 'Szervizajánlás és szakértői segítség',
                'description' => 'Ismerjük a legjobb szervizeket és szakembereket. Az általunk ajánlott partnerek bizonyított, megbízható támogatást nyújtanak járművéhez.',
                'icon' => 'bx-user-voice',
                'order' => 6
            ],
            [
                'title' => 'Céges autók értékesítése',
                'description' => 'Vállalkozások számára is kínálunk lehetőséget flottajárműveik cseréjére vagy eladására.',
                'icon' => 'bx-buildings',
                'order' => 7
            ],
            [
                'title' => 'Egyéb szolgáltatások',
                'description' => 'További egyedi szolgáltatásokat kínálunk minden igényének kielégítésére.',
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

        // 3. Home Page Headings
        $headings = CMSSection::updateOrCreate(
            ['slug' => 'home-headings'],
            [
                'name' => 'Főoldali címsorok',
                'title' => 'Főoldali szekció címek',
                'description' => 'Kezelje a kezdőlap különböző elrendezési szekcióinak címeit.',
                'status' => 1,
            ]
        );

        $heading_items = [
            ['title' => 'Top Cars Header', 'description' => 'Új és használt autók', 'order' => 1],
            ['title' => 'Browse By Brands Header', 'description' => 'Böngészés márka szerint', 'order' => 2],
            ['title' => 'Browse By Design Header', 'description' => '..vagy kivitel szerint', 'order' => 3],
            ['title' => 'Featured Ads Header', 'description' => 'KIEMELT HIRDETÉSEK', 'order' => 4],
            ['title' => 'Latest Ads Header', 'description' => 'LEGUTÓBBI HIRDETÉSEK', 'order' => 5],
        ];

        foreach ($heading_items as $item) {
            CMSItem::updateOrCreate(
                ['section_id' => $headings->id, 'title' => $item['title']],
                [
                    'description' => $item['description'],
                    'order' => $item['order']
                ]
            );
        }
    }
}
