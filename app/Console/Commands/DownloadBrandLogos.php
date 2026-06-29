<?php

namespace App\Console\Commands;

use App\Models\Brand;
use Illuminate\Console\Command;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DownloadBrandLogos extends Command
{
    protected $signature = 'brands:download-logos
        {--force : Replace existing local logo files}
        {--insecure : Disable SSL verification for local environments with missing CA certificates}
        {--limit= : Download at most this many missing logos in one run}';

    protected $description = 'Download car brand logos from Wikipedia and save them locally.';

    private const BRAND_NAMES = [
        'Abarth', 'Acura', 'AIWAYS', 'Aixam', 'Alfa Romeo', 'Alpine', 'Amc', 'Aro', 'Asia', 'Aston Martin',
        'Audi', 'Austin', 'Austin-Healey', 'Auto Union', 'Autobianchi', 'Baic', 'Barkas', 'Bentley', 'BIRDIE',
        'Bluecar', 'Borgward', 'Bugatti', 'Buick', 'Byd', 'Cadillac', 'Caterham', 'Changan', 'Chery',
        'Chevrolet', 'Chrysler', 'Citroen', 'Cupra', 'Dacia', 'Daewoo', 'Daihatsu', 'DAIMLER', 'Datsun',
        'De Lorean', 'Dkw', 'Dodge', 'Dr', 'Ds', 'Egyedi', 'Electroauto', 'Excalibur', 'Ferrari', 'Fiat',
        'Fisker', 'Ford', 'GAZ', 'Geely', 'Genesis', 'Geo', 'GMC', 'Great Wall', 'Honda', 'Hongqi', 'Hummer',
        'Hyundai', 'Ineos', 'Infiniti', 'Isuzu', 'Iveco', 'Jac', 'Jaecoo', 'Jaguar', 'Jeep', 'Karma', 'Kgm',
        'KGM (SsangYong)', 'Kia', 'KOENIGSEGG', 'Kuba', 'Lacia', 'Lamborghini', 'Lancia', 'Land Rover',
        'Leapmotor', 'Lexus', 'Ligier', 'Lincoln', 'Linktour', 'Lotus', 'Luaz', 'Lynk & Co', 'Mahindra',
        'Man', 'Maruti', 'Maserati', 'Maxus', 'Maybach', 'Mazda', 'McLaren', 'Mercedes-AMG', 'Mercedes-Benz',
        'Mercedes-Maybach', 'Mercury', 'MG', 'Microcar', 'Mini', 'Mitsubishi', 'Morgan', 'Morris', 'Moszkvics',
        'Nio', 'Nissan', 'Nsu', 'Oldsmobile', 'Omoda', 'Opel', 'Peugeot', 'Piaggio', 'Plymouth', 'Polestar',
        'Polski Fiat', 'Pontiac', 'Porsche', 'Reliant', 'Relive', 'Renault', 'Replika', 'Rolls-Royce', 'Rover',
        'Saab', 'Saturn', 'Seat', 'Simca', 'Skywell', 'Smart', 'Skoda', 'Suda', 'Subaru', 'Suzuki', 'Tata',
        'Tatra', 'Tesla', 'Toyota', 'Trabant', 'Triumph', 'UAZ', 'Vauxhall', 'Velorex', 'Versenyauto', 'Volga',
        'Volkswagen', 'Volvo', 'Voyah', 'Wartburg', 'Xev', 'Xiaomi', 'Xpeng', 'Yamaha', 'Yugo', 'Zaporozsec',
        'Zastava', 'Zeekr', 'Ztech', 'EGYEB',
    ];

    private const SEARCH_ALIASES = [
        'Amc' => 'AMC automobile',
        'Asia' => 'Asia Motors',
        'Baic' => 'BAIC Motor',
        'BIRDIE' => 'Birdie car',
        'Byd' => 'BYD Auto',
        'Citroen' => 'Citroen',
        'DAIMLER' => 'Daimler Company',
        'Dkw' => 'DKW',
        'Dr' => 'DR Automobiles',
        'Ds' => 'DS Automobiles',
        'GAZ' => 'GAZ automobile',
        'Genesis' => 'Genesis Motor',
        'Great Wall' => 'Great Wall Motors',
        'Ineos' => 'Ineos Automotive',
        'Jac' => 'JAC Motors',
        'Kgm' => 'KG Mobility',
        'KGM (SsangYong)' => 'KG Mobility',
        'KOENIGSEGG' => 'Koenigsegg',
        'Lacia' => 'Lancia',
        'Luaz' => 'LuAZ',
        'Lynk & Co' => 'Lynk & Co',
        'Man' => 'MAN Truck & Bus',
        'Maruti' => 'Maruti Suzuki',
        'McLaren' => 'McLaren Automotive',
        'Mercedes-AMG' => 'Mercedes-AMG',
        'Mercedes-Maybach' => 'Mercedes-Maybach',
        'MG' => 'MG Motor',
        'Mini' => 'Mini marque',
        'Moszkvics' => 'Moskvitch',
        'Nio' => 'Nio Inc.',
        'Nsu' => 'NSU Motorenwerke',
        'Omoda' => 'Omoda',
        'Polski Fiat' => 'Polski Fiat',
        'Seat' => 'SEAT',
        'Skoda' => 'Skoda Auto',
        'UAZ' => 'UAZ',
        'Versenyauto' => 'race car',
        'Xev' => 'XEV',
        'Xpeng' => 'XPeng',
        'Zaporozsec' => 'Zaporozhets',
        'EGYEB' => 'car',
    ];

    private const BRAND_DOMAINS = [
        'abarth' => 'abarth.com',
        'acura' => 'acura.com',
        'aiways' => 'ai-ways.eu',
        'aixam' => 'aixam.com',
        'alfa-romeo' => 'alfaromeo.com',
        'alpine' => 'alpinecars.com',
        'aston-martin' => 'astonmartin.com',
        'audi' => 'audi.com',
        'bentley' => 'bentleymotors.com',
        'bmw' => 'bmw.com',
        'bugatti' => 'bugatti.com',
        'buick' => 'buick.com',
        'byd' => 'byd.com',
        'cadillac' => 'cadillac.com',
        'caterham' => 'caterhamcars.com',
        'changan' => 'changan.com.cn',
        'chery' => 'cheryinternational.com',
        'chevrolet' => 'chevrolet.com',
        'chrysler' => 'chrysler.com',
        'citroen' => 'citroen.com',
        'cupra' => 'cupraofficial.com',
        'dacia' => 'dacia.com',
        'daewoo' => 'daewoo.com',
        'daihatsu' => 'daihatsu.com',
        'datsun' => 'datsun.com',
        'dodge' => 'dodge.com',
        'dr' => 'drautomobiles.com',
        'ds' => 'dsautomobiles.com',
        'ferrari' => 'ferrari.com',
        'fiat' => 'fiat.com',
        'fisker' => 'fiskerinc.com',
        'ford' => 'ford.com',
        'geely' => 'geely.com',
        'genesis' => 'genesis.com',
        'gmc' => 'gmc.com',
        'great-wall' => 'gwm-global.com',
        'honda' => 'honda.com',
        'hongqi' => 'hongqi-auto.com',
        'hummer' => 'gmc.com',
        'hyundai' => 'hyundai.com',
        'ineos' => 'ineosgrenadier.com',
        'infiniti' => 'infiniti.com',
        'isuzu' => 'isuzu.com',
        'iveco' => 'iveco.com',
        'jac' => 'jac.com.cn',
        'jaecoo' => 'jaecoo.com',
        'jaguar' => 'jaguar.com',
        'jeep' => 'jeep.com',
        'karma' => 'karmaautomotive.com',
        'kgm' => 'kg-mobility.com',
        'kgm-ssangyong' => 'kg-mobility.com',
        'kia' => 'kia.com',
        'koenigsegg' => 'koenigsegg.com',
        'lamborghini' => 'lamborghini.com',
        'lancia' => 'lancia.com',
        'land-rover' => 'landrover.com',
        'leapmotor' => 'leapmotor.com',
        'lexus' => 'lexus.com',
        'ligier' => 'ligier.fr',
        'lincoln' => 'lincoln.com',
        'lynk-co' => 'lynkco.com',
        'mahindra' => 'mahindra.com',
        'maserati' => 'maserati.com',
        'maxus' => 'saicmaxus.com',
        'maybach' => 'mercedes-benz.com',
        'mazda' => 'mazda.com',
        'mclaren' => 'mclaren.com',
        'mercedes-amg' => 'mercedes-amg.com',
        'mercedes-benz' => 'mercedes-benz.com',
        'mercedes-maybach' => 'mercedes-benz.com',
        'mercury' => 'mercuryvehicles.com',
        'mg' => 'mg.co.uk',
        'mini' => 'mini.com',
        'mitsubishi' => 'mitsubishi-motors.com',
        'nio' => 'nio.com',
        'nissan' => 'nissan-global.com',
        'omoda' => 'omodajaecoo.com',
        'opel' => 'opel.com',
        'peugeot' => 'peugeot.com',
        'piaggio' => 'piaggio.com',
        'polestar' => 'polestar.com',
        'porsche' => 'porsche.com',
        'renault' => 'renault.com',
        'rolls-royce' => 'rolls-roycemotorcars.com',
        'saab' => 'saab.com',
        'seat' => 'seat.com',
        'skoda' => 'skoda-auto.com',
        'smart' => 'smart.com',
        'subaru' => 'subaru.com',
        'suzuki' => 'suzuki.com',
        'tata' => 'tatamotors.com',
        'tesla' => 'tesla.com',
        'toyota' => 'toyota.com',
        'uaz' => 'uaz.ru',
        'vauxhall' => 'vauxhall.co.uk',
        'volkswagen' => 'volkswagen.com',
        'volvo' => 'volvocars.com',
        'voyah' => 'voyah.com',
        'xiaomi' => 'mi.com',
        'xpeng' => 'xpeng.com',
        'yamaha' => 'yamaha-motor.eu',
        'zeekr' => 'zeekr.eu',
    ];

    public function handle(): int
    {
        $directory = public_path('assets/img/brands');

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $brands = $this->brandNames();
        $limit = (int) $this->option('limit');

        if ($limit > 0 && !$this->option('force')) {
            $brands = collect($brands)
                ->reject(fn ($brand) => file_exists(Brand::localLogoPath($brand) ?? ''))
                ->take($limit)
                ->values()
                ->all();
        } elseif ($limit > 0) {
            $brands = array_slice($brands, 0, $limit);
        }

        if (!$brands) {
            $this->info('No brand logos need downloading.');

            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar(count($brands));
        $bar->start();

        $downloaded = 0;
        $skipped = 0;
        $failed = [];

        foreach ($brands as $brand) {
            $path = Brand::localLogoPath($brand);

            if (!$path) {
                $bar->advance();
                continue;
            }

            if (file_exists($path) && !$this->option('force')) {
                $skipped++;
                $bar->advance();
                continue;
            }

            try {
                if (!$this->downloadFromAvailableSources($brand, $path)) {
                    $failed[] = $brand;
                    $bar->advance();
                    continue;
                }
            } catch (\Throwable) {
                $failed[] = $brand;
                $bar->advance();
                continue;
            }

            $downloaded++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Downloaded: {$downloaded}. Skipped existing: {$skipped}. Failed: " . count($failed) . '.');

        if ($failed) {
            $this->warn('Failed brands: ' . implode(', ', $failed));
        }

        return self::SUCCESS;
    }

    private function brandNames(): array
    {
        $databaseBrands = Brand::query()
            ->pluck('name')
            ->all();

        return collect(array_merge(self::BRAND_NAMES, $databaseBrands))
            ->filter()
            ->unique(fn ($brand) => Str::lower($brand))
            ->values()
            ->all();
    }

    private function findWikipediaLogoUrl(string $brand): ?string
    {
        $query = self::SEARCH_ALIASES[$brand] ?? "{$brand} automobile logo";

        $response = $this->http()
            ->retry(2, 500)
            ->get('https://en.wikipedia.org/w/api.php', [
                'action' => 'query',
                'format' => 'json',
                'generator' => 'search',
                'gsrsearch' => $query,
                'gsrlimit' => 5,
                'prop' => 'pageimages|images',
                'pithumbsize' => 512,
                'pilicense' => 'any',
                'imlimit' => 50,
            ]);

        if (!$response->ok()) {
            return null;
        }

        $pages = collect($response->json('query.pages', []))
            ->sortBy('index');

        foreach ($pages as $page) {
            $thumbnail = data_get($page, 'thumbnail.source');

            if ($thumbnail) {
                return $thumbnail;
            }
        }

        $imageTitle = $this->bestLogoImageTitle($pages->pluck('images')->flatten(1)->all());

        return $imageTitle ? $this->imageInfoUrl($imageTitle) : null;
    }

    private function downloadFromAvailableSources(string $brand, string $path): bool
    {
        foreach ($this->candidateLogoUrls($brand) as $url) {
            if ($this->downloadLogo($url, $path)) {
                return true;
            }
        }

        return false;
    }

    private function candidateLogoUrls(string $brand): array
    {
        $slug = Brand::logoSlug($brand);
        $urls = array_filter([
            $this->findWikipediaLogoUrl($brand),
            "https://seeklogo.com/images/{$slug}-logo",
            "https://www.carlogos.org/car-logos/{$slug}-logo.png",
            "https://cdn.freebiesupply.com/logos/large/2x/{$slug}-logo-png-transparent.png",
        ]);

        if ($domain = $this->brandDomain($brand)) {
            $urls[] = "https://logo.clearbit.com/{$domain}";
            $urls[] = "https://www.google.com/s2/favicons?domain={$domain}&sz=128";
        }

        $urls[] = "https://simpleicons.org/icons/{$slug}.svg";
        $urls[] = "https://brandfetch.com/api/logos/{$slug}";

        return array_values(array_unique($urls));
    }

    private function brandDomain(string $brand): ?string
    {
        $slug = Brand::logoSlug($brand);
        $configuredDomains = config('vehicle_brand_domains', []);

        return $configuredDomains[$slug] ?? self::BRAND_DOMAINS[$slug] ?? null;
    }

    private function bestLogoImageTitle(array $images): ?string
    {
        return collect($images)
            ->pluck('title')
            ->filter()
            ->sortByDesc(function (string $title) {
                $normalized = Str::lower($title);

                return (int) Str::contains($normalized, ['logo', 'wordmark'])
                    + (int) Str::contains($normalized, ['emblem', 'badge']) * 0.8
                    + (int) Str::contains($normalized, ['svg']) * 0.5
                    - (int) Str::contains($normalized, ['commons-logo', 'symbol support vote']) * 5;
            })
            ->first();
    }

    private function imageInfoUrl(string $imageTitle): ?string
    {
        $response = $this->http()
            ->retry(2, 500)
            ->get('https://en.wikipedia.org/w/api.php', [
                'action' => 'query',
                'format' => 'json',
                'titles' => $imageTitle,
                'prop' => 'imageinfo',
                'iiprop' => 'url',
                'iiurlwidth' => 512,
            ]);

        if (!$response->ok()) {
            return null;
        }

        $page = collect($response->json('query.pages', []))->first();

        return data_get($page, 'imageinfo.0.thumburl') ?: data_get($page, 'imageinfo.0.url');
    }

    private function downloadLogo(string $url, string $path): bool
    {
        $response = $this->http()
            ->retry(2, 500)
            ->get($url);

        if (!$response->ok() || !$response->body()) {
            return false;
        }

        $contentType = $response->header('Content-Type', '');

        if (Str::contains($contentType, ['image/svg', 'text/xml', 'application/xml'])) {
            return false;
        }

        if (Str::contains($contentType, 'text/html')) {
            $imageUrl = $this->extractImageUrlFromHtml($response->body(), $url);

            return $imageUrl ? $this->downloadLogo($imageUrl, $path) : false;
        }

        return $this->saveAsPng($response->body(), $path);
    }

    private function extractImageUrlFromHtml(string $html, string $baseUrl): ?string
    {
        if (!preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $match)
            && !preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/i', $html, $match)
        ) {
            return null;
        }

        $url = html_entity_decode($match[1]);

        if (Str::startsWith($url, '//')) {
            return 'https:' . $url;
        }

        if (Str::startsWith($url, '/')) {
            $parts = parse_url($baseUrl);

            return ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '') . $url;
        }

        return $url;
    }

    private function saveAsPng(string $imageContents, string $path): bool
    {
        $image = @imagecreatefromstring($imageContents);

        if (!$image) {
            return false;
        }

        imagesavealpha($image, true);
        $saved = imagepng($image, $path);
        imagedestroy($image);

        return $saved;
    }

    private function http(): PendingRequest
    {
        $request = Http::timeout(30)
            ->withHeaders(['User-Agent' => config('app.name', 'CarSwap') . '/1.0']);

        return $this->option('insecure') ? $request->withoutVerifying() : $request;
    }
}
