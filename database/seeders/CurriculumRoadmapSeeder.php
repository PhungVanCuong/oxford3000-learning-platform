<?php

namespace Database\Seeders;

use App\Models\CurriculumTopic;
use App\Models\CurriculumTerm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CurriculumRoadmapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topicsMeta = [
            1 => [
                'title' => 'Education & Learning',
                'title_vi' => 'Giáo dục & Học tập',
                'file' => 'forumflash-education-learning.csv',
                'icon' => 'bi-mortarboard-fill',
                'color' => '#4f46e5',
                'description' => 'Hệ thống giáo dục, trường học, phương pháp giảng dạy, đại học và học tập suốt đời.'
            ],
            2 => [
                'title' => 'Environment & Climate',
                'title_vi' => 'Môi trường & Biến đổi Khí hậu',
                'file' => 'forumflash-environment-climate.csv',
                'icon' => 'bi-tree-fill',
                'color' => '#059669',
                'description' => 'Khí hậu, hệ sinh thái, bảo tồn thiên nhiên, đa dạng sinh học và phát triển bền vững.'
            ],
            3 => [
                'title' => 'Technology & Digital Life',
                'title_vi' => 'Công nghệ & Đời sống Số',
                'file' => 'forumflash-technology-digital-life.csv',
                'icon' => 'bi-cpu-fill',
                'color' => '#2563eb',
                'description' => 'Trí tuệ nhân tạo, thiết bị di động, tự động hóa, an ninh mạng và kỷ nguyên internet.'
            ],
            4 => [
                'title' => 'Health & Well-being',
                'title_vi' => 'Sức khỏe & Đời sống Tinh thần',
                'file' => 'forumflash-health-well-being.csv',
                'icon' => 'bi-heart-pulse-fill',
                'color' => '#e11d48',
                'description' => 'Y tế công cộng, dinh dưỡng lành mạnh, phòng ngừa bệnh tật và sức khỏe tâm lý.'
            ],
            5 => [
                'title' => 'Work & Employment',
                'title_vi' => 'Việc làm & Thị trường Lao động',
                'file' => 'forumflash-work-employment.csv',
                'icon' => 'bi-briefcase-fill',
                'color' => '#d97706',
                'description' => 'Tuyển dụng, môi trường công sở, chế độ đãi ngộ, thăng tiến và cân bằng công việc.'
            ],
            6 => [
                'title' => 'Economy & Consumerism',
                'title_vi' => 'Kinh tế & Xu hướng Tiêu dùng',
                'file' => 'forumflash-economy-consumerism.csv',
                'icon' => 'bi-graph-up-arrow',
                'color' => '#0891b2',
                'description' => 'Tăng trưởng kinh tế, thị trường tài chính, lạm phát và văn hóa chi tiêu tiêu dùng.'
            ],
            7 => [
                'title' => 'Cities & Urban Life',
                'title_vi' => 'Đô thị & Nhịp sống Hiện đại',
                'file' => 'forumflash-cities-urban-life.csv',
                'icon' => 'bi-buildings-fill',
                'color' => '#7c3aed',
                'description' => 'Quy hoạch đô thị, tắc nghẽn, tiện ích công cộng và cơ sở hạ tầng thành phố lớn.'
            ],
            8 => [
                'title' => 'Transport & Mobility',
                'title_vi' => 'Giao thông & Di chuyển Bền vững',
                'file' => 'forumflash-transport-mobility.csv',
                'icon' => 'bi-train-front-fill',
                'color' => '#ea580c',
                'description' => 'Phương tiện công cộng, đường sắt cao tốc, xe điện và giao thông tương lai.'
            ],
            9 => [
                'title' => 'Crime & Justice',
                'title_vi' => 'Tội phạm & Pháp luật Công lý',
                'file' => 'forumflash-crime-justice.csv',
                'icon' => 'bi-shield-shaded',
                'color' => '#dc2626',
                'description' => 'Hệ thống tư pháp, luật pháp, phiên tòa xét xử, cải tạo phạm nhân và an ninh trật tự.'
            ],
            10 => [
                'title' => 'Government & Policy',
                'title_vi' => 'Chính phủ & Chính sách Công',
                'file' => 'forumflash-government-policy.csv',
                'icon' => 'bi-bank2',
                'color' => '#4338ca',
                'description' => 'Quản trị quốc gia, ngân sách công, bầu cử, ban hành điều luật và phúc lợi xã hội.'
            ],
            11 => [
                'title' => 'Media & Advertising',
                'title_vi' => 'Truyền thông & Quảng cáo',
                'file' => 'forumflash-media-advertising.csv',
                'icon' => 'bi-broadcast-pin',
                'color' => '#db2777',
                'description' => 'Báo chí, mạng xã hội, chiến dịch quảng cáo, tự do ngôn luận và truyền thông đại chúng.'
            ],
            12 => [
                'title' => 'Culture & Heritage',
                'title_vi' => 'Văn hóa & Di sản Thế giới',
                'file' => 'forumflash-culture-heritage.csv',
                'icon' => 'bi-palette-fill',
                'color' => '#9333ea',
                'description' => 'Di sản kiến trúc, phong tục truyền thống, bảo tàng nghệ thuật và bản sắc dân tộc.'
            ],
            13 => [
                'title' => 'Globalisation & Trade',
                'title_vi' => 'Toàn cầu hóa & Thương mại',
                'file' => 'forumflash-globalisation-trade.csv',
                'icon' => 'bi-globe-americas',
                'color' => '#0284c7',
                'description' => 'Xuất nhập khẩu, chuỗi cung ứng toàn cầu, hội nhập quốc tế và hiệp định thương mại.'
            ],
            14 => [
                'title' => 'Science & Research',
                'title_vi' => 'Khoa học & Nghiên cứu Đột phá',
                'file' => 'forumflash-science-research.csv',
                'icon' => 'bi-flask-fill',
                'color' => '#0d9488',
                'description' => 'Phương pháp thực nghiệm, phát minh khoa học, công nghệ sinh học và vũ trụ.'
            ],
            15 => [
                'title' => 'Family & Children',
                'title_vi' => 'Gia đình & Nuôi dạy Con cái',
                'file' => 'forumflash-family-children.csv',
                'icon' => 'bi-people-fill',
                'color' => '#16a34a',
                'description' => 'Mối quan hệ gia đình, nuôi dưỡng trẻ nhỏ, giáo dục gia đình và người cao tuổi.'
            ],
            16 => [
                'title' => 'Food & Agriculture',
                'title_vi' => 'Nông nghiệp & An ninh Lương thực',
                'file' => 'forumflash-food-agriculture.csv',
                'icon' => 'bi-egg-fried',
                'color' => '#ca8a04',
                'description' => 'Trồng trọt hữu cơ, chế biến thực phẩm, chuỗi thức ăn và an ninh nông nghiệp.'
            ],
            17 => [
                'title' => 'Tourism & Travel',
                'title_vi' => 'Du lịch & Khám phá Trải nghiệm',
                'file' => 'forumflash-tourism-travel.csv',
                'icon' => 'bi-compass-fill',
                'color' => '#0ea5e9',
                'description' => 'Du lịch sinh thái, khách sạn nghỉ dưỡng, khám phá văn hóa và tác động môi trường.'
            ],
            18 => [
                'title' => 'Housing & Communities',
                'title_vi' => 'Nhà ở & Xây dựng Cộng đồng',
                'file' => 'forumflash-housing-communities.csv',
                'icon' => 'bi-house-heart-fill',
                'color' => '#65a30d',
                'description' => 'Giá nhà ở, bất động sản, gắn kết cộng đồng dân cư và không gian sinh hoạt chung.'
            ],
            19 => [
                'title' => 'Energy & Resources',
                'title_vi' => 'Năng lượng Tái tạo & Tài nguyên',
                'file' => 'forumflash-energy-resources.csv',
                'icon' => 'bi-lightning-charge-fill',
                'color' => '#f59e0b',
                'description' => 'Điện mặt trời, phong điện, nhiên liệu hóa thạch và chuyển đổi năng lượng xanh.'
            ],
            20 => [
                'title' => 'Equality & Social Change',
                'title_vi' => 'Bình đẳng & Thay đổi Xã hội',
                'file' => 'forumflash-equality-social-change.csv',
                'icon' => 'bi-gender-ambiguous',
                'color' => '#c026d3',
                'description' => 'Bình đẳng giới, quyền con người, hỗ trợ người yếu thế và biến chuyển xã hội.'
            ],
            21 => [
                'title' => 'Language & Communication',
                'title_vi' => 'Ngôn ngữ & Nghệ thuật Giao tiếp',
                'file' => 'forumflash-language-communication.csv',
                'icon' => 'bi-chat-quote-fill',
                'color' => '#3b82f6',
                'description' => 'Ngôn ngữ mẹ đẻ, song ngữ, giao tiếp liên văn hóa và tiếng Anh toàn cầu.'
            ],
            22 => [
                'title' => 'Population & Migration',
                'title_vi' => 'Dân số & Dịch chuyển Toàn cầu',
                'file' => 'forumflash-population-migration.csv',
                'icon' => 'bi-person-walking',
                'color' => '#475569',
                'description' => 'Già hóa dân số, làn sóng di cư, hội nhập người nhập cư và phân bố dân cư.'
            ],
            23 => [
                'title' => 'Sport & Active Lifestyles',
                'title_vi' => 'Thể thao & Lối sống Năng động',
                'file' => 'forumflash-sport-active-lifestyles.csv',
                'icon' => 'bi-bicycle',
                'color' => '#10b981',
                'description' => 'Thể thao chuyên nghiệp, rèn luyện thể chất hàng ngày, tinh thần thi đấu và sức bền.'
            ],
            24 => [
                'title' => 'Oceans & Water',
                'title_vi' => 'Đại dương & Nguồn nước Sạch',
                'file' => 'forumflash-oceans-water.csv',
                'icon' => 'bi-water',
                'color' => '#06b6d4',
                'description' => 'Bảo vệ biển cả, ô nhiễm rác thải nhựa, hệ sinh thái san hô và an ninh nước ngọt.'
            ],
            25 => [
                'title' => 'Personal Finance & Household Life',
                'title_vi' => 'Tài chính Cá nhân & Chi tiêu Gia đình',
                'file' => 'forumflash-personal-finance-household-life.csv',
                'icon' => 'bi-piggy-bank-fill',
                'color' => '#10b981',
                'description' => 'Tiết kiệm, đầu tư cá nhân, bảo hiểm, quản lý nợ và chi tiêu ngân sách gia đình.'
            ],
        ];

        // Root folder where CSVs are placed
        $workspaceRoot = dirname(base_path());

        $this->command->info("Starting Curriculum Roadmap Import from 25 CSV files...");

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CurriculumTerm::truncate();
        CurriculumTopic::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($topicsMeta as $order => $meta) {
            $candidatePaths = [
                base_path('Tài liệu tiếng Anh' . DIRECTORY_SEPARATOR . $meta['file']),
                base_path('Tài liệu tiếng Anh' . DIRECTORY_SEPARATOR . 'IELTS 25 topics' . DIRECTORY_SEPARATOR . $meta['file']),
                $workspaceRoot . DIRECTORY_SEPARATOR . 'Tài liệu tiếng Anh' . DIRECTORY_SEPARATOR . $meta['file'],
                $workspaceRoot . DIRECTORY_SEPARATOR . $meta['file'],
                base_path('..' . DIRECTORY_SEPARATOR . 'Tài liệu tiếng Anh' . DIRECTORY_SEPARATOR . $meta['file']),
                base_path($meta['file']),
            ];

            $csvPath = null;
            foreach ($candidatePaths as $candidate) {
                if (file_exists($candidate)) {
                    $csvPath = $candidate;
                    break;
                }
            }

            if (!$csvPath) {
                $this->command->warn("CSV file not found in 'Tài liệu tiếng Anh' or root: {$meta['file']}");
                continue;
            }

            $topic = CurriculumTopic::create([
                'order_num' => $order,
                'title' => $meta['title'],
                'title_vi' => $meta['title_vi'],
                'slug' => Str::slug($meta['title']),
                'icon' => $meta['icon'],
                'color' => $meta['color'],
                'description' => $meta['description'],
                'csv_filename' => $meta['file'],
                'total_terms' => 200,
                'total_stages' => 10,
            ]);

            $handle = fopen($csvPath, 'r');
            if ($handle === false) {
                continue;
            }

            // Read header
            $header = fgetcsv($handle);
            $termIndex = 0;

            $batchTerms = [];

            while (($row = fgetcsv($handle)) !== false) {
                if (empty($row[0])) {
                    continue;
                }
                $termIndex++;

                // Assign stage according to user specifications:
                // Stage 1: 10 items (1..10)
                // Stage 2: 12 items (11..22)
                // Stage 3: 14 items (23..36)
                // Stage 4: 16 items (37..52)
                // Stage 5: 20 items (53..72)
                // Stage 6: 24 items (73..96)
                // Stage 7: 24 items (97..120)
                // Stage 8: 26 items (121..146)
                // Stage 9: 26 items (147..172)
                // Stage 10: 28 items (173..200)
                $stageNum = 1;
                if ($termIndex <= 10) {
                    $stageNum = 1;
                } elseif ($termIndex <= 22) {
                    $stageNum = 2;
                } elseif ($termIndex <= 36) {
                    $stageNum = 3;
                } elseif ($termIndex <= 52) {
                    $stageNum = 4;
                } elseif ($termIndex <= 72) {
                    $stageNum = 5;
                } elseif ($termIndex <= 96) {
                    $stageNum = 6;
                } elseif ($termIndex <= 120) {
                    $stageNum = 7;
                } elseif ($termIndex <= 146) {
                    $stageNum = 8;
                } elseif ($termIndex <= 172) {
                    $stageNum = 9;
                } else {
                    $stageNum = 10;
                }

                $term = trim($row[0] ?? '');
                $definition = trim($row[1] ?? '');
                $pos = trim($row[2] ?? '');
                $pattern = trim($row[3] ?? '');
                $examples = trim($row[4] ?? '');

                $batchTerms[] = [
                    'topic_id' => $topic->id,
                    'order_in_topic' => $termIndex,
                    'stage_num' => $stageNum,
                    'term' => $term,
                    'definition' => $definition,
                    'part_of_speech' => $pos,
                    'pattern' => $pattern,
                    'examples' => $examples,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            fclose($handle);

            // Chunk insert for high performance
            foreach (array_chunk($batchTerms, 50) as $chunk) {
                CurriculumTerm::insert($chunk);
            }

            $topic->update(['total_terms' => $termIndex]);
            $this->command->info("Topic #{$order}: {$meta['title']} - Imported {$termIndex} terms across 10 stages.");
        }

        $this->command->info("Completed importing all 25 Curriculum topics and 5,000 terms successfully!");
    }
}
