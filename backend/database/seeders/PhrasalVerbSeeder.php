<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhrasalVerb;
use Illuminate\Support\Facades\Schema;

class PhrasalVerbSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        PhrasalVerb::truncate();
        Schema::enableForeignKeyConstraints();

        $jsonPath = database_path('data/phrasal_verbs_and_phrases.json');
        if (!file_exists($jsonPath)) {
            $this->command->error("Data file not found: {$jsonPath}");
            return;
        }

        $records = json_decode(file_get_contents($jsonPath), true);
        if (!is_array($records)) {
            $this->command->error("Invalid JSON data in {$jsonPath}");
            return;
        }

        foreach (array_chunk($records, 100) as $chunk) {
            foreach ($chunk as &$row) {
                if (is_array($row['component_words_json'])) {
                    $row['component_words_json'] = json_encode($row['component_words_json'], JSON_UNESCAPED_UNICODE);
                }
                $row['created_at'] = now();
                $row['updated_at'] = now();
            }
            PhrasalVerb::insert($chunk);
        }

        $this->command->info("Successfully seeded " . count($records) . " phrasal verbs and common phrases!");
    }
}
