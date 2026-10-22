<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhrasalVerb;

class PhrasalVerbSeeder extends Seeder
{
    public function run(): void
    {
        $phrases = [
            [
                'phrase' => 'look forward to',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Trông mong, háo hức chờ đợi điều gì',
                'example_en' => 'I am really looking forward to working with you on this project.',
                'example_vi' => 'Tôi thực sự rất háo hức mong chờ được làm việc cùng bạn trong dự án này.',
                'component_words_json' => ['look', 'forward'],
                'cefr_level' => 'B1',
            ],
            [
                'phrase' => 'give up',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Từ bỏ, đầu hàng, ngừng cố gắng',
                'example_en' => 'Never give up on your dreams no matter how hard it gets.',
                'example_vi' => 'Đừng bao giờ từ bỏ ước mơ của bạn dù cho mọi thứ có khó khăn đến đâu.',
                'component_words_json' => ['give', 'up'],
                'cefr_level' => 'A2',
            ],
            [
                'phrase' => 'take care of',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Chăm sóc, chịu trách nhiệm xử lý',
                'example_en' => 'She takes care of her younger brother when their parents are away.',
                'example_vi' => 'Cô ấy chăm sóc em trai mình khi bố mẹ đi vắng.',
                'component_words_json' => ['take', 'care'],
                'cefr_level' => 'A2',
            ],
            [
                'phrase' => 'make a decision',
                'type' => 'collocation',
                'meaning_vi' => 'Đưa ra quyết định, quyết định',
                'example_en' => 'It is time for the committee to make a final decision.',
                'example_vi' => 'Đã đến lúc ban hội đồng phải đưa ra quyết định cuối cùng.',
                'component_words_json' => ['make', 'decision'],
                'cefr_level' => 'B1',
            ],
            [
                'phrase' => 'break down',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Bị hỏng hóc (máy móc, xe cộ); suy sụp tinh thần',
                'example_en' => 'Our car broke down on the highway yesterday morning.',
                'example_vi' => 'Chiếc xe của chúng tôi bị hỏng trên đường cao tốc vào sáng hôm qua.',
                'component_words_json' => ['break', 'down'],
                'cefr_level' => 'B1',
            ],
            [
                'phrase' => 'carry out',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Tiến hành, thực hiện (nghiên cứu, kế hoạch, nhiệm vụ)',
                'example_en' => 'The medical team carried out several scientific experiments.',
                'example_vi' => 'Đội ngũ y tế đã tiến hành một số thí nghiệm khoa học.',
                'component_words_json' => ['carry', 'out'],
                'cefr_level' => 'B2',
            ],
            [
                'phrase' => 'pay attention to',
                'type' => 'collocation',
                'meaning_vi' => 'Chú ý đến, tập trung vào điều gì',
                'example_en' => 'Please pay close attention to the safety instructions.',
                'example_vi' => 'Xin vui lòng chú ý kỹ đến các hướng dẫn an toàn.',
                'component_words_json' => ['pay', 'attention'],
                'cefr_level' => 'A2',
            ],
            [
                'phrase' => 'run out of',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Cạn kiệt, hết sạch (tiền, thời gian, xăng...)',
                'example_en' => 'We are running out of time so let us conclude the meeting.',
                'example_vi' => 'Chúng ta sắp hết thời gian rồi, vì vậy hãy kết thúc cuộc họp.',
                'component_words_json' => ['run', 'out'],
                'cefr_level' => 'B1',
            ],
            [
                'phrase' => 'find out',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Tìm ra, phát hiện ra thông tin',
                'example_en' => 'I will call the hotel to find out if there are vacant rooms.',
                'example_vi' => 'Tôi sẽ gọi điện cho khách sạn để tìm hiểu xem còn phòng trống hay không.',
                'component_words_json' => ['find', 'out'],
                'cefr_level' => 'A2',
            ],
            [
                'phrase' => 'get along with',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Hòa thuận, có mối quan hệ tốt đẹp với ai',
                'example_en' => 'He gets along very well with all his colleagues at work.',
                'example_vi' => 'Anh ấy rất hòa thuận với tất cả các đồng nghiệp ở nơi làm việc.',
                'component_words_json' => ['get', 'along'],
                'cefr_level' => 'B1',
            ],
            [
                'phrase' => 'set up',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Thành lập, thiết lập, dựng lên (công ty, hệ thống)',
                'example_en' => 'They plan to set up a new technology branch in Singapore.',
                'example_vi' => 'Họ dự định thành lập một chi nhánh công nghệ mới tại Singapore.',
                'component_words_json' => ['set', 'up'],
                'cefr_level' => 'A2',
            ],
            [
                'phrase' => 'put off',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Hoãn lại, dời lịch hẹn',
                'example_en' => 'The match was put off due to heavy rain and storms.',
                'example_vi' => 'Trận đấu đã bị hoãn lại do mưa to và bão lớn.',
                'component_words_json' => ['put', 'off'],
                'cefr_level' => 'B1',
            ],
            [
                'phrase' => 'take part in',
                'type' => 'collocation',
                'meaning_vi' => 'Tham gia vào (sự kiện, cuộc thi, hoạt động)',
                'example_en' => 'More than 500 students took part in the marathon competition.',
                'example_vi' => 'Hơn 500 sinh viên đã tham gia vào cuộc thi marathon.',
                'component_words_json' => ['take', 'part'],
                'cefr_level' => 'A2',
            ],
            [
                'phrase' => 'come up with',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Nảy ra, nghĩ ra (ý tưởng, giải pháp, sáng kiến)',
                'example_en' => 'She came up with a brilliant marketing strategy for the campaign.',
                'example_vi' => 'Cô ấy đã nghĩ ra một chiến lược tiếp thị xuất sắc cho chiến dịch.',
                'component_words_json' => ['come', 'up'],
                'cefr_level' => 'B2',
            ],
            [
                'phrase' => 'bring about',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Mang lại, gây ra, dẫn đến (sự thay đổi lớn)',
                'example_en' => 'The internet has brought about huge changes in how we communicate.',
                'example_vi' => 'Internet đã mang lại những thay đổi to lớn trong cách chúng ta giao tiếp.',
                'component_words_json' => ['bring', 'about'],
                'cefr_level' => 'B2',
            ],
            [
                'phrase' => 'turn down',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Từ chối (lời mời, đề nghị); vặn nhỏ âm lượng',
                'example_en' => 'He had to turn down the job offer because the commute was too far.',
                'example_vi' => 'Anh ấy phải từ chối lời mời làm việc vì quãng đường đi làm quá xa.',
                'component_words_json' => ['turn', 'down'],
                'cefr_level' => 'B1',
            ],
            [
                'phrase' => 'catch up with',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Bắt kịp, đuổi kịp (tiến độ, kiến thức, bạn bè)',
                'example_en' => 'After missing three days of school, he studied late to catch up with his class.',
                'example_vi' => 'Sau khi nghỉ học 3 ngày, cậu ấy học muộn để bắt kịp tiến độ với lớp.',
                'component_words_json' => ['catch', 'up'],
                'cefr_level' => 'B1',
            ],
            [
                'phrase' => 'take advantage of',
                'type' => 'collocation',
                'meaning_vi' => 'Tận dụng, lợi dụng cơ hội tốt',
                'example_en' => 'You should take advantage of this free scholarship program.',
                'example_vi' => 'Bạn nên tận dụng chương trình học bổng miễn phí này.',
                'component_words_json' => ['take', 'advantage'],
                'cefr_level' => 'B2',
            ],
            [
                'phrase' => 'look into',
                'type' => 'phrasal_verb',
                'meaning_vi' => 'Điều tra, xem xét kỹ lưỡng một vấn đề',
                'example_en' => 'The police are looking into the cause of the sudden accident.',
                'example_vi' => 'Cảnh sát đang điều tra nguyên nhân của vụ tai nạn bất ngờ.',
                'component_words_json' => ['look', 'into'],
                'cefr_level' => 'B1',
            ],
            [
                'phrase' => 'keep in touch with',
                'type' => 'idiom',
                'meaning_vi' => 'Giữ liên lạc với ai đó',
                'example_en' => 'We still keep in touch with our high school classmates.',
                'example_vi' => 'Chúng tôi vẫn giữ liên lạc với các bạn học thời cấp ba.',
                'component_words_json' => ['keep', 'touch'],
                'cefr_level' => 'A2',
            ],
        ];

        foreach ($phrases as $p) {
            PhrasalVerb::updateOrCreate(
                ['phrase' => $p['phrase']],
                $p
            );
        }
    }
}
