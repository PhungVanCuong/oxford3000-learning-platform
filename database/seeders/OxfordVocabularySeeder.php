<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Roadmap;
use App\Models\RoadmapNode;
use App\Models\Vocabulary;
use App\Models\User;
use App\Models\UserVocabularyProgress;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class OxfordVocabularySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Roadmap
        $roadmap = Roadmap::create([
            'title' => 'Chinh phục 3000 Từ vựng Oxford & Ngữ pháp Thực chiến',
            'description' => 'Lộ trình bài bản từ cơ bản đến nâng cao: Xây dựng nền tảng từ vựng + Ngữ pháp chuẩn xác, kết hợp kiểm tra đánh giá toàn diện.',
            'level' => 'A1 - B2',
            'order_index' => 1,
        ]);

        // 2. Create Alternating Nodes
        $node1 = RoadmapNode::create([
            'roadmap_id' => $roadmap->id,
            'title' => 'Chặng 1: 20 từ vựng cốt lõi vần A',
            'description' => 'Bộ 20 từ vựng đầu tiên trong từ điển Oxford 3000 (abandon, ability, able, about, above,...)',
            'node_type' => 'vocabulary',
            'order_index' => 1,
            'is_locked' => false, // First node unlocked
            'passing_score' => 80,
        ]);

        $node2 = RoadmapNode::create([
            'roadmap_id' => $roadmap->id,
            'title' => 'Chặng 2: Ngữ pháp Thì Hiện Tại Đơn',
            'description' => 'Quy tắc công thức, cách dùng chân lý/thói quen và bài tập trắc nghiệm + điền khuyết.',
            'node_type' => 'grammar',
            'order_index' => 2,
            'is_locked' => true,
            'passing_score' => 80,
        ]);

        $node3 = RoadmapNode::create([
            'roadmap_id' => $roadmap->id,
            'title' => 'Chặng 3: 20 từ vựng mở rộng vần A',
            'description' => 'Bộ 20 từ vựng tiếp theo: access, accident, achieve, acknowledge, adapt, v.v.',
            'node_type' => 'vocabulary',
            'order_index' => 3,
            'is_locked' => true,
            'passing_score' => 80,
        ]);

        $node4 = RoadmapNode::create([
            'roadmap_id' => $roadmap->id,
            'title' => 'Chặng 4: Ngữ pháp Thì Hiện Tại Tiếp Diễn',
            'description' => 'Diễn tả hành động đang xảy ra tại thời điểm nói hoặc kế hoạch tương lai gần.',
            'node_type' => 'grammar',
            'order_index' => 4,
            'is_locked' => true,
            'passing_score' => 80,
        ]);

        $node5 = RoadmapNode::create([
            'roadmap_id' => $roadmap->id,
            'title' => 'Chặng 5: 20 từ vựng nâng cao vần A - B',
            'description' => 'Bộ từ vựng nâng cao: adequate, admit, adventure, affect, agree, v.v.',
            'node_type' => 'vocabulary',
            'order_index' => 5,
            'is_locked' => true,
            'passing_score' => 80,
        ]);

        $node6 = RoadmapNode::create([
            'roadmap_id' => $roadmap->id,
            'title' => 'Chặng 6: Ngữ pháp Thì Quá Khứ Đơn',
            'description' => 'Cách chia động từ quá khứ có quy tắc (-ed) và bảng động từ bất quy tắc thông dụng.',
            'node_type' => 'grammar',
            'order_index' => 6,
            'is_locked' => true,
            'passing_score' => 80,
        ]);

        $node7 = RoadmapNode::create([
            'roadmap_id' => $roadmap->id,
            'title' => 'Chặng 7: 20 từ vựng giao tiếp thực chiến',
            'description' => 'Bộ từ vựng giao tiếp: alcohol, alive, allow, already, alternative, amazing,...',
            'node_type' => 'vocabulary',
            'order_index' => 7,
            'is_locked' => true,
            'passing_score' => 80,
        ]);

        // 3. Load oxford_3000.json
        $jsonPath = database_path('data/oxford_3000.json');
        if (!File::exists($jsonPath)) {
            $this->command->error("File $jsonPath not found!");
            return;
        }

        $words = json_decode(File::get($jsonPath), true);
        $this->command->info("Seeding " . count($words) . " vocabulary entries...");

        $batch = [];
        $now = now();

        foreach ($words as $index => $item) {
            $nodeId = null;
            if ($index < 20) {
                $nodeId = $node1->id;
            } elseif ($index < 40) {
                $nodeId = $node3->id;
            } elseif ($index < 60) {
                $nodeId = $node5->id;
            } elseif ($index < 80) {
                $nodeId = $node7->id;
            }

            $batch[] = [
                'roadmap_node_id' => $nodeId,
                'word' => $item['word'],
                'phonetic_us' => $item['phonetic_us'] ?? '',
                'part_of_speech' => $item['part_of_speech'] ?? '',
                'meaning_vi' => $item['meaning_vi'] ?? '',
                'audio_url' => $item['audio_url'] ?? null,
                'example_en' => $item['example_en'] ?? '',
                'example_vi' => $item['example_vi'] ?? '',
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

        $this->command->info("Successfully seeded vocabularies!");

        // 4. Default user progress initialization
        $demoUser = User::first();
        if ($demoUser) {
            $sampleVocabs = Vocabulary::take(25)->get();
            foreach ($sampleVocabs as $i => $v) {
                $status = $i < 10 ? 'mastered' : ($i < 20 ? 'learning' : 'new');
                UserVocabularyProgress::create([
                    'user_id' => $demoUser->id,
                    'vocabulary_id' => $v->id,
                    'status' => $status,
                    'correct_streak' => $status === 'mastered' ? 3 : 1,
                    'last_reviewed_at' => Carbon::now()->subDays(rand(0, 3)),
                    'next_review_at' => $status === 'mastered' ? Carbon::now()->addDays(5) : Carbon::now()->addDay(),
                ]);
            }
        }
    }
}
