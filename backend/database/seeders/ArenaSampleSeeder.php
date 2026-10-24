<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizRoom;
use App\Models\QuizRoomParticipant;
use App\Models\Vocabulary;
use App\Models\GrammarQuestion;
use App\Models\User;

class ArenaSampleSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        QuizRoomParticipant::truncate();
        QuizRoom::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $hostUser = User::where('role', 'tutor')->first() ?? User::where('role', 'admin')->first();
        $students = User::where('role', 'user')->take(10)->get();

        // 1. Room 1: Vocab 3000 Arena
        $words = Vocabulary::inRandomOrder()->take(10)->get();
        $distractors = Vocabulary::inRandomOrder()->take(40)->pluck('meaning_vi')->toArray();
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

        $room1 = QuizRoom::create([
            'room_code' => 'OX-ARENA1',
            'title' => '🏆 Đấu Trường 3.000 Từ Vựng Hàng Ngày (Mở tự do)',
            'host_user_id' => $hostUser ? $hostUser->id : 1,
            'status' => 'waiting',
            'category' => 'vocab_3000',
            'password' => null,
            'max_players' => 50,
            'time_per_question' => 15,
            'questions_data' => $q1,
            'current_question_index' => 0,
        ]);

        // Add 5 participants to Room 1
        foreach ($students->slice(0, 5) as $stu) {
            QuizRoomParticipant::create([
                'room_id' => $room1->id,
                'user_id' => $stu->id,
                'score' => rand(400, 950),
                'correct_count' => rand(4, 9),
                'total_time_ms' => rand(25000, 60000),
            ]);
        }

        // 2. Room 2: Grammar 12 Tenses Arena
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

        $room2 = QuizRoom::create([
            'room_code' => 'OX-GRAMMAR',
            'title' => '⚡ Thách Thức 12 Thì Ngữ Pháp - Tốc Độ & Phản Xạ',
            'host_user_id' => $hostUser ? $hostUser->id : 1,
            'status' => 'waiting',
            'category' => 'grammar',
            'password' => null,
            'max_players' => 30,
            'time_per_question' => 15,
            'questions_data' => $q2,
            'current_question_index' => 0,
        ]);

        foreach ($students->slice(5, 4) as $stu) {
            QuizRoomParticipant::create([
                'room_id' => $room2->id,
                'user_id' => $stu->id,
                'score' => rand(300, 850),
                'correct_count' => rand(3, 8),
                'total_time_ms' => rand(30000, 70000),
            ]);
        }

        // 3. Room 3: Private Class Room with Password
        $room3 = QuizRoom::create([
            'room_code' => 'OX-VIP99',
            'title' => '🔒 Lớp Học Trực Tuyến: Luyện Đề & Thi Đua XP (Cô Thu Trang)',
            'host_user_id' => $hostUser ? $hostUser->id : 1,
            'status' => 'waiting',
            'category' => 'curriculum',
            'password' => '123456',
            'max_players' => 20,
            'time_per_question' => 20,
            'questions_data' => $q1,
            'current_question_index' => 0,
        ]);

        foreach ($students->slice(0, 3) as $stu) {
            QuizRoomParticipant::create([
                'room_id' => $room3->id,
                'user_id' => $stu->id,
                'score' => rand(500, 990),
                'correct_count' => rand(5, 10),
                'total_time_ms' => rand(20000, 50000),
            ]);
        }
    }
}
