<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CMSSection;
use App\Models\CMSItem;

class CMSBlogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Blog Posts Section
        $blogSection = CMSSection::updateOrCreate(
            ['slug' => 'blog-posts'],
            [
                'name' => 'Blog bejegyzések',
                'title' => 'Legfrissebb hírek és útmutatók',
                'description' => 'A weboldal összes blogbejegyzésének és cikkének kezelése.',
                'status' => 1,
            ]
        );

        $posts = [
            [
                'title' => 'Mit nézzünk meg autóvásárlás előtt?',
                'description' => '<p>Az autóvásárlás megterhelő élmény lehet. Figyelembe kell venni a költségvetést, a saját igényeit és az életstílusához illő járműtípust. Ebben a bejegyzésben a 10 legfontosabb dolgot beszéljük meg, amit ellenőriznie kell a végső döntés előtt.</p>',
                'link' => json_encode(['date' => '2024.04.17.', 'comments' => 4]),
                'order' => 1
            ],
            [
                'title' => 'Autócsere - Egyszerű és nagyszerű',
                'description' => '<p>Az autócsere nem szabadna, hogy gondot okozzon. A CARSWAP-nál a folyamat egyszerűsítve van, hogy Ön a legjobb értéket kapja a hagyományos fejfájás nélkül. Tudja meg, hogyan teszi platformunk egyszerűvé az autócserét az elejétől a végéig.</p>',
                'link' => json_encode(['date' => '2024.04.17.', 'comments' => 5]),
                'order' => 2
            ],
            [
                'title' => 'Amit tudnod KELL az adásvételi szerződésről!',
                'description' => '<p>Egy világos és jogilag kötelező erejű adásvételi szerződés védi mind a vevőt, mind az eladót. Ez a cikk az alapvető záradékokat tárgyalja, amelyeket minden gépjármű-adásvételi szerződésnek tartalmaznia kell, és azt, hogyan értelmezzük azokat helyesen.</p>',
                'link' => json_encode(['date' => '2024.04.17.', 'comments' => 5]),
                'order' => 3
            ]
        ];

        foreach ($posts as $post) {
            CMSItem::updateOrCreate(
                ['section_id' => $blogSection->id, 'title' => $post['title']],
                [
                    'description' => $post['description'],
                    'link' => $post['link'], // Storing extra metadata in the link field temporarily
                    'order' => $post['order'],
                    'status' => 1
                ]
            );
        }
    }
}
