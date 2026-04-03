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
                'name' => 'Blog Posts',
                'title' => 'Latest News & Guides',
                'description' => 'Manage all blog posts and articles for the website.',
                'status' => 1,
            ]
        );

        $posts = [
            [
                'title' => 'What do you look for before buying a car?',
                'description' => '<p>Buying a car can be an overwhelming experience. You need to consider the budget, the specific needs you have, and the type of vehicle that fits your lifestyle. In this post, we discuss the top 10 things you must check before making a final decision.</p>',
                'link' => json_encode(['date' => '2024.04.17.', 'comments' => 4]),
                'order' => 1
            ],
            [
                'title' => 'Car exchange - Simple and great',
                'description' => '<p>Exchanging your car shouldn\'t be a hassle. With CARSWAP, the process is streamlined to ensure you get the best value without the traditional headaches. Learn how our platform simplifies the car exchange process from start to finish.</p>',
                'link' => json_encode(['date' => '2024.04.17.', 'comments' => 5]),
                'order' => 2
            ],
            [
                'title' => 'What you NEED to know about the sales contract!',
                'description' => '<p>A clear and legally binding sales contract protects both the buyer and the seller. This article covers the essential clauses that should be included in every vehicle sales agreement and how to interpret them correctly.</p>',
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
