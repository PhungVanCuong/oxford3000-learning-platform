<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizRoom;
use App\Models\QuizRoomParticipant;
use App\Models\Vocabulary;
use App\Models\GrammarQuestion;
use App\Models\CurriculumTerm;
use App\Models\User;

class ArenaSampleSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        QuizRoomParticipant::truncate();
        QuizRoom::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $tutor = User::where('role', 'tutor')->first() ?? User::where('role', 'admin')->first();
        $admin = User::where('role', 'admin')->first();
        $allUsers = User::all();
        $students = User::where('role', 'user')->get();

        // 1. Prepare Question Sets
        $words = Vocabulary::inRandomOrder()->take(10)->get();
        if ($words->isEmpty()) {
            $words = collect([
                (object)['word' => 'abandon', 'phonetic_us' => '/əˈbændən/', 'meaning_vi' => 'từ bỏ, bỏ rơi', 'example_en' => 'He abandoned his car.', 'example_vi' => 'Anh ấy đã bỏ lại xe của mình.'],
                (object)['word' => 'ability', 'phonetic_us' => '/əˈbɪləti/', 'meaning_vi' => 'khả năng, năng lực', 'example_en' => 'She has the ability to speak English.', 'example_vi' => 'Cô ấy có khả năng nói tiếng Anh.'],
            ]);
        }
        $distractors = Vocabulary::inRandomOrder()->take(50)->pluck('meaning_vi')->toArray();
        if (empty($distractors)) {
            $distractors = ['thành công', 'phát triển', 'kiên nhẫn', 'sáng tạo', 'bền bỉ', 'học hỏi'];
        }

        $q1 = [];
        foreach ($words as $idx => $w) {
            $options = [$w->meaning_vi];
            $randD = array_filter($distractors, fn($d) => $d !== $w->meaning_vi);
            shuffle($randD);
            $options = array_merge($options, array_slice($randD, 0, 3));
            shuffle($options);

            $q1[] = [
                'id' => $idx + 1,
                'prompt' => "Từ \"{$w->word}\" ({$w->phonetic_us}) có nghĩa tiếng Việt là gì?",
                'options' => $options,
                'correct_answer' => $w->meaning_vi,
                'explanation' => "Ví dụ: {$w->example_en} ({$w->example_vi})",
            ];
        }

        // 2. Grammar questions
        $gQuestions = GrammarQuestion::take(10)->get();
        $q2 = [];
        foreach ($gQuestions as $idx => $gq) {
            $q2[] = [
                'id' => $idx + 1,
                'prompt' => $gq->question,
                'options' => $gq->options_json ?? ['A', 'B', 'C', 'D'],
                'correct_answer' => $gq->correct_answer,
                'explanation' => $gq->explanation,
            ];
        }

        // ROOM 1: Waiting Lobby - Oxford 3000
        $room1 = QuizRoom::create([
            'room_code' => 'OX-ARENA1',
            'title' => '🏆 Đấu Trường 3.000 Từ Vựng Hàng Ngày (Mở tự do)',
            'host_user_id' => $tutor ? $tutor->id : 1,
            'status' => 'waiting',
            'category' => 'vocab_3000',
            'password' => null,
            'max_players' => 50,
            'time_per_question' => 15,
            'xp_per_question' => 15,
            'questions_data' => $q1,
            'current_question_index' => 0,
        ]);

        foreach ($students->slice(0, 8) as $stu) {
            QuizRoomParticipant::create([
                'room_id' => $room1->id,
                'user_id' => $stu->id,
                'score' => rand(150, 450),
                'correct_count' => rand(4, 9),
                'total_time_ms' => rand(25000, 60000),
            ]);
        }

        // ROOM 2: Playing Live - Grammar 12 Tenses
        $room2 = QuizRoom::create([
            'room_code' => 'OX-GRAMMAR',
            'title' => '⚡ Thách Thức 12 Thì Ngữ Pháp - Tốc Độ & Phản Xạ',
            'host_user_id' => $admin ? $admin->id : 1,
            'status' => 'playing',
            'category' => 'grammar',
            'password' => null,
            'max_players' => 30,
            'time_per_question' => 15,
            'xp_per_question' => 20,
            'questions_data' => $q2 ?: $q1,
            'current_question_index' => 2,
        ]);

        foreach ($students->slice(8, 6) as $stu) {
            QuizRoomParticipant::create([
                'room_id' => $room2->id,
                'user_id' => $stu->id,
                'score' => rand(200, 650),
                'correct_count' => rand(3, 7),
                'total_time_ms' => rand(20000, 45000),
            ]);
        }

        // ROOM 3: Private Class Room with Password
        $room3 = QuizRoom::create([
            'room_code' => 'OX-VIP99',
            'title' => '🔒 Lớp Học Trực Tuyến: Luyện Đề & Thi Đua XP (Cô Thu Trang)',
            'host_user_id' => $tutor ? $tutor->id : 1,
            'status' => 'waiting',
            'category' => 'curriculum',
            'password' => '123456',
            'max_players' => 20,
            'time_per_question' => 20,
            'xp_per_question' => 20,
            'questions_data' => $q1,
            'current_question_index' => 0,
        ]);

        foreach ($students->slice(14, 5) as $stu) {
            QuizRoomParticipant::create([
                'room_id' => $room3->id,
                'user_id' => $stu->id,
                'score' => rand(300, 750),
                'correct_count' => rand(5, 9),
                'total_time_ms' => rand(22000, 52000),
            ]);
        }

        // ROOM 4: Finished Tournament - Cross-Server Grand League
        $room4 = QuizRoom::create([
            'room_code' => 'OX-GLOBAL',
            'title' => '🌐 Chung Kết Giải Đấu Liên Server Mùa 1 - 2026',
            'host_user_id' => $admin ? $admin->id : 1,
            'status' => 'finished',
            'category' => 'vocab_3000',
            'password' => null,
            'max_players' => 100,
            'time_per_question' => 15,
            'xp_per_question' => 25,
            'questions_data' => $q1,
            'current_question_index' => count($q1),
        ]);

        $scorePool = [1450, 1380, 1250, 1120, 980, 890, 760, 650, 580, 490, 420, 350];
        foreach ($students->slice(0, count($scorePool)) as $idx => $stu) {
            QuizRoomParticipant::create([
                'room_id' => $room4->id,
                'user_id' => $stu->id,
                'score' => $scorePool[$idx] ?? rand(300, 600),
                'correct_count' => max(1, 10 - $idx),
                'total_time_ms' => (20 + $idx * 3) * 1000,
            ]);
        }

        // Give Admin account some strong arena participation
        if ($admin) {
            QuizRoomParticipant::firstOrCreate([
                'room_id' => $room4->id,
                'user_id' => $admin->id,
            ], [
                'score' => 1650,
                'correct_count' => 10,
                'total_time_ms' => 18500,
            ]);
        }
    }
}
