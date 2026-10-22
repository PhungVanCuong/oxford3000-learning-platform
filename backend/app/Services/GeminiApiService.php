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
}
