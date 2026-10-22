<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            TopicSeeder::class,
            Oxford3000Seeder::class,
            GrammarSeeder::class,
            IpaSeeder::class,
            LearningTipSeeder::class,
        ]);
    }
}
