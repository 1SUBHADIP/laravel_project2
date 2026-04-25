<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin users
        $this->call([
            AdminUserSeeder::class,
        ]);

        echo "✅ Database seeded successfully!\n";
        echo "📚 Books: " . \App\Models\Book::count() . "\n";
        echo "👥 Members: " . \App\Models\Member::count() . "\n";
        echo "📖 Loans: " . \App\Models\Loan::count() . "\n";
        echo "🏷️ Categories: " . \App\Models\Category::count() . "\n";
    }
}
