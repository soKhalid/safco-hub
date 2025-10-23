<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Company Events',
                'slug' => 'company-events',
                'description' => 'Photos and videos from company events, conferences, and gatherings',
                'icon' => 'calendar-event',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Marketing Materials',
                'slug' => 'marketing-materials',
                'description' => 'Marketing assets, brochures, and promotional materials',
                'icon' => 'megaphone',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Product Images',
                'slug' => 'product-images',
                'description' => 'Product photos and documentation',
                'icon' => 'box-seam',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Training Videos',
                'slug' => 'training-videos',
                'description' => 'Educational and training video content',
                'icon' => 'play-btn',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Documentation',
                'slug' => 'documentation',
                'description' => 'Technical documentation, guides, and manuals',
                'icon' => 'file-text',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Team Photos',
                'slug' => 'team-photos',
                'description' => 'Team member photos and group pictures',
                'icon' => 'people',
                'order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Presentations',
                'slug' => 'presentations',
                'description' => 'PowerPoint presentations and slideshows',
                'icon' => 'easel',
                'order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Reports',
                'slug' => 'reports',
                'description' => 'Annual reports, financial documents, and analytics',
                'icon' => 'graph-up',
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('Categories created successfully!');
    }
}
