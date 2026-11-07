<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LearningTip;

class LearningTipSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        LearningTip::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $tips = [
            [
                'title' => 'Phương pháp Lặp lại ngắt quãng (Spaced Repetition System - SRS)',
                'category' => 'Mẹo nhớ từ vựng',
                'order_index' => 1,
                'content_html' => '
                    <p class="lead">Bộ não con người có cơ chế quên thông tin tự nhiên theo biểu đồ đường cong lãng quên của Ebbinghaus. Nếu không ôn lại, bạn sẽ quên tới 80% từ mới sau 48 giờ.</p>
                    <h5>Cách áp dụng SRS hiệu quả:</h5>
                    <ul>
                        <li><strong>Lần 1:</strong> Ôn lại sau 10 - 20 phút ngay sau khi học xong bộ từ.</li>
                        <li><strong>Lần 2:</strong> Ôn lại sau 24 giờ (vào ngày hôm sau).</li>
                        <li><strong>Lần 3:</strong> Ôn lại sau 3 ngày.</li>
                        <li><strong>Lần 4:</strong> Ôn lại sau 7 ngày và 1 tháng.</li>
                    </ul>
                    <div class="alert alert-info">Trên hệ thống Oxford 3000, mỗi khi bạn bấm nút <strong>[S] Đã nhớ</strong>, thuật toán tự động tính toán thời gian lặp lại tối ưu cho bạn!</div>
                ',
            ],
            [
                'title' => 'Kỹ thuật Phân tích vị trí từ loại (Danh - Động - Tính - Trạng)',
                'category' => 'Mẹo làm bài trắc nghiệm',
                'order_index' => 2,
                'content_html' => '
                    <p class="lead">Khi gặp câu hỏi điền khuyết hoặc trắc nghiệm, đừng vội dịch nghĩa. Hãy phân tích cấu trúc ngữ pháp trước:</p>
                    <ul>
                        <li><strong>Sau mạo từ (a, an, the), tính từ sở hữu (my, your...):</strong> Cần một <strong>Danh từ (Noun)</strong>.</li>
                        <li><strong>Trước danh từ hoặc sau động từ to be / linking verb:</strong> Cần một <strong>Tính từ (Adjective)</strong>.</li>
                        <li><strong>Sau trợ động từ hoặc sau chủ ngữ:</strong> Cần một <strong>Động từ (Verb)</strong>.</li>
                        <li><strong>Bổ nghĩa cho động từ, tính từ hoặc đứng đầu/cuối câu:</strong> Cần một <strong>Trạng từ (Adverb - đuôi -ly)</strong>.</li>
                    </ul>
                ',
            ],
            [
                'title' => 'Bí quyết Nối âm (Linking Sounds) và Ngữ điệu chuẩn Mỹ',
                'category' => 'Mẹo phát âm',
                'order_index' => 3,
                'content_html' => '
                    <p class="lead">Người bản xứ nói tiếng Anh trôi chảy và tự nhiên nhờ kỹ thuật nối âm mượt mà giữa các từ.</p>
                    <h5>3 nguyên tắc nối âm cơ bản:</h5>
                    <ul>
                        <li><strong>Phụ âm nối với Nguyên âm:</strong> <em>"Hold on"</em> phát âm là <em>/həʊl-dɒn/</em>, <em>"an apple"</em> phát âm là <em>/ə-næpl/</em>.</li>
                        <li><strong>Phụ âm nối với Phụ âm giống nhau:</strong> Giữ âm và bật một lần: <em>"black coffee"</em>, <em>"bad day"</em>.</li>
                        <li><strong>Nguyên âm nối với Nguyên âm:</strong> Chèn âm lướt nhẹ /w/ hoặc /j/: <em>"go out" -> /ɡəʊ-w-aʊt/</em>, <em>"see it" -> /si:-j-ɪt/</em>.</li>
                    </ul>
                ',
            ],
            [
                'title' => 'Bí kíp sử dụng bàn phím thao tác thần tốc trên Quizlet 3000',
                'category' => 'Mẹo nhớ từ vựng',
                'order_index' => 4,
                'content_html' => '
                    <p class="lead">Thay vì dùng chuột bấm, hãy để hai bàn tay trên bàn phím để tăng tốc độ ghi nhớ từ vựng lên gấp 3 lần:</p>
                    <table class="table table-bordered">
                        <thead><tr><th>Phím tắt</th><th>Chức năng</th></tr></thead>
                        <tbody>
                            <tr><td><code>Space</code></td><td>Lật qua lại giữa mặt trước và mặt sau thẻ</td></tr>
                            <tr><td><code>←</code> hoặc <code>Q</code></td><td>Lùi lại thẻ từ vựng trước</td></tr>
                            <tr><td><code>→</code> hoặc <code>E</code></td><td>Chuyển sang thẻ từ vựng kế tiếp</td></tr>
                            <tr><td><code>A</code></td><td>Phát âm từ vựng chuẩn giọng Mỹ (Nghe)</td></tr>
                            <tr><td><code>D</code></td><td>Kích hoạt Microphone để Luyện phát âm (Nói)</td></tr>
                            <tr><td><code>S</code></td><td>Đánh dấu [Đã nhớ] từ này</td></tr>
                            <tr><td><code>X</code></td><td>Đánh dấu [Cần ôn] lại từ này</td></tr>
                        </tbody>
                    </table>
                ',
            ],
            [
                'title' => 'Cách phân biệt và ghi nhớ 12 thì cơ bản trong tiếng Anh',
                'category' => 'Mẹo ngữ pháp',
                'order_index' => 5,
                'content_html' => '
                    <p class="lead">Hãy nhớ thì tiếng Anh theo trục thời gian (Quá khứ - Hiện tại - Tương lai) và 4 thể biểu hiện:</p>
                    <ul>
                        <li><strong>Đơn (Simple):</strong> Sự thật, thói quen, lịch trình hoặc mốc thời gian cố định.</li>
                        <li><strong>Tiếp diễn (Continuous - V-ing):</strong> Đang xảy ra tại một thời điểm cụ thể.</li>
                        <li><strong>Hoàn thành (Perfect - have/has/had + V3/ed):</strong> Kết quả hoặc trải nghiệm tính đến một mốc thời gian.</li>
                        <li><strong>Hoàn thành tiếp diễn:</strong> Nhấn mạnh tính liên tục của hành động kéo dài đến mốc thời gian.</li>
                    </ul>
                ',
            ],
        ];

        foreach ($tips as $tip) {
            LearningTip::create($tip);
        }
    }
}
