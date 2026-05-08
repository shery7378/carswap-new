<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            ['name' => 'Üdvözlünk', 'slug' => 'welcome', 'subject' => 'Üdvözlünk a CarSwap-en! 🎉', 'category' => 'Általános', 'shortcodes' => ['first_name', 'frontend_url', 'login_url']],
            ['name' => 'Új felhasználó', 'slug' => 'new-user', 'subject' => 'Új felhasználó regisztráció', 'category' => 'Auth', 'shortcodes' => ['user_name', 'user_email']],
            ['name' => 'E-mail megerősítés', 'slug' => 'new-user-email-confirmation', 'subject' => 'Kérjük, erősítse meg e-mail címét', 'category' => 'Auth', 'shortcodes' => ['first_name', 'confirmation_link']],
            ['name' => 'Jelszó visszaállítás', 'slug' => 'password-recovery', 'subject' => 'Jelszó visszaállítása', 'category' => 'Auth', 'shortcodes' => ['first_name', 'reset_link']],
            ['name' => 'Kereskedői megkeresés', 'slug' => 'request-for-dealer', 'subject' => 'Új kereskedői érdeklődés', 'category' => 'Inquiry', 'shortcodes' => ['dealer_name', 'user_contact', 'message']],
            ['name' => 'Tesztvezetés', 'slug' => 'test-drive', 'subject' => 'Tesztvezetési kérelem', 'category' => 'Trade', 'shortcodes' => ['first_name', 'car_model', 'date_requested']],
            ['name' => 'Árajánlat kérés', 'slug' => 'request-price', 'subject' => 'Árajánlat kérés', 'category' => 'Inquiry', 'shortcodes' => ['first_name', 'car_details', 'contact_info']],
            ['name' => 'Autó beszámítás', 'slug' => 'trade-in', 'subject' => 'Autó beszámítási kérelem', 'category' => 'Trade', 'shortcodes' => ['car', 'first_name', 'last_name', 'email', 'phone', 'make', 'model', 'stm_year', 'transmission', 'mileage', 'vin', 'exterior_color', 'interior_color', 'owner', 'exterior_condition', 'interior_condition', 'accident', 'comments']],
            ['name' => 'Csere ajánlat', 'slug' => 'trade-offer', 'subject' => 'Új csere ajánlat érkezett', 'category' => 'Trade', 'shortcodes' => ['offer_amount', 'car_details', 'buyer_name']],
            ['name' => 'Hirdetés aktív', 'slug' => 'add-car', 'subject' => 'Hirdetése élesedett!', 'category' => 'Listings', 'shortcodes' => ['listing_url', 'car_title']],
            ['name' => 'Hirdetés frissítve', 'slug' => 'update-car', 'subject' => 'Hirdetés sikeresen frissítve', 'category' => 'Listings', 'shortcodes' => ['car_title', 'listing_url']],
            ['name' => 'Fizetett hirdetés frissítése', 'slug' => 'update-pay-per-listing', 'subject' => 'Fizetett hirdetés frissítése', 'category' => 'Payments', 'shortcodes' => ['listing_details', 'payment_status']],
            ['name' => 'Értékelés bejelentése', 'slug' => 'report-review', 'subject' => 'Egy értékelést bejelentettek', 'category' => 'Moderation', 'shortcodes' => ['review_id', 'reporter_info', 'reason']],
            ['name' => 'Fizetett hirdetés', 'slug' => 'pay-per-listing', 'subject' => 'Hirdetés fizetési visszaigazolás', 'category' => 'Payments', 'shortcodes' => ['receipt_url', 'amount_paid', 'car_title']],
            ['name' => 'Autó értékbecslés', 'slug' => 'value-my-car', 'subject' => 'Autó értékbecslési jelentés', 'category' => 'Inquiry', 'shortcodes' => ['first_name', 'valuation_amount', 'car_specs']],
            ['name' => 'Jóváhagyásra váró hirdetés', 'slug' => 'user-listing-waiting', 'subject' => 'Hirdetés jóváhagyásra vár', 'category' => 'Listings', 'shortcodes' => ['car_title', 'review_time_estimate']],
            ['name' => 'Jóváhagyott hirdetés', 'slug' => 'user-listing-approved', 'subject' => 'Hirdetését jóváhagyták!', 'category' => 'Listings', 'shortcodes' => ['car_title', 'live_url']],
            ['name' => 'Üzenet a kereskedőnek', 'slug' => 'message-to-dealer', 'subject' => 'Új üzenet a vevőtől', 'category' => 'Communication', 'shortcodes' => ['sender_name', 'message_content', 'reply_link']],
            ['name' => 'Jármű érdeklődés', 'slug' => 'vehicle-inquiry', 'subject' => 'Új érdeklődés a következő járművel kapcsolatban: [car_title]', 'category' => 'Inquiry', 'shortcodes' => ['car_title', 'sender_name', 'sender_email', 'sender_phone', 'message_content']],
            ['name' => 'Megtekintési időpont egyeztetése', 'slug' => 'arrange-viewing-time', 'subject' => 'Megtekintési kérelem a következőhöz: [car_title]', 'category' => 'Inquiry', 'shortcodes' => ['car_title', 'sender_name', 'sender_email', 'sender_phone', 'requested_date', 'requested_time', 'message_content']],
        ];

        foreach ($templates as $template) {
            
            // Generate basic body structure for new seed records
            $body = "<div style='font-family: Arial, sans-serif; padding: 20px;'>\n<h2>{$template['subject']}</h2>\n<p>Üdvözöljük!</p>\n<p>Ez egy dinamikus e-mail a következőhöz: {$template['name']}. Az alábbi kódokat használhatja:</p>\n<ul>";
            
            foreach($template['shortcodes'] as $code) {
                $body .= "<li><strong>{$code}:</strong> [{$code}]</li>\n";
            }
            $body .= "</ul>\n<p>Üdvözlettel,<br>CarSwap csapat</p>\n</div>";

            // If it's Welcome or Password, keep original seeded HTML if extending
            if ($template['slug'] == 'welcome') {
                $body = "<div class='header' style='background-color: #dcb377; color: white; padding: 20px; text-align: center; border-radius: 5px;'><h2>Üdvözlünk a CarSwap-en! 🎉</h2></div><div class='content' style='padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px;'><p>Kedves <strong>[first_name]</strong>!</p><p>Köszönjük, hogy csatlakoztál a CarSwap-hez!</p><h3>Kezdjük el a CarSwap használatát:</h3><ul><li><strong>📋 Profil kitöltése:</strong> Adj meg profilképet és részletes adatokat.</li><li><strong>🚗 Böngéssz a hirdetések között:</strong> Fedezd fel járművek ezreit ellenőrzött eladóktól.</li><li><strong>💬 Kapcsolatfelvétel:</strong> Küldj üzenetet az eladóknak és vevőknek közvetlenül.</li></ul></div><p style='text-align: center;'><a href='[frontend_url]' style='background-color: #dcb377; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;'>Böngészés indítása</a></p>";
            } else if ($template['slug'] == 'password-recovery') {
                $body = "<p>Kedves [first_name]!</p><p>Azért kapta ezt az e-mailt, mert jelszó-visszaállítási kérelem érkezett a fiókjához.</p><p style='text-align: center;'><a href='[reset_link]' style='background-color: #dcb377; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;'>Jelszó visszaállítása</a></p><p>Ha nem Ön kérte a jelszó visszaállítását, nincs szükség további intézkedésre.</p>";
            }

            \App\Models\EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                [
                    'name' => $template['name'],
                    'subject' => $template['subject'],
                    'category' => $template['category'],
                    'shortcodes' => $template['shortcodes'],
                    'body' => $body
                ]
            );
        }
    }
}
