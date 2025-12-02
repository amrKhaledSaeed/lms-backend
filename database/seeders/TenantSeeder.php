<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds for tenant-specific data.
     */
    public function run(): void
    {
        // Example: Seed posts for a tenant
        DB::table('posts')->insert([
            [
                'title' => 'Welcome to Your Tenant',
                'content' => 'This is your first post in the multi-tenant system!',
                'published' => true,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Getting Started',
                'content' => 'Here are some tips to get started with your tenant dashboard.',
                'published' => true,
                'user_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}


