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
            PhrasalVerbSeeder::class,
            CurriculumRoadmapSeeder::class,
            GrammarPdfFullSeeder::class,
            UpdateGrammarExercisesSeeder::class,
            ArenaSampleSeeder::class,
            IpaSeeder::class,
            LearningTipSeeder::class,
        ]);
    }
}
