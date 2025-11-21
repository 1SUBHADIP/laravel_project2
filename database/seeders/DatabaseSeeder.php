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
        // Create categories first
        $this->call([
            CategorySeeder::class,
        ]);

        // Create books (depends on categories)
        $this->call([
            BookSeeder::class,
        ]);

        // Create members
        $this->call([
            MemberSeeder::class,
        ]);

        // Create loans (depends on books and members)
        $this->call([
            LoanSeeder::class,
        ]);

        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@cclms.test'],
            [
                'name' => 'CCLMS Admin',
                'is_admin' => true,
                'password' => Hash::make('password'),
            ]
        );

        echo "✅ Database seeded successfully!\n";
        echo "📚 Books: " . \App\Models\Book::count() . "\n";
        echo "👥 Members: " . \App\Models\Member::count() . "\n";
        echo "📖 Loans: " . \App\Models\Loan::count() . "\n";
        echo "🏷️ Categories: " . \App\Models\Category::count() . "\n";
        echo "👤 Admin User: admin@cclms.test / password\n";
    }
}
