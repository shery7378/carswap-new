<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CMSSection;
use App\Models\CMSItem;
use Illuminate\Support\Str;

class CMSLegalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legalSections = [
            [
                'name' => 'Általános Szerződési Feltételek',
                'slug' => 'general-terms-and-conditions',
                'title' => 'Általános Szerződési Feltételek',
                'subtitle' => 'Jogi megállapodás a szolgáltatás és a felhasználók között.',
                'description' => 'Ez a rész tartalmazza a CarSwap használatára vonatkozó hivatalos általános szerződési feltételeket.',
                'status' => true,
            ],
            [
                'name' => 'Adatkezelési Tájékoztató',
                'slug' => 'data-protection-notice',
                'title' => 'Adatkezelési Tájékoztató',
                'subtitle' => 'Információk az adatai kezeléséről.',
                'description' => 'Ez a rész az adatvédelmi és adatkezelési irányelvekkel kapcsolatos információkat tartalmazza.',
                'status' => true,
            ],
            [
                'name' => 'Gyakran Ismételt Kérdések',
                'slug' => 'faq',
                'title' => 'Gyakran Ismételt Kérdések',
                'subtitle' => 'Válaszok a CarSwap használatával kapcsolatos gyakori kérdésekre.',
                'description' => 'A gyakori kérdések és válaszok átfogó listája.',
                'status' => true,
            ],
            [
                'name' => 'Iratkozzon fel hírlevelünkre',
                'slug' => 'mailing-list-info',
                'title' => 'Iratkozzon fel hírlevelünkre!',
                'subtitle' => 'Iratkozzon fel hírlevelünkre a legfrissebb hírekért.',
                'description' => 'A hírlevél feliratkozási rész tartalma.',
                'status' => true,
            ],
        ];

        foreach ($legalSections as $sectionData) {
            $section = CMSSection::updateOrCreate(
                ['slug' => $sectionData['slug']],
                $sectionData
            );

            // Detailed content for General Terms and Conditions if provided
            $itemContent = 'Kérjük, adja meg a tartalmat itt...';
            if ($sectionData['slug'] === 'general-terms-and-conditions') {
                $itemContent = '
                    <h4>Általános Szerződési Feltételek</h4>
                    <p>A <a href="https://carswap.hexafume.com">https://carswap.hexafume.com</a> weboldalra (a továbbiakban: Weboldal) és a Társaság szolgáltatásaira vonatkozóan.</p>
                    <p>A szolgáltatást az Ügyfelek részére a Swap Group Korlátolt Felelősségű Társaság (a továbbiakban: Társaság) – mint üzemeltető Társaság – és a vele szerződéses kapcsolatban álló partnerei nyújtják, a jelen általános szerződési feltételeknek (a továbbiakban: ÁSZF) megfelelően a Társaság által a Weboldalon végzett tevékenységek igénybevételével kapcsolatban.</p>
                    <p>A Társaság Weboldalán elérhető szolgáltatások igénybevételével az Ügyfél és a Társaság között a jelen ÁSZF feltételei szerinti szerződéses jogviszony jön létre, amely eltérő megállapodás hiányában elektronikus úton megkötöttnek minősül, és a Polgári Törvénykönyvről szóló 2013. évi V. törvény (a továbbiakban: Ptk.) 6:7. § (3) bekezdése alapján írásbelinek tekintendő (a továbbiakban: Szerződés).</p>

                    <h4>Kereskedő és Magánügyfelek</h4>
                    <p>Jelen ÁSZF alkalmazásában Ügyfél minden olyan személy, aki a Weboldalt megtekinti, hirdetést ad fel, terméket kínál eladásra vagy ajánlatot tesz termék megvételére, azaz hirdetésre jelentkezik (a továbbiakban: Ügyfél). A Társaság az Ügyfelek alábbi kategóriáit különbözteti meg a jelen ÁSZF-ben:</p>
                    <ul>
                        <li><strong>Kereskedő:</strong> Az az Ügyfél, aki az admin fiókba történő első bejelentkezés után Kereskedő Ügyfélként határozza meg magát. A kereskedői státuszt "Kereskedés" címke jelzi.</li>
                        <li><strong>Magánügyfél:</strong> Az a hirdetést feladó Ügyfél, aki nem Kereskedő.</li>
                    </ul>

                    <h4>1. A TÁRSASÁG ADATAI</h4>
                    <p>Név: Swap Group Korlátolt Felelősségű Társaság<br>
                    Székhely: 1039 Budapest, Álmos utca 3.<br>
                    Cégjegyzékszám: 01-09-423632<br>
                    Adószám: 32429073-2-41<br>
                    E-mail: swapgroupkft@gmail.com<br>
                    Telefonszám: +36305990290</p>

                    <h4>3. ÁLTALUNK KÍNÁLT ELŐFIZETÉSI SZOLGÁLTATÁSOK</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Szolgáltatások</th>
                                <th>Díjak</th>
                                <th>Fizetési határidő</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ingyenes csomag</td>
                                <td>0 Ft / hó</td>
                                <td>nem szükséges fizetés</td>
                            </tr>
                            <tr>
                                <td>Több autóm van csomag</td>
                                <td>21.990 Ft + ÁFA / hó</td>
                                <td>havonta előre</td>
                            </tr>
                            <tr>
                                <td>Kereskedői csomag</td>
                                <td>39.990 Ft + ÁFA / hó</td>
                                <td>havonta előre</td>
                            </tr>
                        </tbody>
                    </table>

                    <p><em>Hatálybalépés dátuma: 2025. május 28.</em></p>
                ';
            }

            if ($sectionData['slug'] === 'faq') {
                $faqs = [
                    ['q' => 'Hogyan regisztrálhatok a CARSWAP-on?', 'a' => 'A Regisztráció gombra kattintva és az adatai megadásával regisztrálhat...'],
                    ['q' => 'Milyen dokumentumok szükségesek egy autó hirdetéséhez?', 'a' => 'A hirdetés feladásához szüksége lesz a jármű forgalmi engedélyére, törzskönyvére, szervizkönyvére és a műszaki vizsga dokumentumára.'],
                    ['q' => 'Vásárolhatok-e autót közvetlenül, beszámítás nélkül?', 'a' => 'Igen, a felhasználók közvetlenül is vásárolhatnak járműveket, ha az eladó ezt lehetővé teszi.'],
                    ['q' => 'Hogyan működik az autócsere funkció?', 'a' => 'Az autócsere lehetővé teszi, hogy járművét egy másikra cserélje...'],
                    ['q' => 'Lehetséges-e egyszerre több autót cserélni?', 'a' => 'Jelenleg a CarSwap az 1:1 vagy N:1 cseréket támogatja, attól függően, hogy...'],
                    ['q' => 'Mi történik, ha a cserekérelmemet elutasítják?', 'a' => 'Értesítést kap, és tehet más ajánlatokat...'],
                    ['q' => 'Hogyan kaphatok értesítést az engem érdeklő autókról?', 'a' => 'Mentheti a kereséseket, vagy hozzáadhatja a járműveket a kedvenceihez...'],
                    ['q' => 'Milyen szűrési lehetőségek állnak rendelkezésemre?', 'a' => 'Szűrést kínálunk Márka, Modell, Évjárat, Üzemanyag típusa és egyebek alapján.'],
                    ['q' => 'Mit tegyek, ha problémám van egy másik felhasználóval?', 'a' => 'Kérjük, azonnal vegye fel a kapcsolatot ügyfélszolgálatunkkal.'],
                ];

                foreach ($faqs as $index => $faq) {
                    CMSItem::updateOrCreate(
                        [
                            'section_id' => $section->id,
                            'title' => $faq['q'], // Using question as the title
                        ],
                        [
                            'description' => $faq['a'], // Using answer as the description
                            'order' => $index + 1,
                            'status' => true,
                        ]
                    );
                }
                continue; // Skip the default item below for FAQ
            }

            if ($sectionData['slug'] === 'mailing-list-info') {
                $itemContent = '
                    <h4>Iratkozzon fel hírlevelünkre!</h4>
                    <p>Szeretne elsőként értesülni az új termékekről, titkos ajánlatokról vagy inspiráló tartalmakról? Iratkozzon fel hírlevelünkre, és garantáljuk, hogy csak hasznos, érdekes vagy mosolyt csaló üzeneteket kap - mi is utáljuk a spameket.</p>
                    <p>Havonta néhány levél, semmi felesleges, csak a lényeg.</p>
                ';
            }

            // Create or update the default item for each section (T&C, Privacy, Mailing list)
            CMSItem::updateOrCreate(
                [
                    'section_id' => $section->id,
                    'title' => 'Fő tartalom',
                ],
                [
                    'description' => $itemContent,
                    'order' => 1,
                    'status' => true,
                ]
            );
        }
    }
}
