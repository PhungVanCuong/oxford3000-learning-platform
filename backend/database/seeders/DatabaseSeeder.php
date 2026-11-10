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
            TopicSeeder::class,
            Oxford3000Seeder::class,
            PhrasalVerbSeeder::class,
            CurriculumRoadmapSeeder::class,
            GrammarPdfFullSeeder::class,
            UpdateGrammarExercisesSeeder::class,
            IpaSeeder::class,
            LearningTipSeeder::class,
            UserSeeder::class,
            ArenaSampleSeeder::class,
        ]);
    }
}
