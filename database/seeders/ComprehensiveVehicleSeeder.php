<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\VehicleModel;

class ComprehensiveVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleData = [
            'Acura' => ['ILX', 'MDX', 'NSX', 'RDX', 'RL', 'RLX', 'RSX', 'TL', 'TLX', 'TSX', 'ZDX'],
            'Alfa Romeo' => ['147', '156', '159', '166', '4C', '8C', 'Brera', 'Giulia', 'Giulietta', 'GT', 'GTV', 'MiTo', 'Spider', 'Stelvio', 'Tonale'],
            'Aston Martin' => ['DB7', 'DB9', 'DB11', 'DBS', 'DBX', 'Rapide', 'Vantage', 'Vanquish', 'Virage'],
            'Audi' => ['A1', 'A2', 'A3', 'A4', 'A5', 'A6', 'A7', 'A8', 'Q2', 'Q3', 'Q4 e-tron', 'Q5', 'Q7', 'Q8', 'R8', 'RS3', 'RS4', 'RS5', 'RS6', 'RS7', 'S1', 'S3', 'S4', 'S5', 'S6', 'S7', 'S8', 'SQ2', 'SQ5', 'SQ7', 'SQ8', 'TT', 'TT RS', 'e-tron', 'e-tron GT'],
            'Bentley' => ['Arnage', 'Azure', 'Bentayga', 'Brooklands', 'Continental GT', 'Flying Spur', 'Mulsanne'],
            'BMW' => ['1 Series', '2 Series', '3 Series', '4 Series', '5 Series', '6 Series', '7 Series', '8 Series', 'i3', 'i4', 'i8', 'iX', 'iX3', 'i7', 'M1', 'M2', 'M3', 'M4', 'M5', 'M6', 'M8', 'X1', 'X2', 'X3', 'X4', 'X5', 'X6', 'X7', 'Z1', 'Z3', 'Z4', 'Z8'],
            'Bugatti' => ['Chiron', 'Divo', 'Veyron'],
            'Buick' => ['Enclave', 'Encore', 'Envision', 'LaCrosse', 'Regal', 'Verano'],
            'Cadillac' => ['ATS', 'CT4', 'CT5', 'CT6', 'CTS', 'DTS', 'Escalade', 'SRX', 'STS', 'XT4', 'XT5', 'XT6', 'XTS'],
            'Chevrolet' => ['Aveo', 'Blazer', 'Bolt', 'Camaro', 'Captiva', 'Colorado', 'Corvette', 'Cruze', 'Equinox', 'Impala', 'Malibu', 'Orlando', 'Silverado', 'Spark', 'Suburban', 'Tahoe', 'Traverse', 'Trax', 'Volt'],
            'Chrysler' => ['300', 'Aspen', 'Crossfire', 'Pacifica', 'PT Cruiser', 'Sebring', 'Town & Country', 'Voyager'],
            'Citroen' => ['Berlingo', 'C1', 'C2', 'C3', 'C3 Aircross', 'C3 Picasso', 'C4', 'C4 Cactus', 'C4 Picasso', 'C4 Spacetourer', 'C4 X', 'C5', 'C5 Aircross', 'C5 X', 'C6', 'C8', 'C-Crosser', 'C-Elysee', 'DS3', 'DS4', 'DS5', 'Jumper', 'Jumpy', 'Nemo', 'Xsara'],
            'Cupra' => ['Ateca', 'Born', 'Formentor', 'Leon', 'Tavascan'],
            'Dacia' => ['Dokker', 'Duster', 'Jogger', 'Lodgy', 'Logan', 'Sandero', 'Spring'],
            'Daewoo' => ['Kalos', 'Lacetti', 'Lanos', 'Matiz', 'Nubira', 'Tacuma'],
            'Daihatsu' => ['Charade', 'Cuore', 'Materia', 'Sirion', 'Terios', 'Trevis'],
            'Dodge' => ['Avenger', 'Caliber', 'Challenger', 'Charger', 'Dakota', 'Dart', 'Durango', 'Grand Caravan', 'Journey', 'Magnum', 'Nitro', 'Ram', 'Viper'],
            'Ferrari' => ['296 GTB', '360', '430', '458', '488', '599', '812', 'California', 'Enzo', 'F12', 'F40', 'F50', 'F8', 'FF', 'LaFerrari', 'Portofino', 'Purosangue', 'Roma', 'SF90'],
            'Fiat' => ['124 Spider', '500', '500L', '500X', 'Barchetta', 'Brava', 'Bravo', 'Croma', 'Doblo', 'Ducato', 'Fiorino', 'Freemont', 'Fullback', 'Grande Punto', 'Idea', 'Linea', 'Marea', 'Multipla', 'Panda', 'Punto', 'Punto Evo', 'Qubo', 'Scudo', 'Sedici', 'Stilo', 'Talento', 'Tipo', 'Ulysse'],
            'Ford' => ['B-Max', 'C-Max', 'EcoSport', 'Edge', 'Explorer', 'F-150', 'Fiesta', 'Focus', 'Fusion', 'Galaxy', 'GT', 'Ka', 'Ka+', 'Kuga', 'Maverick', 'Mondeo', 'Mustang', 'Mustang Mach-E', 'Puma', 'Ranger', 'S-Max', 'Streetka', 'Tourneo', 'Transit'],
            'GMC' => ['Acadia', 'Canyon', 'Envoy', 'Savana', 'Sierra', 'Terrain', 'Yukon'],
            'Honda' => ['Accord', 'Civic', 'CR-V', 'CR-Z', 'Element', 'FR-V', 'Honda e', 'HR-V', 'Insight', 'Jazz', 'Legend', 'NSX', 'Odyssey', 'Pilot', 'Prelude', 'Ridgeline', 'S2000'],
            'Hummer' => ['H1', 'H2', 'H3'],
            'Hyundai' => ['Accent', 'Bayon', 'Coupe', 'Elantra', 'Genesis', 'Getz', 'i10', 'i20', 'i30', 'i40', 'Ioniq', 'Ioniq 5', 'Ioniq 6', 'ix20', 'ix35', 'ix55', 'Kona', 'Lantra', 'Matrix', 'Palisade', 'Santa Fe', 'Sonata', 'Staria', 'Terracan', 'Tiburon', 'Trajet', 'Tucson', 'Veloster', 'Venue'],
            'Infiniti' => ['EX', 'FX', 'G', 'JX', 'M', 'Q30', 'Q50', 'Q60', 'Q70', 'QX30', 'QX50', 'QX60', 'QX70', 'QX80'],
            'Isuzu' => ['D-Max', 'Trooper'],
            'Jaguar' => ['E-Pace', 'F-Pace', 'F-Type', 'I-Pace', 'S-Type', 'XE', 'XF', 'XJ', 'XK', 'X-Type'],
            'Jeep' => ['Avenger', 'Cherokee', 'Commander', 'Compass', 'Gladiator', 'Grand Cherokee', 'Patriot', 'Renegade', 'Wrangler'],
            'Kia' => ['Carens', 'Carnival', 'Ceed', 'EV6', 'EV9', 'Magentis', 'Niro', 'Opirus', 'Optima', 'Picanto', 'Proceed', 'Rio', 'Sorento', 'Soul', 'Sportage', 'Stinger', 'Stonic', 'Venga', 'XCeed'],
            'Lamborghini' => ['Aventador', 'Countach', 'Diablo', 'Gallardo', 'Huracan', 'Murcielago', 'Revuelto', 'Urus'],
            'Lancia' => ['Delta', 'Kappa', 'Lybra', 'Musa', 'Phedra', 'Thema', 'Thesis', 'Voyager', 'Ypsilon', 'Zeta'],
            'Land Rover' => ['Defender', 'Discovery', 'Discovery Sport', 'Freelander', 'Range Rover', 'Range Rover Evoque', 'Range Rover Sport', 'Range Rover Velar'],
            'Lexus' => ['CT', 'ES', 'GS', 'GX', 'HS', 'IS', 'LC', 'LFA', 'LS', 'LX', 'NX', 'RC', 'RX', 'RZ', 'SC', 'UX'],
            'Lincoln' => ['Aviator', 'Continental', 'Corsair', 'MKC', 'MKS', 'MKT', 'MKX', 'MKZ', 'Navigator', 'Nautilus'],
            'Lotus' => ['Elise', 'Emira', 'Eletre', 'Esprit', 'Europa', 'Evora', 'Exige'],
            'Maserati' => ['Ghibli', 'Grancabrio', 'Granturismo', 'Grecale', 'Levante', 'MC20', 'Quattroporte', 'Spyder'],
            'Mazda' => ['2', '3', '5', '6', 'BT-50', 'CX-3', 'CX-30', 'CX-5', 'CX-7', 'CX-9', 'CX-60', 'CX-80', 'MX-30', 'MX-5', 'Premacy', 'RX-7', 'RX-8', 'Tribute'],
            'McLaren' => ['540C', '570GT', '570S', '600LT', '650S', '675LT', '720S', '750S', '765LT', 'Artura', 'GT', 'P1', 'Senna'],
            'Mercedes-Benz' => ['A-Class', 'AMG GT', 'B-Class', 'C-Class', 'CL', 'CLA', 'CLC', 'CLK', 'CLS', 'E-Class', 'EQA', 'EQB', 'EQC', 'EQE', 'EQS', 'EQV', 'G-Class', 'GL', 'GLA', 'GLB', 'GLC', 'GLE', 'GLK', 'GLS', 'M-Class', 'R-Class', 'S-Class', 'SL', 'SLC', 'SLK', 'SLR', 'SLS AMG', 'V-Class', 'Vaneo', 'Viano', 'Vito', 'X-Class'],
            'MG' => ['EHS', 'HS', 'Marvel R', 'MG3', 'MG4', 'MG5', 'ZS'],
            'Mini' => ['Cabrio', 'Clubman', 'Countryman', 'Coupe', 'Hatch', 'Paceman', 'Roadster'],
            'Mitsubishi' => ['ASX', 'Carisma', 'Colt', 'Eclipse', 'Eclipse Cross', 'Galant', 'Grandis', 'i-MiEV', 'L200', 'Lancer', 'Mirage', 'Outlander', 'Pajero', 'Pajero Sport', 'Space Star'],
            'Nissan' => ['350Z', '370Z', 'Almera', 'Almera Tino', 'Ariya', 'GT-R', 'Juke', 'Leaf', 'Maxima', 'Micra', 'Murano', 'Navara', 'Note', 'NV200', 'NV300', 'NV400', 'Pathfinder', 'Patrol', 'Pulsar', 'Qashqai', 'Qashqai+2', 'Tiida', 'Townstar', 'X-Trail', 'Z'],
            'Opel' => ['Adam', 'Agila', 'Ampera', 'Ampera-e', 'Antara', 'Astra', 'Cascada', 'Combo', 'Corsa', 'Crossland', 'Crossland X', 'Frontera', 'Grandland', 'Grandland X', 'Insignia', 'Karl', 'Meriva', 'Mokka', 'Mokka X', 'Movano', 'Omega', 'Signum', 'Tigra', 'Vectra', 'Vivaro', 'Zafira', 'Zafira Life'],
            'Peugeot' => ['1007', '107', '108', '2008', '206', '207', '208', '3008', '301', '307', '308', '4007', '4008', '407', '408', '5008', '508', '607', '807', 'Bipper', 'Expert', 'ion', 'Partner', 'Rcz', 'Rifter', 'Traveller'],
            'Porsche' => ['718 Boxster', '718 Cayman', '911', 'Boxster', 'Cayenne', 'Cayman', 'Macan', 'Panamera', 'Taycan'],
            'Renault' => ['Alaskan', 'Arkana', 'Austral', 'Captur', 'Clio', 'Espace', 'Express', 'Fluence', 'Grand Espace', 'Grand Scenic', 'Kadjar', 'Kangoo', 'Koleos', 'Laguna', 'Latitude', 'Master', 'Megane', 'Megane E-Tech', 'Modus', 'Rafale', 'Scenic', 'Symbol', 'Talisman', 'Thalia', 'Trafic', 'Twingo', 'Twizy', 'Vel Satis', 'Wind', 'Zoe'],
            'Rolls-Royce' => ['Cullinan', 'Dawn', 'Ghost', 'Phantom', 'Spectre', 'Wraith'],
            'Saab' => ['9-3', '9-5', '9-7X'],
            'Seat' => ['Alhambra', 'Altea', 'Altea XL', 'Arona', 'Arosa', 'Ateca', 'Cordoba', 'Exeo', 'Ibiza', 'Inca', 'Leon', 'Mii', 'Tarraco', 'Toledo'],
            'Skoda' => ['Citigo', 'Enyaq iV', 'Fabia', 'Kamiq', 'Karoq', 'Kodiaq', 'Octavia', 'Praktik', 'Rapid', 'Roomster', 'Scala', 'Superb', 'Yeti'],
            'Smart' => ['#1', '#3', 'Forfour', 'Fortwo', 'Roadster'],
            'SsangYong' => ['Actyon', 'Korando', 'Musso', 'Rexton', 'Rodius', 'Tivoli', 'Torres', 'XLV'],
            'Subaru' => ['B9 Tribeca', 'BRZ', 'Forester', 'Impreza', 'Justy', 'Legacy', 'Levorg', 'Outback', 'Solterra', 'Trezia', 'WRX STI', 'XV'],
            'Suzuki' => ['Across', 'Alto', 'Baleno', 'Celerio', 'Grand Vitara', 'Ignis', 'Jimny', 'Kizashi', 'Liana', 'S-Cross', 'Splash', 'Swace', 'Swift', 'SX4', 'SX4 S-Cross', 'Vitara', 'Wagon R+'],
            'Tesla' => ['Model 3', 'Model S', 'Model X', 'Model Y', 'Roadster'],
            'Toyota' => ['Aurion', 'Auris', 'Avensis', 'Avensis Verso', 'Aygo', 'Aygo X', 'bZ4X', 'C-HR', 'Camry', 'Celica', 'Corolla', 'Corolla Cross', 'Corolla Verso', 'GT86', 'GR86', 'Highlander', 'Hilux', 'IQ', 'Land Cruiser', 'Mirai', 'MR2', 'Previa', 'Prius', 'Prius+', 'Proace', 'Proace City', 'RAV4', 'Supra', 'Urban Cruiser', 'Verso', 'Verso-S', 'Yaris', 'Yaris Cross'],
            'Volkswagen' => ['Amarok', 'Arteon', 'Beetle', 'Bora', 'Caddy', 'California', 'CC', 'Crafter', 'Eos', 'Fox', 'Golf', 'Golf Plus', 'Golf Sportsvan', 'ID.3', 'ID.4', 'ID.5', 'ID.7', 'ID. Buzz', 'Jetta', 'Lupo', 'Multivan', 'Passat', 'Passat CC', 'Phaeton', 'Polo', 'Scirocco', 'Sharan', 'T-Cross', 'T-Roc', 'Taigo', 'Tiguan', 'Tiguan Allspace', 'Touareg', 'Touran', 'Transporter', 'Up!'],
            'Volvo' => ['C30', 'C40', 'C70', 'EX30', 'EX90', 'S40', 'S60', 'S80', 'S90', 'V40', 'V50', 'V60', 'V70', 'V90', 'XC40', 'XC60', 'XC70', 'XC90'],
        ];

        foreach ($vehicleData as $brandName => $models) {
            $brand = Brand::firstOrCreate(['name' => $brandName]);
            
            foreach ($models as $modelName) {
                VehicleModel::firstOrCreate([
                    'brand_id' => $brand->id,
                    'name' => $modelName,
                ]);
            }
        }
    }
}
