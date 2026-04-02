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
                'name' => 'General Terms and Conditions',
                'slug' => 'general-terms-and-conditions',
                'title' => 'General Terms and Conditions',
                'subtitle' => 'Legal agreement between the service and the users.',
                'description' => 'This section contains the official terms and conditions for using CarSwap.',
                'status' => true,
            ],
            [
                'name' => 'Data Protection Notice',
                'slug' => 'data-protection-notice',
                'title' => 'Data Protection Notice',
                'subtitle' => 'Information about how we handle your data.',
                'description' => 'This section contains information about data privacy and protection policies.',
                'status' => true,
            ],
        ];

        foreach ($legalSections as $sectionData) {
            $section = CMSSection::firstOrCreate(
                ['slug' => $sectionData['slug']],
                $sectionData
            );

            // Create a default item for each section to hold the main content
            CMSItem::firstOrCreate(
                [
                    'section_id' => $section->id,
                    'title' => 'Main Content',
                ],
                [
                    'description' => 'Please enter the content here...',
                    'order' => 1,
                    'status' => true,
                ]
            );
        }
    }
}
