<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizRoom;
use App\Models\QuizRoomParticipant;
use App\Models\Vocabulary;
use App\Models\CurriculumTerm;
use App\Models\GrammarQuestion;
use App\Models\User;
use App\Models\UserStudyLog;
use Illuminate\Support\Str;

class ArenaController extends Controller
{
    private function resolveCurrentUser(Request $request): ?User
    {
        $user = auth('sanctum')->user() ?? $request->user();
        if (!$user) {
            $token = $request->bearerToken();
            if ($token) {
                $accessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
                if ($accessToken) {
                    $user = $accessToken->tokenable;
                }
            }
        }
        return $user;
    }

    /**
     * Bảng xếp hạng vinh quang Đấu Trường & Toàn diện
     */
    public function leaderboard(Request $request)
    {
        $type = $request->query('type', 'all_xp'); // all_xp | streak | arena | words
        $currentUser = $this->resolveCurrentUser($request);

        $query = User::select([
            'id', 'name', 'avatar_url', 'role', 'xp_points', 'streak_days', 'last_study_date', 'created_at'
        ]);

        if ($type === 'streak') {
            $query->orderByDesc('streak_days')->orderByDesc('xp_points');
        } elseif ($type === 'arena') {
            $query->withSum('quizRoomParticipants as arena_score', 'score')
                  ->withCount('quizRoomParticipants as arena_matches')
                  ->orderByDesc('arena_score')
                  ->orderByDesc('xp_points');
        } elseif ($type === 'words') {
            $query->withCount(['vocabularyProgress as mastered_words' => function ($q) {
                $q->where('status', 'mastered');
            }])->orderByDesc('mastered_words')->orderByDesc('xp_points');
        } else {
            // default all_xp
            $query->orderByDesc('xp_points')->orderByDesc('streak_days');
        }

        $users = $query->take(50)->get();

        $getTier = function ($xp) {
            if ($xp >= 5000) return ['name' => 'Đại Cao Thủ', 'badge' => '💎', 'class' => 'tier-grandmaster'];
            if ($xp >= 3000) return ['name' => 'Kim Cương', 'badge' => '💠', 'class' => 'tier-diamond'];
            if ($xp >= 1500) return ['name' => 'Bạch Kim', 'badge' => '🏅', 'class' => 'tier-platinum'];
            if ($xp >= 800)  return ['name' => 'Vàng', 'badge' => '🌟', 'class' => 'tier-gold'];
            if ($xp >= 300)  return ['name' => 'Bạc', 'badge' => '🥈', 'class' => 'tier-silver'];
            return ['name' => 'Đồng', 'badge' => '🥉', 'class' => 'tier-bronze'];
        };

        $leaderboard = $users->map(function ($u, $idx) use ($currentUser, $getTier) {
            $tier = $getTier($u->xp_points ?? 0);
            return [
                'rank' => $idx + 1,
                'user_id' => $u->id,
                'name' => $u->name,
                'avatar_url' => $u->avatar_url ?: ('https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($u->name)),
                'role' => $u->role,
                'xp_points' => $u->xp_points ?? 0,
                'streak_days' => $u->streak_days ?? 0,
                'arena_score' => $u->arena_score ?? 0,
                'arena_matches' => $u->arena_matches ?? 0,
                'mastered_words' => $u->mastered_words ?? 0,
                'tier_name' => $tier['name'],
                'tier_badge' => $tier['badge'],
                'tier_class' => $tier['class'],
                'is_current_user' => $currentUser && ($currentUser->id === $u->id),
            ];
        });

        // Current user personal ranking summary
        $myRank = null;
        if ($currentUser) {
            $inTop = $leaderboard->firstWhere('is_current_user', true);
            if ($inTop) {
                $myRank = $inTop;
            } else {
                $higherCount = 0;
                if ($type === 'streak') {
                    $higherCount = User::where('streak_days', '>', $currentUser->streak_days ?? 0)->count();
                } else {
                    $higherCount = User::where('xp_points', '>', $currentUser->xp_points ?? 0)->count();
                }

                $tier = $getTier($currentUser->xp_points ?? 0);
                $myRank = [
                    'rank' => $higherCount + 1,
                    'user_id' => $currentUser->id,
                    'name' => $currentUser->name,
                    'avatar_url' => $currentUser->avatar_url ?: ('https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($currentUser->name)),
                    'role' => $currentUser->role,
                    'xp_points' => $currentUser->xp_points ?? 0,
                    'streak_days' => $currentUser->streak_days ?? 0,
                    'arena_score' => QuizRoomParticipant::where('user_id', $currentUser->id)->sum('score') ?? 0,
                    'arena_matches' => QuizRoomParticipant::where('user_id', $currentUser->id)->count(),
                    'mastered_words' => $currentUser->vocabularyProgress()->where('status', 'mastered')->count(),
                    'tier_name' => $tier['name'],
                    'tier_badge' => $tier['badge'],
                    'tier_class' => $tier['class'],
                    'is_current_user' => true,
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'type' => $type,
                'leaderboard' => $leaderboard,
                'my_rank' => $myRank,
                'total_users' => User::count(),
            ],
        ]);
    }

    /**
     * List all active/available quiz arena rooms
     */
    public function index()
    {
        $rooms = QuizRoom::with(['host', 'participants.user'])
            ->withCount('participants')
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->orderByRaw("FIELD(status, 'waiting', 'playing', 'finished')")
            ->orderByDesc('id')
            ->take(40)
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'room_code' => $r->room_code,
                    'title' => $r->title,
                    'host_name' => $r->host ? $r->host->name : 'Giáo viên OxVocab',
                    'host_avatar' => $r->host ? $r->host->avatar_url : null,
                    'category' => $r->category,
                    'status' => $r->status,
                    'has_password' => !empty($r->password),
                    'max_players' => $r->max_players ?: 'Không giới hạn',
                    'participants_count' => $r->participants_count,
                    'total_questions' => is_array($r->questions_data) ? count($r->questions_data) : 10,
                    'time_per_question' => $r->time_per_question ?: 0,
                    'xp_per_question' => $r->xp_per_question ?: 15,
                    'expires_at' => $r->expires_at ? $r->expires_at->toIso8601String() : null,
                    'created_at' => $r->created_at ? $r->created_at->diffForHumans() : '',
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $rooms,
        ]);
    }

    /**
     * Create a new classroom quiz room
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'category' => 'required|string|in:vocab_3000,curriculum,grammar,daily,custom',
            'selected_day' => 'nullable|integer|min:1|max:300',
            'selected_topic_id' => 'nullable|integer',
            'questions_count' => 'nullable|integer|min:1|max:50',
            'password' => 'nullable|string|max:30',
            'time_per_question' => 'nullable|integer|min:0|max:120',
            'xp_per_question' => 'nullable|integer|min:5|max:100',
            'max_players' => 'nullable|integer|min:0|max:500',
            'lifetime' => 'nullable|string|in:30_mins,1_hour,24_hours,permanent',
            'custom_questions' => 'nullable|array',
        ]);

        $user = $this->resolveCurrentUser($request) ?? User::first();
        $roomCode = 'OX-' . strtoupper(Str::random(5));
        $qCount = $validated['questions_count'] ?? 10;
        if ($qCount <= 0) $qCount = 10;

        // Determine expiration timestamp
        $expiresAt = null;
        if (!empty($validated['lifetime'])) {
            if ($validated['lifetime'] === '30_mins') {
                $expiresAt = now()->addMinutes(30);
            } elseif ($validated['lifetime'] === '1_hour') {
                $expiresAt = now()->addHour();
            } elseif ($validated['lifetime'] === '24_hours') {
                $expiresAt = now()->addHours(24);
            }
        }

        // Generate questions based on category
        $questions = [];

        if ($validated['category'] === 'daily') {
            $day = $validated['selected_day'] ?? 1;
            $wordsPerDay = 15;
            $startId = ($day - 1) * $wordsPerDay + 1;
            $endId = $day * $wordsPerDay;
            $words = Vocabulary::whereBetween('id', [$startId, $endId])->inRandomOrder()->take($qCount)->get();
            if ($words->isEmpty()) {
                $words = Vocabulary::inRandomOrder()->take($qCount)->get();
            }

            $allDistractors = Vocabulary::inRandomOrder()->take(50)->pluck('meaning_vi')->toArray();
            foreach ($words as $idx => $w) {
                $options = [$w->meaning_vi];
                $randDistractors = array_filter($allDistractors, fn($d) => $d !== $w->meaning_vi);
                shuffle($randDistractors);
                $options = array_merge($options, array_slice($randDistractors, 0, 3));
                shuffle($options);

                $questions[] = [
                    'id' => $idx + 1,
                    'prompt' => "Từ \"{$w->word}\" ({$w->phonetic_us}) (Ngày {$day}) có nghĩa là gì?",
                    'options' => $options,
                    'correct_answer' => $w->meaning_vi,
                    'explanation' => "Ví dụ: {$w->example_en} - {$w->example_vi}",
                ];
            }
        } elseif ($validated['category'] === 'vocab_3000') {
            $words = Vocabulary::inRandomOrder()->take($qCount)->get();
            $allDistractors = Vocabulary::inRandomOrder()->take(50)->pluck('meaning_vi')->toArray();

            foreach ($words as $idx => $w) {
                $options = [$w->meaning_vi];
                $randDistractors = array_filter($allDistractors, fn($d) => $d !== $w->meaning_vi);
                shuffle($randDistractors);
                $options = array_merge($options, array_slice($randDistractors, 0, 3));
                shuffle($options);

                $questions[] = [
                    'id' => $idx + 1,
                    'prompt' => "Từ \"{$w->word}\" ({$w->phonetic_us}) có nghĩa tiếng Việt là gì?",
                    'options' => $options,
                    'correct_answer' => $w->meaning_vi,
                    'explanation' => "Ví dụ: {$w->example_en} - {$w->example_vi}",
                ];
            }
        } elseif ($validated['category'] === 'curriculum') {
            $query = CurriculumTerm::query();
            if (!empty($validated['selected_topic_id'])) {
                $query->where('topic_id', $validated['selected_topic_id']);
            }
            $terms = $query->inRandomOrder()->take($qCount)->get();
            if ($terms->isEmpty()) {
                $terms = CurriculumTerm::inRandomOrder()->take($qCount)->get();
            }
            $distractors = CurriculumTerm::inRandomOrder()->take(50)->pluck('meaning_vi')->toArray();

            foreach ($terms as $idx => $t) {
                $options = [$t->meaning_vi];
                $randDistractors = array_filter($distractors, fn($d) => $d !== $t->meaning_vi);
                shuffle($randDistractors);
                $options = array_merge($options, array_slice($randDistractors, 0, 3));
                shuffle($options);

                $questions[] = [
                    'id' => $idx + 1,
                    'prompt' => "Trong lộ trình thực chiến, từ \"{$t->term}\" mang ý nghĩa là gì?",
                    'options' => $options,
                    'correct_answer' => $t->meaning_vi,
                    'explanation' => "Ví dụ: {$t->example_en}",
                ];
            }
        } elseif ($validated['category'] === 'grammar') {
            $gQuestions = GrammarQuestion::where('type', 'multiple_choice')->inRandomOrder()->take($qCount)->get();
            if ($gQuestions->isEmpty()) {
                $gQuestions = GrammarQuestion::inRandomOrder()->take($qCount)->get();
            }

            foreach ($gQuestions as $idx => $q) {
                $questions[] = [
                    'id' => $idx + 1,
                    'prompt' => $q->question,
                    'options' => $q->options_json ?? ['A', 'B', 'C', 'D'],
                    'correct_answer' => $q->correct_answer,
                    'explanation' => $q->explanation ?? 'Cấu trúc thì tiếng Anh chuẩn.',
                ];
            }
        } elseif ($validated['category'] === 'custom' && !empty($validated['custom_questions'])) {
            $questions = $validated['custom_questions'];
        }

        // Fallback default questions if empty
        if (empty($questions)) {
            $words = Vocabulary::take($qCount)->get();
            foreach ($words as $idx => $w) {
                $questions[] = [
                    'id' => $idx + 1,
                    'prompt' => "Nghĩa của từ \"{$w->word}\":",
                    'options' => [$w->meaning_vi, 'Tùy chọn B', 'Tùy chọn C', 'Tùy chọn D'],
                    'correct_answer' => $w->meaning_vi,
                    'explanation' => $w->example_vi,
                ];
            }
        }

        $room = QuizRoom::create([
            'room_code' => $roomCode,
            'title' => $validated['title'],
            'host_user_id' => $user ? $user->id : null,
            'status' => 'waiting',
            'category' => $validated['category'],
            'password' => $validated['password'] ?? null,
            'max_players' => !empty($validated['max_players']) ? $validated['max_players'] : 0,
            'time_per_question' => isset($validated['time_per_question']) ? $validated['time_per_question'] : 15,
            'xp_per_question' => !empty($validated['xp_per_question']) ? $validated['xp_per_question'] : 15,
            'questions_data' => $questions,
            'expires_at' => $expiresAt,
            'current_question_index' => 0,
        ]);

        // Auto join host
        if ($user) {
            QuizRoomParticipant::firstOrCreate([
                'room_id' => $room->id,
                'user_id' => $user->id,
            ], [
                'score' => 0,
                'correct_count' => 0,
                'total_time_ms' => 0,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã tạo phòng thi đấu thành công!',
            'data' => [
                'id' => $room->id,
                'room_code' => $room->room_code,
                'title' => $room->title,
                'category' => $room->category,
                'time_per_question' => $room->time_per_question,
                'xp_per_question' => $room->xp_per_question,
            ],
        ]);
    }
    /**
     * Join room by code & optional password
     */
    public function join(Request $request)
    {
        $validated = $request->validate([
            'room_code' => 'required|string',
            'password' => 'nullable|string',
        ]);

        $room = QuizRoom::where('room_code', strtoupper(trim($validated['room_code'])))->firstOrFail();

        // 1. Check password if room has password
        if (!empty($room->password)) {
            $providedPwd = isset($validated['password']) ? trim((string)$validated['password']) : '';
            if ($providedPwd !== trim((string)$room->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Mật khẩu phòng thi đấu không chính xác! Vui lòng thử lại.',
                ], 403);
            }
        }

        // 2. Check login user
        $user = $this->resolveCurrentUser($request);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập tài khoản để tham gia phòng thi đấu.',
            ], 401);
        }

        // 3. Check room status
        if ($room->status === 'finished') {
            return response()->json([
                'status' => 'error',
                'message' => 'Phòng thi đấu này đã kết thúc.',
            ], 400);
        }

        // 4. Check max players limit
        if ($room->max_players > 0) {
            $alreadyIn = $room->participants()->where('user_id', $user->id)->exists();
            if (!$alreadyIn && $room->participants()->count() >= $room->max_players) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Phòng đã đủ số lượng tối đa ({$room->max_players} người).",
                ], 400);
            }
        }

        QuizRoomParticipant::firstOrCreate([
            'room_id' => $room->id,
            'user_id' => $user->id,
        ], [
            'score' => 0,
            'correct_count' => 0,
            'total_time_ms' => 0,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã tham gia phòng thi đấu thành công!',
            'data' => [
                'id' => $room->id,
                'room_code' => $room->room_code,
                'user_id' => $user->id,
                'user_name' => $user->name,
            ],
        ]);
    }

    /**
     * Get room details & live leaderboard
     */
    public function show(Request $request, $id)
    {
        $room = is_numeric($id) 
            ? QuizRoom::with(['host', 'participants.user'])->findOrFail($id)
            : QuizRoom::with(['host', 'participants.user'])->where('room_code', strtoupper($id))->firstOrFail();

        $user = $this->resolveCurrentUser($request);
        $isHost = $user && ($user->id === $room->host_user_id || $user->isAdmin());
        $isParticipant = $user && $room->participants->contains('user_id', $user->id);

        $hasPassword = !empty($room->password);

        // Check password protection:
        // If room has password and user is NOT host and NOT already joined as participant:
        if ($hasPassword && !$isHost && !$isParticipant) {
            $providedPwd = trim((string)($request->query('pwd') ?? $request->header('X-Room-Password') ?? ''));
            if ($providedPwd !== trim((string)$room->password)) {
                return response()->json([
                    'status' => 'locked',
                    'message' => 'Phòng này yêu cầu mật khẩu.',
                    'data' => [
                        'id' => $room->id,
                        'room_code' => $room->room_code,
                        'title' => $room->title,
                        'category' => $room->category,
                        'has_password' => true,
                        'requires_password' => true,
                        'is_host' => false,
                        'is_participant' => false,
                        'host_name' => $room->host ? $room->host->name : 'Giáo viên',
                        'host_avatar' => $room->host ? $room->host->avatar_url : null,
                        'participants_count' => $room->participants->count(),
                        'participants' => [],
                        'questions' => [],
                    ]
                ], 200);
            }
        }

        $leaderboard = $room->participants->map(function ($p, $idx) use ($user) {
            return [
                'rank' => $idx + 1,
                'user_id' => $p->user_id,
                'user_name' => $p->user ? $p->user->name : 'Học viên',
                'user_avatar' => $p->user ? ($p->user->avatar_url ?: ('https://api.dicebear.com/7.x/bottts/svg?seed=' . urlencode($p->user->name))) : "https://api.dicebear.com/7.x/bottts/svg?seed={$p->user_id}",
                'score' => $p->score ?? 0,
                'correct_count' => $p->correct_count ?? 0,
                'total_time_ms' => $p->total_time_ms ?? 0,
                'is_current_user' => $user && ($user->id === $p->user_id),
            ];
        });

        // Hide correct answer during 'playing' for standard students
        $questions = $room->questions_data ?? [];
        if ($room->status === 'playing' && !$isHost) {
            $questions = array_map(function ($q) {
                $copy = $q;
                unset($copy['correct_answer']);
                unset($copy['explanation']);
                return $copy;
            }, $questions);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $room->id,
                'room_code' => $room->room_code,
                'title' => $room->title,
                'category' => $room->category,
                'status' => $room->status,
                'has_password' => $hasPassword,
                'requires_password' => false,
                'is_participant' => $isParticipant,
                'time_per_question' => $room->time_per_question,
                'xp_per_question' => $room->xp_per_question ?: 15,
                'expires_at' => $room->expires_at ? $room->expires_at->toIso8601String() : null,
                'current_question_index' => $room->current_question_index,
                'is_host' => $isHost,
                'host_name' => $room->host ? $room->host->name : 'Quản trị viên',
                'questions' => $questions,
                'total_questions' => count($questions),
                'participants' => $leaderboard,
            ],
        ]);
    }

    /**
     * Start the quiz arena
     */
    public function start(Request $request, $id)
    {
        $room = is_numeric($id)
            ? QuizRoom::findOrFail($id)
            : QuizRoom::where('room_code', strtoupper($id))->firstOrFail();

        $room->status = 'playing';
        $room->current_question_index = 0;
        $room->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cuộc thi đấu đã chính thức bắt đầu!',
        ]);
    }

    /**
     * Submit an answer for a question with speed bonus
     */
    public function submitAnswer(Request $request, $id)
    {
        $validated = $request->validate([
            'question_index' => 'nullable|integer',
            'question_id' => 'nullable|integer',
            'selected_option' => 'nullable|string',
            'user_answer' => 'nullable|string',
            'time_taken_ms' => 'nullable|integer',
            'time_taken_seconds' => 'nullable|numeric',
        ]);

        $room = is_numeric($id)
            ? QuizRoom::findOrFail($id)
            : QuizRoom::where('room_code', strtoupper($id))->firstOrFail();

        $user = $this->resolveCurrentUser($request) ?? User::first();
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        $questions = $room->questions_data ?? [];
        $idx = $validated['question_index'] ?? ($validated['question_id'] ? $validated['question_id'] - 1 : 0);

        if (!isset($questions[$idx])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid question index'], 400);
        }

        $userAnswer = $validated['selected_option'] ?? $validated['user_answer'] ?? '';
        $timeTakenMs = $validated['time_taken_ms'] ?? round(($validated['time_taken_seconds'] ?? 5) * 1000);

        $q = $questions[$idx];
        $isCorrect = trim($q['correct_answer'] ?? '') === trim($userAnswer);

        $earnedPoints = 0;
        if ($isCorrect) {
            $basePoints = $room->xp_per_question ?: 15;
            $maxTimeMs = ($room->time_per_question ?: 15) * 1000;
            $timeRatio = $maxTimeMs > 0 ? max(0, min(1, 1 - ($timeTakenMs / $maxTimeMs))) : 0.5;
            $speedBonus = round($timeRatio * 15);
            $earnedPoints = $basePoints + $speedBonus;
        }

        $participant = QuizRoomParticipant::firstOrCreate([
            'room_id' => $room->id,
            'user_id' => $user->id,
        ]);

        if ($isCorrect) {
            $participant->increment('score', $earnedPoints);
            $participant->increment('correct_count', 1);
        }
        $participant->increment('total_time_ms', $timeTakenMs);

        // Check if finished
        if ($idx + 1 >= count($questions)) {
            $awardedXp = round($participant->score);
            $user->increment('xp_points', $awardedXp);
            if ($user->streak_days == 0) {
                $user->streak_days = 1;
            }
            $user->last_study_date = now()->toDateString();
            $user->save();

            $log = UserStudyLog::firstOrCreate(
                ['user_id' => $user->id, 'study_date' => now()->toDateString()],
                ['minutes_spent' => 0, 'words_learned' => 0, 'xp_earned' => 0, 'exercises_done' => 0]
            );
            $log->increment('xp_earned', $awardedXp);
            $log->increment('exercises_done', count($questions));
            $log->increment('minutes_spent', 3);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'is_correct' => $isCorrect,
                'correct_answer' => $q['correct_answer'] ?? '',
                'explanation' => $q['explanation'] ?? '',
                'earned_points' => $earnedPoints,
                'total_score' => $participant->score,
            ],
        ]);
    }

    /**
     * Admin: List all rooms for management
     */
    public function adminIndex(Request $request)
    {
        $user = $this->resolveCurrentUser($request);
        if (!$user || (!$user->isAdmin() && !$user->hasPermission('manage_arena'))) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền quản lý đấu trường.'], 403);
        }

        $rooms = QuizRoom::with(['host', 'participants.user'])
            ->withCount('participants')
            ->orderByDesc('id')
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'room_code' => $r->room_code,
                    'title' => $r->title,
                    'category' => $r->category,
                    'status' => $r->status,
                    'host_name' => $r->host ? $r->host->name : 'Hệ thống',
                    'host_email' => $r->host ? $r->host->email : '',
                    'participants_count' => $r->participants_count,
                    'max_players' => $r->max_players ?: 'Không giới hạn',
                    'time_per_question' => $r->time_per_question ?: 0,
                    'xp_per_question' => $r->xp_per_question ?: 15,
                    'total_questions' => is_array($r->questions_data) ? count($r->questions_data) : 0,
                    'has_password' => !empty($r->password),
                    'password' => $r->password,
                    'expires_at' => $r->expires_at ? $r->expires_at->toDateTimeString() : 'Vĩnh viễn',
                    'created_at' => $r->created_at ? $r->created_at->toDateTimeString() : '',
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $rooms,
        ]);
    }

    /**
     * Admin: Close / finish an active room
     */
    public function closeRoom(Request $request, $id)
    {
        $user = $this->resolveCurrentUser($request);
        if (!$user || (!$user->isAdmin() && !$user->hasPermission('manage_arena'))) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền đóng phòng này.'], 403);
        }

        $room = is_numeric($id)
            ? QuizRoom::findOrFail($id)
            : QuizRoom::where('room_code', strtoupper($id))->firstOrFail();

        $room->status = 'finished';
        $room->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã đóng phòng thi đấu thành công.',
        ]);
    }

    /**
     * Admin: Delete a room completely
     */
    public function destroy(Request $request, $id)
    {
        $user = $this->resolveCurrentUser($request);
        if (!$user || (!$user->isAdmin() && !$user->hasPermission('manage_arena'))) {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền xóa phòng này.'], 403);
        }

        $room = is_numeric($id)
            ? QuizRoom::findOrFail($id)
            : QuizRoom::where('room_code', strtoupper($id))->firstOrFail();

        $room->participants()->delete();
        $room->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa phòng thi đấu thành công.',
        ]);
    }
}
