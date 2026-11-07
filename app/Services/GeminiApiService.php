<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiApiService
{
    protected ?string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?? env('GEMINI_API_KEY');
    }

    /**
     * Tự động sinh câu ví dụ song ngữ Anh - Việt cho từ vựng
     */
    public function generateExamples(string $word, string $partOfSpeech, string $meaningVi): array
    {
        if (empty($this->apiKey)) {
            return [
                'example_en' => "This is a practical example sentence using the word {$word}.",
                'example_vi' => "Đây là câu ví dụ thực tế sử dụng từ {$word} ({$meaningVi}).",
                'source' => 'fallback',
            ];
        }

        $prompt = "Bạn là chuyên gia giảng dạy từ vựng tiếng Anh Oxford. Hãy tạo đúng 01 câu ví dụ tiếng Anh tự nhiên, thực tế chứa từ '{$word}' (loại từ: {$partOfSpeech}, nghĩa tiếng Việt: '{$meaningVi}') và câu dịch tiếng Việt chuẩn xác.\n"
            . "Chỉ trả về duy nhất định dạng JSON thuần túy (không markdown, không giải thích thêm):\n"
            . "{\"example_en\": \"...\", \"example_vi\": \"...\"}";

        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/gemini-2.5-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4,
                    'maxOutputTokens' => 200,
                ]
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text', '');
                // Clean markdown code blocks if any
                $cleaned = preg_replace('/```json\s*|\s*```/', '', trim($content));
                $json = json_decode($cleaned, true);

                if (is_array($json) && !empty($json['example_en']) && !empty($json['example_vi'])) {
                    return [
                        'example_en' => trim($json['example_en']),
                        'example_vi' => trim($json['example_vi']),
                        'source' => 'gemini_ai',
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Gemini API generateExamples failed: ' . $e->getMessage());
        }

        return [
            'example_en' => "Learning how to use '{$word}' properly will improve your English fluency.",
            'example_vi' => "Học cách sử dụng từ '{$word}' ({$meaningVi}) đúng chuẩn sẽ nâng cao độ lưu loát tiếng Anh của bạn.",
            'source' => 'fallback',
        ];
    }

    /**
     * Giải thích ngữ pháp / sửa lỗi chính tả / gợi ý câu
     */
    public function explainOrCorrect(string $text): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => true,
                'explanation' => "Giải thích cơ bản cho '{$text}': Đây là cấu trúc thông dụng trong tiếng Anh giao tiếp hàng ngày.",
                'corrected' => $text,
            ];
        }

        $prompt = "Bạn là trợ lý AI học tiếng Anh thông minh. Hãy phân tích đoạn sau của người học: '{$text}'.\n"
            . "Hãy kiểm tra lỗi chính tả/ngữ pháp (nếu có), cung cấp bản sửa chuẩn xác nhất và giải thích ngắn gọn, xúc tích, dễ hiểu bằng tiếng Việt trong khoảng 3-4 câu.\n"
            . "Trả về định dạng JSON thuần túy:\n"
            . "{\"corrected\": \"...\", \"explanation\": \"...\"}";

        try {
            $response = Http::timeout(12)->post("{$this->baseUrl}/gemini-2.5-flash:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 350,
                ]
            ]);

            if ($response->successful()) {
                $content = $response->json('candidates.0.content.parts.0.text', '');
                $cleaned = preg_replace('/```json\s*|\s*```/', '', trim($content));
                $json = json_decode($cleaned, true);

                if (is_array($json) && !empty($json['explanation'])) {
                    return [
                        'success' => true,
                        'corrected' => $json['corrected'] ?? $text,
                        'explanation' => $json['explanation'],
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Gemini API explainOrCorrect failed: ' . $e->getMessage());
        }

        return [
            'success' => true,
            'corrected' => $text,
            'explanation' => "Đoạn văn '{$text}' đã được ghi nhận. Để sử dụng tốt nhất, hãy luyện tập đặt câu thường xuyên trong các ngữ cảnh đời sống hàng ngày.",
        ];
    }

    /**
     * Chấm điểm và giải thích câu dịch bài tập ngữ pháp từ tiếng Việt sang tiếng Anh
     */
    public function checkTranslation(string $viPrompt, string $userAnswer, string $expectedAnswer, string $tense = ''): array
    {
        // 1. Chuẩn hóa chuỗi: bỏ toàn bộ dấu câu, đưa về chữ thường
        $normalizeText = function($str) {
            $str = mb_strtolower(trim($str), 'UTF-8');
            $str = preg_replace('/[.,?!:;"\'`\-_()]/u', ' ', $str);
            $str = preg_replace('/\s+/u', ' ', $str);
            $str = trim($str);

            $replacements = [
                "don t" => "do not", "doesn t" => "does not", "didn t" => "did not",
                "haven t" => "have not", "hasn t" => "has not", "hadn t" => "had not",
                "won t" => "will not", "isn t" => "is not", "aren t" => "are not",
                "wasn t" => "was not", "weren t" => "were not", "can t" => "cannot",
                "i m" => "i am", "you re" => "you are", "we re" => "we are", "they re" => "they are",
                "he s" => "he is", "she s" => "she is", "it s" => "it is",
                "there s" => "there is", "that s" => "that is",
                "everyday" => "every day", "lots of" => "a lot of"
            ];
            return str_replace(array_keys($replacements), array_values($replacements), $str);
        };

        $normUser = $normalizeText($userAnswer);
        $normExp = $normalizeText($expectedAnswer);

        // 2. Tách nếu đáp án có nhiều lựa chọn (ngăn cách bởi dấu / hoặc ngoặc đơn)
        $expectedVariants = [$normExp];
        if (str_contains($expectedAnswer, '/')) {
            $parts = explode('/', $expectedAnswer);
            foreach ($parts as $p) {
                $expectedVariants[] = $normalizeText($p);
            }
        }
        if (str_contains($expectedAnswer, '(')) {
            $cleanNoParen = preg_replace('/\([^\)]+\)/', '', $expectedAnswer);
            $expectedVariants[] = $normalizeText($cleanNoParen);
            $cleanOnlyParen = str_replace(['(', ')'], '', $expectedAnswer);
            $expectedVariants[] = $normalizeText($cleanOnlyParen);
        }

        // 3. So sánh trực tiếp với các biến thể đáp án
        foreach ($expectedVariants as $exp) {
            if ($normUser === $exp) {
                return [
                    'score' => 100,
                    'is_passed' => true,
                    'corrected' => $expectedAnswer,
                    'explanation' => "Xuất sắc! Bạn đã dịch hoàn toàn chính xác theo đúng ngữ pháp thì {$tense}.",
                    'tips' => "Rất chuẩn xác, hãy duy trì tốc độ và sự tự tin này nhé!",
                ];
            }

            // 3b. Kiểm tra trường hợp nhiều từ nối bằng "and", ",", "&" (VD: "reading and booking" vs "reading, booking")
            $stripConnectors = function($tokens) {
                $stop = ['and', '&', 'or', 'a', 'an', 'the', 'with', 'to'];
                return array_values(array_filter($tokens, fn($t) => !in_array($t, $stop)));
            };
            $uTokens = $stripConnectors(explode(' ', $normUser));
            $eTokens = $stripConnectors(explode(' ', $exp));
            if (!empty($uTokens) && !empty($eTokens)) {
                $uSorted = $uTokens; sort($uSorted);
                $eSorted = $eTokens; sort($eSorted);
                if ($uSorted === $eSorted) {
                    return [
                        'score' => 100,
                        'is_passed' => true,
                        'corrected' => $expectedAnswer,
                        'explanation' => "Chính xác tuyệt đối! Bạn đã dịch đúng tất cả các từ và động từ cần chia.",
                        'tips' => "Rất tốt! Hệ thống tự nhận diện các cách viết linh hoạt giữa dấu phẩy và từ 'and'.",
                    ];
                }
            }
        }

        // 4. Tính toán độ tương đồng Fuzzy (Dice, Levenshtein, Substring Overlap)
        $bestScore = 0;
        foreach ($expectedVariants as $exp) {
            $userWords = array_values(array_filter(explode(' ', $normUser)));
            $expWords = array_values(array_filter(explode(' ', $exp)));

            $matchCount = 0;
            $tempExp = $expWords;
            foreach ($userWords as $w) {
                $idx = array_search($w, $tempExp);
                if ($idx !== false) {
                    $matchCount++;
                    unset($tempExp[$idx]);
                }
            }

            $precision = count($userWords) > 0 ? ($matchCount / count($userWords)) : 0;
            $recall = count($expWords) > 0 ? ($matchCount / count($expWords)) : 0;
            $tokenF1 = ($precision + $recall > 0) ? (2 * ($precision * $recall) / ($precision + $recall)) * 100 : 0;

            similar_text($normUser, $exp, $simText);

            $maxLen = max(strlen($normUser), strlen($exp));
            $lev = ($maxLen > 0) ? (1 - (levenshtein(substr($normUser, 0, 255), substr($exp, 0, 255)) / $maxLen)) * 100 : 100;

            $candidateScore = round(max($tokenF1, $simText, $lev));
            if ($candidateScore > $bestScore) {
                $bestScore = $candidateScore;
            }
        }

        $bestScore = min(100, max(0, $bestScore));

        // 5. Nếu đạt từ 80% trở lên -> ĐẠT
        if ($bestScore >= 80) {
            return [
                'score' => $bestScore,
                'is_passed' => true,
                'corrected' => $expectedAnswer,
                'explanation' => "Đạt yêu cầu ({$bestScore}%)! Câu dịch của bạn đã thể hiện đúng nghĩa cốt lõi và dạng thì {$tense}.",
                'tips' => "Góp ý nhẹ: Đáp án chuẩn nhất là: \"{$expectedAnswer}\".",
            ];
        }

        // 6. Nếu điểm < 80% và có API key, thử gọi Gemini AI với timeout ngắn 3s
        if (!empty($this->apiKey)) {
            $prompt = "Bạn là giáo viên tiếng Anh nhiệt huyết, thân thiện của OxVocab. Nhiệm vụ của bạn là chấm điểm bài tập học viên dịch câu từ Tiếng Việt sang Tiếng Anh.\n"
                . "Ngữ pháp mục tiêu: {$tense}\n"
                . "Câu tiếng Việt gốc: \"{$viPrompt}\"\n"
                . "Đáp án tham khảo: \"{$expectedAnswer}\"\n"
                . "Câu học viên gõ: \"{$userAnswer}\"\n\n"
                . "Quy tắc chấm điểm:\n"
                . "- Chấm điểm theo thang 0 - 100 điểm. Nếu học viên dịch đúng nghĩa hoặc đúng từ 80% trở lên (chỉ sai lỗi nhỏ như mạo từ a/the, chính tả 1 chữ, hoặc dùng từ đồng nghĩa hợp lý) thì cho từ 80-100 điểm và is_passed = true. Nếu sai nhiều thì cho đúng số điểm thực tế (< 80) và is_passed = false.\n"
                . "- Dùng từ ngữ tiếng Việt cực kỳ cơ bản, bình dân, dễ hiểu (không dùng thuật ngữ ngữ pháp phức tạp) để giải thích cho học viên hiểu ngay.\n\n"
                . "Chỉ trả về JSON thuần túy (không markdown):\n"
                . "{\n"
                . "  \"score\": 75,\n"
                . "  \"is_passed\": false,\n"
                . "  \"corrected\": \"...\",\n"
                . "  \"explanation\": \"... (giải thích ngắn gọn 2-3 câu dễ hiểu)\",\n"
                . "  \"tips\": \"... (1 mẹo ngắn để nhớ)\"\n"
                . "}";

            try {
                $response = Http::timeout(3)->post("{$this->baseUrl}/gemini-1.5-flash:generateContent?key={$this->apiKey}", [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'maxOutputTokens' => 300,
                    ]
                ]);

                if ($response->successful()) {
                    $content = $response->json('candidates.0.content.parts.0.text', '');
                    $cleaned = preg_replace('/```json\s*|\s*```/', '', trim($content));
                    $json = json_decode($cleaned, true);

                    if (is_array($json) && isset($json['score'])) {
                        $aiScore = (int)$json['score'];
                        return [
                            'score' => $aiScore,
                            'is_passed' => $aiScore >= 80,
                            'corrected' => $json['corrected'] ?? $expectedAnswer,
                            'explanation' => $json['explanation'] ?? '',
                            'tips' => $json['tips'] ?? 'Hãy tiếp tục đối chiếu với câu mẫu nhé!',
                        ];
                    }
                }
            } catch (\Exception $e) {
                // Ignore API timeout/network failure and use smart algorithm score
            }
        }

        // 7. Fallback trả về điểm thực tế và lời giải thích thân thiện
        $isPassed = $bestScore >= 80;
        return [
            'score' => $bestScore,
            'is_passed' => $isPassed,
            'corrected' => $expectedAnswer,
            'explanation' => $bestScore >= 50
                ? "Chưa thật sự chính xác ({$bestScore}%). Bạn đã nắm được một số từ vựng nhưng cần xem lại cách chia thì {$tense}."
                : "Chưa đạt ({$bestScore}%). Hãy đối chiếu cấu trúc thì {$tense} với đáp án chuẩn bên dưới nhé!",
            'tips' => "Đáp án chuẩn: \"{$expectedAnswer}\". Chú ý cấu trúc thì {$tense}.",
        ];
    }
}
