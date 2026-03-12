<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertySeeder extends Seeder
{
    public function run()
    {
        $features = [
            '360 fokos kamerarendszer',
            'Önvezető funkció',
            'állítható combtámasz',
            'állítható felfüggesztés',
            'állítható hátsó ülések',
            'állítható kormány',
            'álló helyzeti klíma',
            'állófűtés',
            'éjjellátó asszisztens',
            'érintőkijelző',
            'A/C: Front',
            'A/C: Rear',
            'üléshűtés',
            'ülésmagasság állítás',
            'tközés veszélyre felkészítő rendszer',
            'ABS',
            'ABS (blokkolásgátló)',
            'ASR (kipörgésgátló)',
            'ESP (menetstabilizátor)',
            'Alcantara kárpit',
            'Bőrbelső',
            'Digitális műszeregység',
            'Hifi',
            'Keyless Go',
            'Led fényszórók',
            'Panorámatető',
            'Tolatóradar',
            'Tolatókamera'
        ];

        foreach ($features as $f) {
            DB::table('properties')->updateOrInsert(['name' => $f]);
        }
    }
}
