<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shop;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */ 
    public function run(): void
    {
        // User::factory(10)->create();
        Shop::create([
            'user_id' => 1,  // Siguraduhin may user na with ID 1
            'name_shop' => 'Sample Shop',
            'desc' => 'This is a sample shop description.',
            'phone' => 9123456789,
            'address' => '123 Sample Street, Sample City',
            'path' => '/sample-shop',
        ]);
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
