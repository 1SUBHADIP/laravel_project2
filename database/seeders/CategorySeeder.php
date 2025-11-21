<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Fiction',
                'description' => 'Literary works of fiction including novels, short stories, and plays'
            ],
            [
                'name' => 'Science',
                'description' => 'Scientific books covering physics, chemistry, biology, and general science'
            ],
            [
                'name' => 'Technology',
                'description' => 'Books about technology, programming, computer science, and engineering'
            ],
            [
                'name' => 'History',
                'description' => 'Historical books, biographies of historical figures, and historical analysis'
            ],
            [
                'name' => 'Biography',
                'description' => 'Life stories and autobiographies of notable people'
            ],
            [
                'name' => 'Self-Help',
                'description' => 'Personal development, motivation, and self-improvement books'
            ],
            [
                'name' => 'Business',
                'description' => 'Business management, entrepreneurship, and economics'
            ],
            [
                'name' => 'Philosophy',
                'description' => 'Philosophical works and studies of thought and ethics'
            ],
            [
                'name' => 'Art',
                'description' => 'Art history, art techniques, and visual arts'
            ],
            [
                'name' => 'Education',
                'description' => 'Educational materials, textbooks, and academic resources'
            ]
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
