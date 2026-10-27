<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Vocabulary;
use App\Models\User;
use App\Models\UserVocabularyProgress;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class Oxford3000Seeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        UserVocabularyProgress::truncate();
        Vocabulary::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $jsonPath = database_path('data/oxford_3000.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("File $jsonPath not found!");
            return;
        }

        $words = json_decode(File::get($jsonPath), true);
        $topics = Topic::all();
        $topicCount = $topics->count();

        $this->command->info("Seeding " . count($words) . " vocabulary items across $topicCount topics...");

        $batch = [];
        $now = now();

        foreach ($words as $idx => $item) {
            $topic = $topicCount > 0 ? $topics[$idx % $topicCount] : null;

            $batch[] = [
                'id' => $idx + 1, // Explicit 1-based sequential ID
                'topic_id' => $topic ? $topic->id : null,
                'word' => $item['word'],
                'phonetic_us' => $item['phonetic_us'] ?? '',
                'part_of_speech' => $item['part_of_speech'] ?? '',
                'meaning_vi' => $item['meaning_vi'] ?? '',
                'example_en' => $item['example_en'] ?? '',
                'example_vi' => $item['example_vi'] ?? '',
                'audio_url' => $item['audio_url'] ?? null,
                'cefr_level' => $item['cefr_level'] ?? 'A1',
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) >= 200) {
                Vocabulary::insert($batch);
                $batch = [];
            }
        }

        if (count($batch) > 0) {
            Vocabulary::insert($batch);
        }

        $this->command->info("Successfully seeded all 3000+ Oxford vocabulary entries!");

        // Pre-seed progress for main student account (Cường Phùng)
        $cuong = User::where('email', 'cuongphung1810@gmail.com')->first();
        if ($cuong) {
            // Day 1 has 15 words: let's mark 10 as mastered, 4 as learning
            $day1Words = Vocabulary::take(25)->get();
            foreach ($day1Words as $i => $v) {
                $status = $i < 12 ? 'mastered' : ($i < 20 ? 'learning' : 'unlearned');
                UserVocabularyProgress::create([
                    'user_id' => $cuong->id,
                    'vocabulary_id' => $v->id,
                    'status' => $status,
                    'correct_count' => $status === 'mastered' ? 3 : 1,
                    'wrong_count' => $status === 'learning' ? 1 : 0,
                    'last_reviewed_at' => Carbon::now()->subHours(rand(1, 24)),
                ]);
            }
        }
    }
}
