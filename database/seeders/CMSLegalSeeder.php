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
            [
                'name' => 'Frequently Asked Questions',
                'slug' => 'faq',
                'title' => 'Frequently Asked Questions',
                'subtitle' => 'Answers to common questions about using CarSwap.',
                'description' => 'A comprehensive list of common questions and answers.',
                'status' => true,
            ],
            [
                'name' => 'Join our mailing list',
                'slug' => 'mailing-list-info',
                'title' => 'Join our mailing list!',
                'subtitle' => 'Subscribe to our newsletter.',
                'description' => 'Content for the newsletter subscription area.',
                'status' => true,
            ],
        ];

        foreach ($legalSections as $sectionData) {
            $section = CMSSection::updateOrCreate(
                ['slug' => $sectionData['slug']],
                $sectionData
            );

            // Detailed content for General Terms and Conditions if provided
            $itemContent = 'Please enter the content here...';
            if ($sectionData['slug'] === 'general-terms-and-conditions') {
                $itemContent = '
                    <h4>General Terms and Conditions</h4>
                    <p>Regarding the website <a href="https://carswap.hexafume.com">https://carswap.hexafume.com</a> (hereinafter: Website) and the services of the Company.</p>
                    <p>The service is provided to the Clients by Swap Group Limited Liability Company (hereinafter: Company) – as the operating Company – and its partners in a contractual relationship with it, in accordance with these general terms and conditions (hereinafter: GTC) in connection with the use of the activities performed by the Company on the Website.</p>
                    <p>By using the services available on the Company\'s Website, a contractual relationship is established between the Client and the Company in accordance with the terms of these GTC, which, in the absence of a different agreement, is deemed to be concluded electronically and shall be deemed to be in writing pursuant to Section 6:7. (3) of Act V of 2013 on the Civil Code (hereinafter: the Civil Code) (hereinafter: the Contract).</p>

                    <h4>Merchant & Private Clients</h4>
                    <p>For the purposes of these GTC, a Customer is any person who views the Website, places an advertisement, offers a product for sale or makes an offer to purchase a product, i.e. applies for an advertisement (hereinafter: Customer). The Company distinguishes the following categories of Customers in these GTC:</p>
                    <ul>
                        <li><strong>Merchant:</strong> The Customer who defines himself as a Merchant Customer after the first login to the admin account. Merchant status is displayed with a "Trade" label.</li>
                        <li><strong>Private Client:</strong> A Client posting an advertisement who is not a Merchant.</li>
                    </ul>

                    <h4>1. COMPANY DETAILS</h4>
                    <p>Name: Swap Group Limited Liability Company<br>
                    Headquarters: 1039 Budapest, Álmos Street 3.<br>
                    Company registration number: 01-09-423632<br>
                    Tax number: 32429073-2-41<br>
                    Email: swapgroupkft@gmail.com<br>
                    Phone number: +36305990290</p>

                    <h4>3. SUBSCRIPTION SERVICES OFFERED BY US</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Services</th>
                                <th>Awards</th>
                                <th>Payment due date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Free package</td>
                                <td>0 HUF / month</td>
                                <td>no payment required</td>
                            </tr>
                            <tr>
                                <td>I have several cars, package</td>
                                <td>21,990 HUF + VAT / month</td>
                                <td>monthly in advance</td>
                            </tr>
                            <tr>
                                <td>Merchant package</td>
                                <td>39,990,- Ft + VAT / month</td>
                                <td>monthly in advance</td>
                            </tr>
                        </tbody>
                    </table>

                    <p><em>Effective date: May 28, 2025.</em></p>
                ';
            }

            if ($sectionData['slug'] === 'faq') {
                $faqs = [
                    ['q' => 'How do I register on CARSWAP?', 'a' => 'You can register by clicking the Register button and providing your details...'],
                    ['q' => 'What documents are required to advertise a car?', 'a' => 'To post an ad, you will need the vehicle\'s registration certificate, registration book, service book, and technical inspection document.'],
                    ['q' => 'Is it possible for me to purchase the car directly, without a trade-in?', 'a' => 'Yes, users can buy vehicles directly if the seller allows it.'],
                    ['q' => 'How does the car swap function work?', 'a' => 'Car Swap allows you to trade your vehicle for another...'],
                    ['q' => 'Is it possible to exchange multiple cars at once?', 'a' => 'Currently,CarSwap supports 1:1 or N:1 swaps depending on...'],
                    ['q' => 'What happens if my exchange request is rejected?', 'a' => 'You will be notified and can make other offers...'],
                    ['q' => 'How can I receive notifications about cars that interest me?', 'a' => 'You can save searches or add vehicles to your favorites...'],
                    ['q' => 'What filtering options are available to me?', 'a' => 'We offer filtering by Brand, Model, Year, Fuel Type, and more.'],
                    ['q' => 'What should I do if I have a problem with another user?', 'a' => 'Please contact our support team immediately.'],
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
                    <h4>Join our mailing list!</h4>
                    <p>Would you like to be the first to know about new products, secret deals, or inspiring content? Subscribe to our newsletter and we guarantee that you will only receive useful, interesting, or smile-inducing messages - we hate spam too.</p>
                    <p>A few letters a month, nothing superfluous, just the essentials.</p>
                ';
            }

            // Create or update the default item for each section (T&C, Privacy, Mailing list)
            CMSItem::updateOrCreate(
                [
                    'section_id' => $section->id,
                    'title' => 'Main Content',
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
