<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Grammar;
use App\Models\GrammarQuestion;
use App\Models\UserGrammarProgress;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GrammarPdfFullSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        GrammarQuestion::truncate();
        UserGrammarProgress::truncate();
        Grammar::truncate();
        Schema::enableForeignKeyConstraints();

        $defaultTopic = Topic::first();
        $topicId = $defaultTopic ? $defaultTopic->id : null;

        $lessons = [
            // 1. HIỆN TẠI ĐƠN
            [
                'title' => 'Thì Hiện Tại Đơn (Present Simple Tense)',
                'slug' => 'hien-tai-don',
                'level' => 'A1',
                'summary' => 'Diễn tả thói quen lặp đi lặp lại, chân lý sự thật hiển nhiên và lịch trình cố định.',
                'badge_color' => '#00b074',
                'icon' => 'bi-sun-fill',
                'order_index' => 1,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-success">
                                <tr><th>Thể</th><th>Công thức Chủ động</th><th>Công thức Bị động</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Hiện tại đơn</strong></td>
                                    <td><code>S + V(s/es) + O</code></td>
                                    <td><code>S + am/is/are + V3/Ved (+ by O)</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => '
                    <div class="alert alert-warning py-2 px-3 fs-8 mb-3">
                        <strong>Quy tắc phát âm đuôi -s/es:</strong><br/>
                        • <strong>/ɪz/:</strong> Sau các âm tận cùng: <em>s, z, tʃ (ch), dʒ (ge), ʃ (sh), ʒ</em> (vd: watch, wash, rush, close, cross).<br/>
                        • <strong>/s/:</strong> Sau các âm vô thanh: <em>/p, k, t, f, θ/</em> (vd: cook, wait, hope, laugh).<br/>
                        • <strong>/z/:</strong> Các trường hợp nguyên âm và phụ âm hữu thanh còn lại (vd: play, live, seem, call).
                    </div>
                ',
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp (Formulas)</h4>
                        
                        <div class="card p-3 mb-3 rounded-3 bg-light border">
                            <h6 class="fw-bold text-primary mb-2">A. Động từ thường (Action Verbs):</h6>
                            <ul class="mb-2 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + V(s/es) + O</code><br/>
                                    <em>• Chủ ngữ số nhiều (I/We/You/They/Danh từ số nhiều): Động từ giữ nguyên mẫu (V0).</em><br/>
                                    <em>• Chủ ngữ ngôi thứ 3 số ít (He/She/It/Tên riêng 1 người/vật): Động từ thêm <strong>s/es</strong>.</em>
                                </li>
                                <li><strong>Phủ định (-):</strong> <code>S + do not (don\'t) / does not (doesn\'t) + V nguyên mẫu + O</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Do / Does + S + V nguyên mẫu + O?</code> → Yes, S + do/does. / No, S + don\'t/doesn\'t.</li>
                                <li><strong>Câu hỏi Wh- (?):</strong> <code>Wh- + do / does + S + V nguyên mẫu?</code></li>
                            </ul>
                        </div>

                        <div class="card p-3 mb-3 rounded-3 bg-light border">
                            <h6 class="fw-bold text-primary mb-2">B. Động từ TO BE (am / is / are):</h6>
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + am / is / are + N / Adj / Cụm giới từ</code> (I am, He/She/It is, We/You/They are)</li>
                                <li><strong>Phủ định (-):</strong> <code>S + am not / is not (isn\'t) / are not (aren\'t) + N / Adj</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Am / Is / Are + S + N / Adj?</code></li>
                                <li><strong>Câu hỏi Wh- (?):</strong> <code>Wh- + am / is / are + S + ...?</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-lightbulb-fill me-2"></i>2. Cách sử dụng (Usage)</h4>
                        <div class="row g-2 fs-7">
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 border bg-white h-100 shadow-2xs">
                                    <div class="fw-bold text-success mb-1">1. Thói quen & Tần suất</div>
                                    <p class="text-muted mb-0">Hành động lặp đi lặp lại hàng ngày hoặc theo chu kỳ.<br/><em>VD: He plays football every day. I often work from home.</em></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 border bg-white h-100 shadow-2xs">
                                    <div class="fw-bold text-primary mb-1">2. Chân lý & Sự thật hiển nhiên</div>
                                    <p class="text-muted mb-0">Hiện tượng tự nhiên, quy luật không thay đổi theo thời gian.<br/><em>VD: The sun rises in the East. Water boils at 100°C.</em></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-3 border bg-white h-100 shadow-2xs">
                                    <div class="fw-bold text-teal mb-1">3. Lịch trình cố định</div>
                                    <p class="text-muted mb-0">Lịch khởi hành của xe buýt, máy bay, giờ mở/đóng cửa hàng.<br/><em>VD: Bus number 7 always departs on time.</em></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grammar-section mb-3">
                        <h4 class="fw-bold text-teal mb-2"><i class="bi bi-clock-history me-2"></i>3. Dấu hiệu nhận biết (Signal Words)</h4>
                        <div class="p-3 rounded-3 bg-success-subtle text-success-emphasis border border-success-subtle fs-7">
                            <strong>Trạng từ chỉ tần suất:</strong> Always (luôn luôn), Usually (thường xuyên), Often (thường), Sometimes (thỉnh thoảng), Seldom / Rarely (hiếm khi), Never (không bao giờ).<br/>
                            <strong>Cụm từ thời gian:</strong> Every day / week / month / year, in the morning/afternoon, once a week, twice a month.
                        </div>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'She speaks English so well.', 'vi' => 'Cô ấy nói tiếng Anh rất hay.'],
                    ['en' => 'He plays football every afternoon.', 'vi' => 'Anh ấy chơi bóng đá mỗi buổi chiều.'],
                    ['en' => 'My father goes to work by bus.', 'vi' => 'Bố tôi đi làm bằng xe buýt.'],
                    ['en' => 'Bus number 7 always departs on time.', 'vi' => 'Xe buýt số 7 luôn xuất phát đúng giờ.'],
                    ['en' => 'Summer in Vietnam is very hot.', 'vi' => 'Mùa hè ở Việt Nam rất nóng.'],
                ],
                'exercises_json' => [
                    'practice_words_ed_s' => [
                        ['word' => 'watch', 'ipa' => '/wɒtʃ/', 'ending_s' => '/ɪz/', 'ending_ed' => '/t/'],
                        ['word' => 'count', 'ipa' => '/kaʊnt/', 'ending_s' => '/s/', 'ending_ed' => '/ɪd/'],
                        ['word' => 'stay', 'ipa' => '/steɪ/', 'ending_s' => '/z/', 'ending_ed' => '/d/'],
                        ['word' => 'need', 'ipa' => '/niːd/', 'ending_s' => '/z/', 'ending_ed' => '/ɪd/'],
                        ['word' => 'cook', 'ipa' => '/kʊk/', 'ending_s' => '/s/', 'ending_ed' => '/t/'],
                        ['word' => 'call', 'ipa' => '/kɔːl/', 'ending_s' => '/z/', 'ending_ed' => '/d/'],
                    ],
                    'translations' => [
                        ['id' => 1, 'vi' => 'Mary ghét mèo nhưng cô ấy rất yêu chó.', 'expected' => 'Mary hates cats but she loves dogs', 'tense' => 'Hiện tại đơn'],
                        ['id' => 2, 'vi' => 'Anh ấy thường chơi bóng đá mỗi buổi chiều.', 'expected' => 'He often plays football every afternoon', 'tense' => 'Hiện tại đơn'],
                        ['id' => 3, 'vi' => 'Tôi thường xuyên làm việc tại nhà.', 'expected' => 'I often work from home', 'tense' => 'Hiện tại đơn'],
                        ['id' => 4, 'vi' => 'Họ không thường xuyên đi ăn ngoài.', 'expected' => 'They do not often eat out', 'tense' => 'Hiện tại đơn'],
                        ['id' => 5, 'vi' => 'Cha tôi đi làm bằng xe buýt và mẹ tôi đi xe máy.', 'expected' => 'My father goes to work by bus and my mother goes by motorbike', 'tense' => 'Hiện tại đơn'],
                        ['id' => 6, 'vi' => 'Gia đình tôi thỉnh thoảng xem ti vi vào buổi tối và nói chuyện cùng nhau.', 'expected' => 'My family sometimes watches TV in the evening and talks together', 'tense' => 'Hiện tại đơn'],
                        ['id' => 7, 'vi' => 'Chúng tôi không thích học ngoại ngữ.', 'expected' => 'We do not like learning foreign languages', 'tense' => 'Hiện tại đơn'],
                        ['id' => 8, 'vi' => 'Minh là kỹ sư và vợ anh ấy là bác sỹ.', 'expected' => 'Minh is an engineer and his wife is a doctor', 'tense' => 'Hiện tại đơn'],
                        ['id' => 9, 'vi' => 'Tôi có 2 con chó. Tôi thường đi dạo với chúng vào buổi tối.', 'expected' => 'I have two dogs. I usually walk them in the evening', 'tense' => 'Hiện tại đơn'],
                        ['id' => 10, 'vi' => 'Sơn Tùng là ca sỹ và anh ấy rất nổi tiếng ở Việt Nam.', 'expected' => 'Son Tung is a singer and he is very famous in Vietnam', 'tense' => 'Hiện tại đơn'],
                        ['id' => 11, 'vi' => 'Tôi là fan của nhạc Pop.', 'expected' => 'I am a fan of pop music', 'tense' => 'Hiện tại đơn'],
                        ['id' => 12, 'vi' => 'Anh ấy có thể uống nhiều bia.', 'expected' => 'He can drink a lot of beer', 'tense' => 'Hiện tại đơn'],
                        ['id' => 13, 'vi' => 'Cha tôi thường nấu bữa tối.', 'expected' => 'My father usually cooks dinner', 'tense' => 'Hiện tại đơn'],
                        ['id' => 14, 'vi' => 'Bạn có thường xuyên nghe nhạc không?', 'expected' => 'Do you often listen to music?', 'tense' => 'Hiện tại đơn'],
                        ['id' => 15, 'vi' => 'Jim học tiếng Trung ở đâu vậy?', 'expected' => 'Where does Jim study Chinese?', 'tense' => 'Hiện tại đơn'],
                        ['id' => 16, 'vi' => 'Khi nào thì cửa hàng này đóng cửa?', 'expected' => 'When does this shop close?', 'tense' => 'Hiện tại đơn'],
                        ['id' => 17, 'vi' => 'Ngọc đến trường bằng gì?', 'expected' => 'How does Ngoc go to school?', 'tense' => 'Hiện tại đơn'],
                        ['id' => 18, 'vi' => 'Bạn thường thức dậy lúc mấy giờ?', 'expected' => 'What time do you usually get up?', 'tense' => 'Hiện tại đơn'],
                        ['id' => 19, 'vi' => 'Gia đình Huy có 4 người.', 'expected' => 'There are 4 people in Huy\'s family', 'tense' => 'Hiện tại đơn'],
                        ['id' => 20, 'vi' => 'Anh ta đi tập thể dục thường xuyên như thế nào?', 'expected' => 'How often does he exercise?', 'tense' => 'Hiện tại đơn'],
                        ['id' => 21, 'vi' => 'Mary rất giỏi về kỹ năng máy tính.', 'expected' => 'Mary is very good at computer skills', 'tense' => 'Hiện tại đơn'],
                        ['id' => 22, 'vi' => 'Bạn có hay đi biển không?', 'expected' => 'Do you often go to the beach?', 'tense' => 'Hiện tại đơn'],
                        ['id' => 23, 'vi' => 'Đây là một ngôi nhà đẹp.', 'expected' => 'This is a beautiful house', 'tense' => 'Hiện tại đơn'],
                        ['id' => 24, 'vi' => 'Mỗi năm cô ấy đều đi du lịch vào mùa hè.', 'expected' => 'She travels in the summer every year', 'tense' => 'Hiện tại đơn'],
                        ['id' => 25, 'vi' => 'Duy là một nhân viên tốt, anh ấy thường đi làm rất sớm.', 'expected' => 'Duy is a good employee, he usually goes to work very early', 'tense' => 'Hiện tại đơn'],
                        ['id' => 26, 'vi' => 'Xe buýt số 7 luôn xuất phát đúng giờ.', 'expected' => 'Bus number 7 always departs on time', 'tense' => 'Hiện tại đơn'],
                        ['id' => 27, 'vi' => 'Tôi hay ăn trưa lúc 11 giờ.', 'expected' => 'I usually have lunch at 11', 'tense' => 'Hiện tại đơn'],
                        ['id' => 28, 'vi' => 'Quản lí của Tom rất giỏi, anh ấy thường rời công ty trễ.', 'expected' => 'Tom\'s manager is very good, he usually leaves work late', 'tense' => 'Hiện tại đơn'],
                        ['id' => 29, 'vi' => 'Mùa hè ở Việt Nam rất nóng.', 'expected' => 'Summer in Vietnam is very hot', 'tense' => 'Hiện tại đơn'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'My father ______ dinner every evening.',
                        'options_json' => ['make', 'makes', 'making', 'is make'],
                        'correct_answer' => 'makes',
                        'explanation' => 'Chủ ngữ "My father" là ngôi thứ 3 số ít, động từ chia "makes".',
                    ],
                    [
                        'type' => 'fill_in_blank',
                        'question' => 'She ___ (not/eat) eggs; they make her ill.',
                        'options_json' => null,
                        'correct_answer' => 'does not eat',
                        'explanation' => 'Ngôi thứ 3 số ít phủ định thì hiện tại đơn dùng "does not eat" hoặc "doesn\'t eat".',
                    ],
                    [
                        'type' => 'multiple_choice',
                        'question' => '______ your sister work in a bank?',
                        'options_json' => ['Do', 'Does', 'Is', 'Are'],
                        'correct_answer' => 'Does',
                        'explanation' => 'Câu hỏi nghi vấn động từ thường với chủ ngữ ngôi thứ 3 số ít "your sister" dùng trợ động từ "Does".',
                    ],
                    [
                        'type' => 'fill_in_blank',
                        'question' => 'Bus number 7 always ___ (depart) on time.',
                        'options_json' => null,
                        'correct_answer' => 'departs',
                        'explanation' => 'Chủ ngữ "Bus number 7" số ít, lịch trình cố định dùng hiện tại đơn -> "departs".',
                    ],
                ],
            ],

            // 2. HIỆN TẠI TIẾP DIỄN
            [
                'title' => 'Thì Hiện Tại Tiếp Diễn (Present Continuous Tense)',
                'slug' => 'hien-tai-tiep-dien',
                'level' => 'A1',
                'summary' => 'Diễn tả hành động đang xảy ra ngay lúc nói, yêu cầu mệnh lệnh và kế hoạch chắc chắn trong tương lai gần.',
                'badge_color' => '#0284c7',
                'icon' => 'bi-play-circle-fill',
                'order_index' => 2,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-primary">
                                <tr><th>Thể</th><th>Công thức Chủ động</th><th>Công thức Bị động</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Hiện tại tiếp diễn</strong></td>
                                    <td><code>S + am/is/are + V-ing + O</code></td>
                                    <td><code>S + am/is/are + being + V3/Ved (+ by O)</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + am / is / are + V-ing + O</code></li>
                                <li><strong>Phủ định (-):</strong> <code>S + am / is / are + not + V-ing + O</code> (isn\'t / aren\'t)</li>
                                <li><strong>Nghi vấn (?):</strong> <code>Am / Is / Are + S + V-ing + O?</code></li>
                                <li><strong>Wh- Question:</strong> <code>Wh- + am / is / are + S + V-ing?</code> (What are you doing? Where is he going?)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-lightbulb-fill me-2"></i>2. Cách sử dụng chính</h4>
                        <ul class="fs-7">
                            <li><strong>1. Hành động đang diễn ra ngay lúc nói:</strong> <em>John is eating lunch now. It is raining very hard.</em></li>
                            <li><strong>2. Sau một mệnh lệnh / yêu cầu chú ý:</strong> <em>Look! He is running so fast. Listen! The baby is crying.</em></li>
                            <li><strong>3. Kế hoạch hoặc lịch trình sắp xảy ra trong tương lai gần (đã có kế hoạch/căn cứ):</strong> <em>Tonight, Mai is going to Jim\'s birthday party. Tomorrow, I am going to the cinema.</em></li>
                        </ul>
                    </div>

                    <div class="grammar-section mb-3">
                        <h4 class="fw-bold text-teal mb-2"><i class="bi bi-clock-history me-2"></i>3. Dấu hiệu nhận biết</h4>
                        <p class="fs-7 text-muted">Now, right now, at the moment, at present, Look!, Listen!, Be careful!, Hurry up!</p>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'I am studying English at the moment.', 'vi' => 'Tôi đang học tiếng Anh vào lúc này.'],
                    ['en' => 'Look! The baby is crying.', 'vi' => 'Nhìn kìa! Em bé đang khóc.'],
                    ['en' => 'My dad is watching TV, and my mother is cleaning the floor.', 'vi' => 'Bố tôi đang xem TV, và mẹ tôi đang lau sàn.'],
                    ['en' => 'Tonight, Mai is going to Jim\'s birthday party.', 'vi' => 'Tối nay Mai sẽ đi sinh nhật của Jim.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Nghe kìa! Em bé đang khóc.', 'expected' => 'Listen! The baby is crying', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 2, 'vi' => 'Nhìn kìa! Sơn đang đánh đàn và Duy đang hát.', 'expected' => 'Look! Son is playing piano and Duy is singing', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 3, 'vi' => 'Huy đang làm bài tập và Dũng đang học Tiếng Anh.', 'expected' => 'Huy is doing homework and Duy is learning English', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 4, 'vi' => 'Tôi đang gội đầu.', 'expected' => 'I am washing my hair', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 5, 'vi' => 'Jim đang viết một lá thư cho bạn gái của anh ấy.', 'expected' => 'Jim is writing a letter for his girlfriend', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 6, 'vi' => 'Anh ấy đang ngồi cạnh cửa sổ và nghe radio.', 'expected' => 'He is sitting near the window and listening to the radio', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 7, 'vi' => 'Trời đang mưa.', 'expected' => 'It is raining', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 8, 'vi' => 'Bạn đang làm gì vậy?', 'expected' => 'What are you doing?', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 9, 'vi' => 'Tôi đang đợi xe buýt.', 'expected' => 'I am waiting for the bus', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 10, 'vi' => 'Cha tôi đang xem ti vi, và mẹ tôi thì đang lau sàn.', 'expected' => 'My dad is watching TV, and my mother is cleaning the floor', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 11, 'vi' => 'Tôi đang làm việc tại câu lạc bộ từ thiện.', 'expected' => 'I am working at a charity club', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 12, 'vi' => 'Bố tôi đang trả lời điện thoại.', 'expected' => 'My father is answering the phone', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 13, 'vi' => 'Anh trai tôi đang tưới cây.', 'expected' => 'My brother is watering a tree', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 14, 'vi' => 'Ông chủ đang viết báo cáo.', 'expected' => 'The boss is writing a report', 'tense' => 'Hiện tại tiếp diễn'],
                        ['id' => 15, 'vi' => 'Bạn nên đem theo áo khoác, trời đang trở lạnh.', 'expected' => 'You should bring a jacket, it is getting cold', 'tense' => 'Hiện tại tiếp diễn'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'Listen! Someone ______ at the door.',
                        'options_json' => ['knocks', 'is knocking', 'knocked', 'are knocking'],
                        'correct_answer' => 'is knocking',
                        'explanation' => 'Sau mệnh lệnh "Listen!" biểu thị hành động đang diễn ra -> dùng hiện tại tiếp diễn "is knocking".',
                    ],
                    [
                        'type' => 'fill_in_blank',
                        'question' => 'Look! The boy ___ (run) so fast.',
                        'options_json' => null,
                        'correct_answer' => 'is running',
                        'explanation' => 'Dấu hiệu "Look!" + chủ ngữ "The boy" -> chia "is running".',
                    ],
                ],
            ],

            // 3. HIỆN TẠI HOÀN THÀNH
            [
                'title' => 'Thì Hiện Tại Hoàn Thành (Present Perfect Tense)',
                'slug' => 'hien-tai-hoan-thanh',
                'level' => 'A2',
                'summary' => 'Hành động kéo dài từ quá khứ đến hiện tại, hành động đã xảy ra không rõ thời gian hoặc diễn tả trải nghiệm.',
                'badge_color' => '#8b5cf6',
                'icon' => 'bi-check-all',
                'order_index' => 3,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-info">
                                <tr><th>Thể</th><th>Công thức Chủ động</th><th>Công thức Bị động</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Hiện tại hoàn thành</strong></td>
                                    <td><code>S + have/has + V3/Ved + O</code></td>
                                    <td><code>S + have/has + been + V3/Ved (+ by O)</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + have / has + V3 / Ved + O</code></li>
                                <li><strong>Phủ định (-):</strong> <code>S + have / has + not (haven\'t / hasn\'t) + V3 / Ved + O</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Have / Has + S + V3 / Ved + O?</code></li>
                                <li><strong>Wh- Question:</strong> <code>Wh- + have / has + S + V3 / Ved?</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-lightbulb-fill me-2"></i>2. 3 Cách dùng trọng tâm</h4>
                        <ol class="fs-7 mb-0">
                            <li><strong>Hành động bắt đầu trong quá khứ và vẫn còn kéo dài đến hiện tại:</strong> thường dùng với <em>for</em> (khoảng thời gian) và <em>since</em> (mốc thời gian).<br/><em>VD: I have worked at Sony since 1998. I have taught English for 5 months.</em></li>
                            <li><strong>Hành động đã xảy ra trong quá khứ nhưng không đề cập rõ thời gian cụ thể:</strong><br/><em>VD: Ben has just come back from his business trip. We have already reviewed the final report.</em></li>
                            <li><strong>Diễn đạt trải nghiệm (kinh nghiệm):</strong> thường đi với <em>ever, never, before, so far</em>.<br/><em>VD: Have you ever visited South America? I have never been to Europe before.</em></li>
                        </ol>
                    </div>

                    <div class="grammar-section mb-3">
                        <h4 class="fw-bold text-teal mb-2"><i class="bi bi-clock-history me-2"></i>3. Dấu hiệu nhận biết</h4>
                        <p class="fs-7 text-muted">Since (+ mốc thời gian), For (+ khoảng thời gian), Already, Yet, Just, Ever, Never, Recently, So far, Until now, Up to now.</p>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'I have worked in this company for 3 years.', 'vi' => 'Tôi đã làm việc ở công ty này được 3 năm.'],
                    ['en' => 'Have you ever been to Russia?', 'vi' => 'Bạn đã từng đến nước Nga bao giờ chưa?'],
                    ['en' => 'My coworker has just bought a new phone.', 'vi' => 'Đồng nghiệp của tôi vừa mới mua chiếc điện thoại mới.'],
                    ['en' => 'We haven\'t seen each other for a long time.', 'vi' => 'Chúng tôi đã không gặp nhau trong một khoảng thời gian dài.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Tôi đã làm ở công ty Manulife được 3 năm.', 'expected' => 'I have worked in Manulife company for 3 years', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 2, 'vi' => 'Huỳnh đã đi Mỹ từ tháng 2.', 'expected' => 'Huynh has gone to America since February', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 3, 'vi' => 'Mary đã làm việc tại ngân hàng được 2 tháng.', 'expected' => 'Mary has worked at the bank for 2 months', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 4, 'vi' => 'Đồng nghiệp của tôi đã mua một chiếc điện thoại mới.', 'expected' => 'My coworker has bought a new phone', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 5, 'vi' => 'Cái máy tính đã bị hỏng rồi.', 'expected' => 'The computer has broken down', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 6, 'vi' => 'Quản lý của tôi đã ăn tối rồi.', 'expected' => 'My manager has already eaten dinner', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 7, 'vi' => 'Bạn đã sống ở đây bao lâu?', 'expected' => 'How long have you lived here?', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 8, 'vi' => 'Tôi đã mất xe đạp.', 'expected' => 'I have lost my bike', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 9, 'vi' => 'Cường chưa về quê 4 năm rồi.', 'expected' => 'Cuong has not gone home for 4 years', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 10, 'vi' => 'Nga chưa gặp tôi 8 ngày rồi.', 'expected' => 'Nga has not seen me for 8 days', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 11, 'vi' => 'Cha của John vừa mua chiếc máy tính mới.', 'expected' => 'John\'s father has just bought a new computer', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 12, 'vi' => 'Chúng tôi đã không gặp nhau trong khoảng thời gian dài.', 'expected' => 'We have not seen each other for a long time', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 13, 'vi' => 'Anh ấy đã không điện thoại cho gia đình từ tháng 8.', 'expected' => 'He has not called his family since August', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 14, 'vi' => 'Bạn đã từng đến Trung Quốc chưa?', 'expected' => 'Have you ever been to China?', 'tense' => 'Hiện tại hoàn thành'],
                        ['id' => 15, 'vi' => 'Tôi chưa từng đến Châu Âu.', 'expected' => 'I have never been to Europe', 'tense' => 'Hiện tại hoàn thành'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'He ______ at his computer for seven hours.',
                        'options_json' => ['is', 'was', 'has been', 'had been'],
                        'correct_answer' => 'has been',
                        'explanation' => 'Dấu hiệu "for seven hours" diễn tả hành động kéo dài đến hiện tại -> chia "has been".',
                    ],
                    [
                        'type' => 'fill_in_blank',
                        'question' => 'We ___ (not / eat) out since mom\'s birthday.',
                        'options_json' => null,
                        'correct_answer' => 'have not eaten',
                        'explanation' => 'Phủ định hiện tại hoàn thành với chủ ngữ "We" -> chia "have not eaten" hoặc "haven\'t eaten".',
                    ],
                ],
            ],

            // 4. HIỆN TẠI HOÀN THÀNH TIẾP DIỄN
            [
                'title' => 'Thì Hiện Tại Hoàn Thành Tiếp Diễn (Present Perfect Continuous)',
                'slug' => 'hien-tai-hoan-thanh-tiep-dien',
                'level' => 'B1',
                'summary' => 'Nhấn mạnh tính liên tục của hành động kéo dài từ quá khứ đến hiện tại hoặc thể hiện sự mệt mỏi, khó chịu khi chờ đợi.',
                'badge_color' => '#ec4899',
                'icon' => 'bi-hourglass-split',
                'order_index' => 4,
                'active_passive_html' => null,
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + have / has + been + V-ing + O</code></li>
                                <li><strong>Phủ định (-):</strong> <code>S + haven\'t / hasn\'t + been + V-ing + O</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Have / Has + S + been + V-ing + O?</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-lightbulb-fill me-2"></i>2. Phân biệt với Hiện Tại Hoàn Thành</h4>
                        <div class="p-3 rounded-3 border bg-light fs-7 mb-3">
                            <p class="mb-2"><strong>Hiện tại hoàn thành (HTHT):</strong> <em>I have waited for you for 1 hour.</em> (Chỉ mức độ bình thường, thông cảm được, tập trung vào kết quả).</p>
                            <p class="mb-0"><strong>Hiện tại hoàn thành tiếp diễn (HTHTTD):</strong> <em>I have been waiting for you for 1 hour!</em> (Nhấn mạnh hành động diễn ra liên tục, không ngắt quãng, thường bày tỏ sự mệt mỏi hoặc khó chịu).</p>
                        </div>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'I have been waiting for you all afternoon.', 'vi' => 'Tôi đã đợi bạn cả buổi chiều.'],
                    ['en' => 'She has been teaching English for 10 years.', 'vi' => 'Cô ấy đã và đang dạy tiếng Anh được 10 năm liên tục.'],
                    ['en' => 'Where have you been? I have been looking for you all day.', 'vi' => 'Anh đã đi đâu vậy? Tôi đã tìm anh cả ngày trời.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Tôi đã đợi bạn cả buổi chiều.', 'expected' => 'I have been waiting for you all afternoon', 'tense' => 'Hiện tại hoàn thành tiếp diễn'],
                        ['id' => 2, 'vi' => 'Cô ấy không đi xem phim từ năm ngoái.', 'expected' => 'She has not been watching movies since last year', 'tense' => 'Hiện tại hoàn thành tiếp diễn'],
                        ['id' => 3, 'vi' => 'Anh ấy gõ bài báo cáo từ sáng rồi đúng không?', 'expected' => 'Has he been typing the report since the morning?', 'tense' => 'Hiện tại hoàn thành tiếp diễn'],
                        ['id' => 4, 'vi' => 'Tôi đã chạy bộ cả buổi sáng.', 'expected' => 'I have been running all morning', 'tense' => 'Hiện tại hoàn thành tiếp diễn'],
                        ['id' => 5, 'vi' => 'Cô ấy đã dạy Tiếng Anh được 10 năm.', 'expected' => 'She has been teaching English for 10 years', 'tense' => 'Hiện tại hoàn thành tiếp diễn'],
                        ['id' => 6, 'vi' => 'Anh đã đi đâu vậy? Tôi đã tìm anh cả ngày.', 'expected' => 'Where have you been? I have been looking for you all day', 'tense' => 'Hiện tại hoàn thành tiếp diễn'],
                        ['id' => 7, 'vi' => 'Jim đã đứng dưới mưa 2 tiếng phải không?', 'expected' => 'Has Jim been standing in the rain for 2 hours?', 'tense' => 'Hiện tại hoàn thành tiếp diễn'],
                        ['id' => 8, 'vi' => 'Mary đã ở thư viện từ trưa nay.', 'expected' => 'Mary has been staying at the library since noon', 'tense' => 'Hiện tại hoàn thành tiếp diễn'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'I ______ for you all afternoon! Where have you been?',
                        'options_json' => ['have been waiting', 'am waiting', 'waited', 'wait'],
                        'correct_answer' => 'have been waiting',
                        'explanation' => 'Nhấn mạnh hành động chờ đợi diễn ra liên tục cả buổi chiều dẫn đến kết quả ở hiện tại -> chia "have been waiting".',
                    ],
                ],
            ],

            // 5. QUÁ KHỨ ĐƠN
            [
                'title' => 'Thì Quá Khứ Đơn (Past Simple Tense)',
                'slug' => 'qua-khu-don',
                'level' => 'A1',
                'summary' => 'Hành động đã xảy ra và kết thúc hoàn toàn trong quá khứ với thời gian xác định rõ ràng.',
                'badge_color' => '#f59e0b',
                'icon' => 'bi-clock-fill',
                'order_index' => 5,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-warning">
                                <tr><th>Thể</th><th>Công thức Chủ động</th><th>Công thức Bị động</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Quá khứ đơn</strong></td>
                                    <td><code>S + V2/Ved + O</code></td>
                                    <td><code>S + was/were + V3/Ved (+ by O)</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => '
                    <div class="alert alert-warning py-2 px-3 fs-8 mb-3">
                        <strong>Quy tắc phát âm đuôi -ed:</strong><br/>
                        • <strong>/ɪd/:</strong> sau âm <em>/t, d/</em> (vd: wanted, needed, started, planted, rented).<br/>
                        • <strong>/t/:</strong> sau các âm vô thanh <em>/p, k, s, f, tʃ (ch), ʃ (sh)/</em> (vd: watched, washed, cooked, helped, danced).<br/>
                        • <strong>/d/:</strong> các âm hữu thanh còn lại (vd: played, lived, called, cleaned, moved).
                    </div>
                ',
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Động từ thường (+):</strong> <code>S + V2 / Ved + O</code></li>
                                <li><strong>Động từ thường (-):</strong> <code>S + did not (didn\'t) + V nguyên mẫu + O</code></li>
                                <li><strong>Động từ thường (?):</strong> <code>Did + S + V nguyên mẫu + O?</code></li>
                                <li><strong>Động từ To Be:</strong> <code>S + was / were ...</code> (I/He/She/It dùng was; We/You/They dùng were)</li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-3">
                        <h4 class="fw-bold text-teal mb-2"><i class="bi bi-clock-history me-2"></i>2. Dấu hiệu nhận biết</h4>
                        <p class="fs-7 text-muted">Yesterday (hôm qua), last night / last week / last month / last year, ... ago (2 days ago), in + năm quá khứ (in 1990, in 2000).</p>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'I moved to Da Nang last year.', 'vi' => 'Tôi đã chuyển đến Đà Nẵng vào năm ngoái.'],
                    ['en' => 'We were very happy yesterday.', 'vi' => 'Hôm qua chúng tôi đã rất vui vẻ.'],
                    ['en' => 'Did you finish your chores?', 'vi' => 'Con đã làm xong việc nhà chưa?'],
                    ['en' => 'The bus left at 5 AM this morning.', 'vi' => 'Xe buýt đã rời đi lúc 5 giờ sáng nay.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Tôi đã chuyển đến Đà Nẵng năm ngoái.', 'expected' => 'I moved to Da Nang last year', 'tense' => 'Quá khứ đơn'],
                        ['id' => 2, 'vi' => 'Chúng tôi đã rất vui ngày hôm qua.', 'expected' => 'We were happy yesterday', 'tense' => 'Quá khứ đơn'],
                        ['id' => 3, 'vi' => 'Thứ 7 tuần trước, tôi đã không đi đá bóng.', 'expected' => 'Last Saturday, I did not play football', 'tense' => 'Quá khứ đơn'],
                        ['id' => 4, 'vi' => 'Cô ấy đã mua nhiều quà vào chuyến đi trước.', 'expected' => 'She bought many gifts on the last trip', 'tense' => 'Quá khứ đơn'],
                        ['id' => 5, 'vi' => 'Có phải hôm qua bạn đã mua quyển sách này?', 'expected' => 'Did you buy this book yesterday?', 'tense' => 'Quá khứ đơn'],
                        ['id' => 6, 'vi' => 'Sáng nay tôi đã không ăn sáng, bây giờ tôi đói quá.', 'expected' => 'This morning, I did not have breakfast, now I am hungry', 'tense' => 'Quá khứ đơn'],
                        ['id' => 7, 'vi' => 'Bạn đã đến đây bằng gì?', 'expected' => 'How did you get here?', 'tense' => 'Quá khứ đơn'],
                        ['id' => 8, 'vi' => 'Quản lí đã gặp tôi 2 ngày trước, anh ấy đã tăng lương của tôi.', 'expected' => 'The manager met me 2 days ago, he increased my salary', 'tense' => 'Quá khứ đơn'],
                        ['id' => 9, 'vi' => 'Nga đã đi học trễ sáng nay.', 'expected' => 'Nga went to school late this morning', 'tense' => 'Quá khứ đơn'],
                        ['id' => 10, 'vi' => '2 tuần trước tôi đã đi tham quan Bà Nà.', 'expected' => '2 weeks ago I visited Ba Na', 'tense' => 'Quá khứ đơn'],
                        ['id' => 11, 'vi' => 'Tối qua cầu rồng không phun lửa.', 'expected' => 'Last night the Dragon Bridge did not breathe fire', 'tense' => 'Quá khứ đơn'],
                        ['id' => 12, 'vi' => 'Tối qua anh đã ở đâu?', 'expected' => 'Where were you last night?', 'tense' => 'Quá khứ đơn'],
                        ['id' => 13, 'vi' => 'Con đã làm xong việc nhà chưa?', 'expected' => 'Did you finish your chores?', 'tense' => 'Quá khứ đơn'],
                        ['id' => 14, 'vi' => 'Thứ 5 tuần trước tôi đã đi quán bar.', 'expected' => 'Last Thursday I went to a bar', 'tense' => 'Quá khứ đơn'],
                        ['id' => 15, 'vi' => 'Mary không đi chơi với tôi ngày hôm qua. Cô ấy đã đi làm.', 'expected' => 'Mary did not hang out with me yesterday. She went to work', 'tense' => 'Quá khứ đơn'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'Where ______ you go last Sunday?',
                        'options_json' => ['do', 'did', 'were', 'have'],
                        'correct_answer' => 'did',
                        'explanation' => 'Câu hỏi nghi vấn quá khứ đơn với động từ thường "go" dùng trợ động từ "did".',
                    ],
                    [
                        'type' => 'fill_in_blank',
                        'question' => 'Yesterday, it ___ (rain) very heavily.',
                        'options_json' => null,
                        'correct_answer' => 'rained',
                        'explanation' => 'Động từ thường quá khứ đơn của "rain" là "rained".',
                    ],
                ],
            ],

            // 6. QUÁ KHỨ TIẾP DIỄN
            [
                'title' => 'Thì Quá Khứ Tiếp Diễn (Past Continuous Tense)',
                'slug' => 'qua-khu-tiep-dien',
                'level' => 'A2',
                'summary' => 'Hành động đang diễn ra tại một thời điểm xác định trong quá khứ, hành động đang diễn ra thì hành động khác cắt ngang.',
                'badge_color' => '#10b981',
                'icon' => 'bi-camera-reels-fill',
                'order_index' => 6,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-success">
                                <tr><th>Thể</th><th>Công thức Chủ động</th><th>Công thức Bị động</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Quá khứ tiếp diễn</strong></td>
                                    <td><code>S + was/were + V-ing + O</code></td>
                                    <td><code>S + was/were + being + V3/Ved (+ by O)</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + was / were + V-ing + O</code></li>
                                <li><strong>Phủ định (-):</strong> <code>S + wasn\'t / weren\'t + V-ing + O</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Was / Were + S + V-ing + O?</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-lightbulb-fill me-2"></i>2. 3 Ngữ cảnh sử dụng cốt lõi</h4>
                        <ul class="fs-7">
                            <li><strong>1. Hành động đang xảy ra tại một thời điểm cụ thể trong quá khứ:</strong> <em>At 8 PM yesterday, they were watching a movie.</em></li>
                            <li><strong>2. Hành động đang xảy ra thì có hành động khác cắt ngang:</strong> hành động đang xảy ra chia <strong>Quá khứ tiếp diễn</strong>, hành động xen vào chia <strong>Quá khứ đơn</strong> (thường dùng liên từ <em>when</em> hoặc <em>while</em>).<br/><em>VD: While I was driving home, Peter called me. I was presenting when the mic broke.</em></li>
                            <li><strong>3. Hai hành động diễn ra song song cùng lúc trong quá khứ:</strong> <em>Yesterday, I was cooking while my sister was washing the dishes.</em></li>
                        </ul>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'Alice saw an accident while she was catching the bus.', 'vi' => 'Alice đã nhìn thấy một tai nạn khi cô ấy đang bắt xe buýt.'],
                    ['en' => 'What were you doing at 8 PM yesterday?', 'vi' => 'Bạn đã đang làm gì lúc 8 giờ tối hôm qua vậy?'],
                    ['en' => 'I was presenting when the mic broke.', 'vi' => 'Tôi đang thuyết trình thì cái mic bị hỏng.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Alice đã nhìn thấy một tai nạn khi cô ta đang bắt xe buýt.', 'expected' => 'Alice saw an accident while she was catching the bus', 'tense' => 'Quá khứ tiếp diễn'],
                        ['id' => 2, 'vi' => 'Bạn đã đang làm gì khi tôi gọi vậy?', 'expected' => 'What were you doing when I called?', 'tense' => 'Quá khứ tiếp diễn'],
                        ['id' => 3, 'vi' => 'Tháng 7 năm ngoái, trời đã mưa rất to.', 'expected' => 'Last July, it was raining heavily', 'tense' => 'Quá khứ tiếp diễn'],
                        ['id' => 4, 'vi' => 'Trong khi mọi người đang nói chuyện với nhau, anh ta đã đang đọc sách.', 'expected' => 'While everyone was talking to each other, he was reading a book', 'tense' => 'Quá khứ tiếp diễn'],
                        ['id' => 5, 'vi' => 'Mary đã ngã khi chúng tôi đang chạy tuần trước.', 'expected' => 'Mary fell while we were running last week', 'tense' => 'Quá khứ tiếp diễn'],
                        ['id' => 6, 'vi' => 'Họ đang xem phim lúc 7 giờ tối hôm qua.', 'expected' => 'They were watching a movie at 7 PM yesterday', 'tense' => 'Quá khứ tiếp diễn'],
                        ['id' => 7, 'vi' => 'Trong khi tôi đang lái xe về nhà, Peter đã cố gọi cho tôi.', 'expected' => 'While I was driving home, Peter was trying to call me', 'tense' => 'Quá khứ tiếp diễn'],
                        ['id' => 8, 'vi' => 'Tôi đang thuyết trình thì cái mic bị hư.', 'expected' => 'I was presenting when the mic broke', 'tense' => 'Quá khứ tiếp diễn'],
                        ['id' => 9, 'vi' => 'Bạn đang làm gì lúc 8 giờ tối hôm qua vậy?', 'expected' => 'What were you doing at 8 PM yesterday?', 'tense' => 'Quá khứ tiếp diễn'],
                        ['id' => 10, 'vi' => 'Họ đang không làm bài tập khi giáo viên vào lớp chiều hôm qua.', 'expected' => 'They were not doing their homework when the teacher entered the class yesterday', 'tense' => 'Quá khứ tiếp diễn'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'While I ______ in my room, my roommate was having a party.',
                        'options_json' => ['studied', 'was studying', 'study', 'were studying'],
                        'correct_answer' => 'was studying',
                        'explanation' => 'Hành động đang diễn ra song song trong quá khứ dùng "was studying" cho chủ ngữ "I".',
                    ],
                ],
            ],

            // 7. QUÁ KHỨ HOÀN THÀNH
            [
                'title' => 'Thì Quá Khứ Hoàn Thành (Past Perfect Tense)',
                'slug' => 'qua-khu-hoan-thanh',
                'level' => 'B1',
                'summary' => 'Hành động xảy ra và kết thúc trước một hành động khác hoặc một mốc thời gian trong quá khứ.',
                'badge_color' => '#6366f1',
                'icon' => 'bi-arrow-left-circle-fill',
                'order_index' => 7,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-secondary">
                                <tr><th>Thể</th><th>Công thức Chủ động</th><th>Công thức Bị động</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Quá khứ hoàn thành</strong></td>
                                    <td><code>S + had + V3/Ved + O</code></td>
                                    <td><code>S + had + been + V3/Ved (+ by O)</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + had + V3 / Ved + O</code></li>
                                <li><strong>Phủ định (-):</strong> <code>S + had not (hadn\'t) + V3 / Ved + O</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Had + S + V3 / Ved + O?</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-shuffle me-2"></i>2. Công thức phối hợp thì kinh điển</h4>
                        <div class="p-3 rounded-3 bg-primary-subtle text-primary-emphasis border border-primary-subtle fs-7 mb-2">
                            <strong>• BEFORE:</strong> <code>QKHT + before + QKĐ</code> (hoặc <code>Before + QKĐ, QKHT</code>)<br/>
                            <em>VD: The movie had ended before I went out.</em>
                        </div>
                        <div class="p-3 rounded-3 bg-success-subtle text-success-emphasis border border-success-subtle fs-7">
                            <strong>• AFTER:</strong> <code>QKĐ + after + QKHT</code> (hoặc <code>After + QKHT, QKĐ</code>)<br/>
                            <em>VD: After Ann had eaten, she went to bed.</em>
                        </div>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'After Ann had eaten dinner, she went to bed.', 'vi' => 'Sau khi Ann ăn tối xong thì cô ấy đi ngủ.'],
                    ['en' => 'Before he arrived, the party had already ended.', 'vi' => 'Trước khi anh ấy đến thì bữa tiệc đã kết thúc.'],
                    ['en' => 'When they arrived at the airport, the plane had taken off.', 'vi' => 'Khi họ đến sân bay thì máy bay đã cất cánh.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Sau khi ăn xong thì Ann đã đi ngủ.', 'expected' => 'After Ann had eaten, she went to bed', 'tense' => 'Quá khứ hoàn thành'],
                        ['id' => 2, 'vi' => 'Trước khi anh ấy đến thì bữa tiệc đã kết thúc.', 'expected' => 'Before he arrived, the party had ended', 'tense' => 'Quá khứ hoàn thành'],
                        ['id' => 3, 'vi' => 'Nguyên đã rời đi trước thời điểm bạn đến sân bay.', 'expected' => 'Nguyen had left before you arrived at the airport', 'tense' => 'Quá khứ hoàn thành'],
                        ['id' => 4, 'vi' => 'Khi mà Jim thức dậy thì bạn gái anh ấy đã đi học rồi.', 'expected' => 'When Jim woke up, his girlfriend had already gone to school', 'tense' => 'Quá khứ hoàn thành'],
                        ['id' => 5, 'vi' => 'Họ đã hoàn thành bài báo cáo trước thứ sáu tuần trước.', 'expected' => 'They had finished the report before last Friday', 'tense' => 'Quá khứ hoàn thành'],
                        ['id' => 6, 'vi' => 'Trước lúc tôi đi làm thì mẹ tôi đã nấu xong bữa sáng.', 'expected' => 'Before I went to work, my mother had cooked breakfast', 'tense' => 'Quá khứ hoàn thành'],
                        ['id' => 7, 'vi' => 'Khi họ đến, máy bay đã cất cánh.', 'expected' => 'When they arrived, the plane had taken off', 'tense' => 'Quá khứ hoàn thành'],
                        ['id' => 8, 'vi' => 'Trước khi mua cái mũ này, tôi đã xem quanh cửa hàng rất kỹ.', 'expected' => 'Before buying this hat, I had looked around the store very carefully', 'tense' => 'Quá khứ hoàn thành'],
                        ['id' => 9, 'vi' => 'Peter đã xây ngôi nhà này trước khi kết hôn.', 'expected' => 'Peter had built this house before he got married', 'tense' => 'Quá khứ hoàn thành'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'Before he went to bed, he ______ a novel.',
                        'options_json' => ['read', 'had read', 'has read', 'reads'],
                        'correct_answer' => 'had read',
                        'explanation' => 'Hành động đọc tiểu thuyết diễn ra trước khi đi ngủ trong quá khứ -> chia Quá khứ hoàn thành "had read".',
                    ],
                ],
            ],

            // 8. TƯƠNG LAI ĐƠN
            [
                'title' => 'Thì Tương Lai Đơn (Future Simple Tense)',
                'slug' => 'tuong-lai-don',
                'level' => 'A1',
                'summary' => 'Quyết định tức thì tại thời điểm nói, lời hứa, lời đe dọa hoặc dự đoán tương lai không có căn cứ xác thực.',
                'badge_color' => '#06b6d4',
                'icon' => 'bi-forward-fill',
                'order_index' => 8,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-info">
                                <tr><th>Thể</th><th>Công thức Chủ động</th><th>Công thức Bị động</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Tương lai đơn</strong></td>
                                    <td><code>S + will + V nguyên mẫu + O</code></td>
                                    <td><code>S + will + be + V3/Ved (+ by O)</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + will + V nguyên mẫu + O</code> (To be: will be)</li>
                                <li><strong>Phủ định (-):</strong> <code>S + will not (won\'t) + V nguyên mẫu + O</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Will + S + V nguyên mẫu + O?</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-3">
                        <h4 class="fw-bold text-teal mb-2"><i class="bi bi-clock-history me-2"></i>2. Dấu hiệu nhận biết</h4>
                        <p class="fs-7 text-muted">Tomorrow, next week/month/year, soon, someday, in the future, I promise, I think, I hope, maybe.</p>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'I will move to Ho Chi Minh City next year.', 'vi' => 'Tôi sẽ chuyển đến TP Hồ Chí Minh năm tới.'],
                    ['en' => 'I promise I will do my homework before going to school.', 'vi' => 'Tôi hứa tôi sẽ làm bài tập trước khi đi học.'],
                    ['en' => 'Our football team will win the final match.', 'vi' => 'Đội bóng của chúng tôi sẽ thắng trận chung kết.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Tôi sẽ chuyển đến TP Hồ Chí Minh năm tới.', 'expected' => 'I will move to Ho Chi Minh city next year', 'tense' => 'Tương lai đơn'],
                        ['id' => 2, 'vi' => 'Tối nay lớp tôi sẽ đi tiệc.', 'expected' => 'Tonight my class will go to the party', 'tense' => 'Tương lai đơn'],
                        ['id' => 3, 'vi' => 'Cửa hàng sẽ mở cửa lại vào tuần sau.', 'expected' => 'The store will reopen next week', 'tense' => 'Tương lai đơn'],
                        ['id' => 4, 'vi' => 'Tôi sẽ không nói với cô ấy sự thật đâu.', 'expected' => 'I will not tell her the truth', 'tense' => 'Tương lai đơn'],
                        ['id' => 5, 'vi' => 'Tháng sau Tôi sẽ đậu kì thi Tiếng Anh.', 'expected' => 'Next month, I will pass the English exam', 'tense' => 'Tương lai đơn'],
                        ['id' => 6, 'vi' => 'Trời sẽ mưa ngày mai.', 'expected' => 'It will rain tomorrow', 'tense' => 'Tương lai đơn'],
                        ['id' => 7, 'vi' => 'Tôi sẽ không bao giờ nói chuyện với bạn nữa.', 'expected' => 'I will never talk to you again', 'tense' => 'Tương lai đơn'],
                        ['id' => 8, 'vi' => 'Tôi hứa tôi sẽ làm bài tập trước khi đi học.', 'expected' => 'I promise I will do my homework before going to school', 'tense' => 'Tương lai đơn'],
                        ['id' => 9, 'vi' => 'Đội bóng chúng tôi sẽ thắng trận chung kết.', 'expected' => 'Our football team will win the final match', 'tense' => 'Tương lai đơn'],
                        ['id' => 10, 'vi' => 'Tôi sẽ trở thành nhà vô địch.', 'expected' => 'I will become the champion', 'tense' => 'Tương lai đơn'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'She hopes that Mary ______ to the party tonight.',
                        'options_json' => ['comes', 'will come', 'came', 'is coming'],
                        'correct_answer' => 'will come',
                        'explanation' => 'Sau "hope" diễn tả kỳ vọng tương lai -> chia "will come".',
                    ],
                ],
            ],

            // 9. TƯƠNG LAI GẦN (BE GOING TO)
            [
                'title' => 'Thì Tương Lai Gần (Near Future - Be Going To)',
                'slug' => 'tuong-lai-gan',
                'level' => 'A2',
                'summary' => 'Kế hoạch hoặc dự định đã được định trước, hoặc tiên đoán có căn cứ dấu hiệu rõ ràng ở hiện tại.',
                'badge_color' => '#14b8a6',
                'icon' => 'bi-signpost-2-fill',
                'order_index' => 9,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-light">
                                <tr><th>Thể</th><th>Công thức Chủ động</th><th>Công thức Bị động</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Tương lai gần</strong></td>
                                    <td><code>S + am/is/are going to + V0 + O</code></td>
                                    <td><code>S + am/is/are going to + be + V3/Ved (+ by O)</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + am / is / are + going to + V nguyên mẫu + O</code></li>
                                <li><strong>Phủ định (-):</strong> <code>S + am / is / are + not + going to + V nguyên mẫu + O</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Am / Is / Are + S + going to + V nguyên mẫu?</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-lightbulb-fill me-2"></i>2. Điểm khác biệt quan trọng với Will</h4>
                        <p class="fs-7">
                            <strong>1. Tiên đoán có căn cứ thực tế:</strong> <em>The sky is very black. It is going to rain. (Trời nhiều mây đen kịt, trời sắp mưa đấy!)</em><br/>
                            <strong>2. Kế hoạch đã có chuẩn bị trước:</strong> <em>I have bought the tickets. I am going to see a movie tomorrow.</em>
                        </p>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'Look at the clouds! It is going to rain.', 'vi' => 'Nhìn những đám mây kìa! Trời sắp mưa đấy.'],
                    ['en' => 'I have received my salary, I am going to buy a new phone.', 'vi' => 'Tôi vừa nhận lương, tôi dự định sẽ mua một chiếc điện thoại mới.'],
                    ['en' => 'Watch out! The cup is going to fall.', 'vi' => 'Coi chừng! Chiếc cốc sắp rơi xuống kìa.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Nhiều mây quá, trời sẽ mưa đấy.', 'expected' => 'It is cloudy, it is going to rain', 'tense' => 'Tương lai gần'],
                        ['id' => 2, 'vi' => 'Tôi vừa nhận lương, tôi sẽ mua một chiếc điện thoại mới.', 'expected' => 'I have received my salary, I am going to buy a new phone', 'tense' => 'Tương lai gần'],
                        ['id' => 3, 'vi' => 'Coi chừng! chiếc cốc sắp rơi xuống kìa.', 'expected' => 'Watch out! The cup is going to fall', 'tense' => 'Tương lai gần'],
                        ['id' => 4, 'vi' => 'Tôi rất mệt, ngày mai tôi sẽ không đi học được.', 'expected' => 'I am tired, tomorrow I am not going to go to school', 'tense' => 'Tương lai gần'],
                        ['id' => 5, 'vi' => 'Cha mẹ sẽ đến thăm tôi ngày mai, họ vừa điện thoại cho tôi.', 'expected' => 'My parents are going to visit me tomorrow, they have just called me', 'tense' => 'Tương lai gần'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'The sky is completely dark. It ______ rain.',
                        'options_json' => ['will', 'is going to', 'rained', 'shall'],
                        'correct_answer' => 'is going to',
                        'explanation' => 'Tiên đoán dựa trên dấu hiệu thực tế mắt thấy ở hiện tại (bầu trời đen) -> dùng "is going to".',
                    ],
                ],
            ],

            // 10. TƯƠNG LAI TIẾP DIỄN
            [
                'title' => 'Thì Tương Lai Tiếp Diễn (Future Continuous Tense)',
                'slug' => 'tuong-lai-tiep-dien',
                'level' => 'B1',
                'summary' => 'Hành động sẽ đang diễn ra tại một thời điểm xác định cụ thể trong tương lai.',
                'badge_color' => '#3b82f6',
                'icon' => 'bi-fast-forward-fill',
                'order_index' => 10,
                'active_passive_html' => null,
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + will + be + V-ing + O</code></li>
                                <li><strong>Phủ định (-):</strong> <code>S + won\'t + be + V-ing + O</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Will + S + be + V-ing + O?</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-3">
                        <h4 class="fw-bold text-teal mb-2"><i class="bi bi-clock-history me-2"></i>2. Dấu hiệu nhận biết</h4>
                        <p class="fs-7 text-muted">At this time tomorrow, at 8 PM tonight, at this moment next year, at 9 AM tomorrow morning.</p>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'This time next year I will be living in London.', 'vi' => 'Vào thời điểm này năm tới, tôi sẽ đang sinh sống tại Luân Đôn.'],
                    ['en' => 'At 8 PM tonight I will be eating dinner with my family.', 'vi' => 'Lúc 8 giờ tối nay, tôi sẽ đang ăn tối cùng gia đình.'],
                    ['en' => 'Tomorrow when you arrive, they will be playing sports.', 'vi' => 'Ngày mai khi bạn đến thì họ sẽ đang chơi thể thao.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Vào lúc này ngày mai thì tôi đang học bài.', 'expected' => 'At this time tomorrow, I will be studying', 'tense' => 'Tương lai tiếp diễn'],
                        ['id' => 2, 'vi' => 'Sáng mai lúc 7 giờ, chúng tôi đang họp.', 'expected' => 'At 7 AM tomorrow, we will be having a meeting', 'tense' => 'Tương lai tiếp diễn'],
                        ['id' => 3, 'vi' => 'Thứ sáu tuần sau cô ấy sẽ đang ở Nha Trang.', 'expected' => 'Next Friday, she will be staying in Nha Trang', 'tense' => 'Tương lai tiếp diễn'],
                        ['id' => 4, 'vi' => 'Bữa tiệc sẽ bắt đầu lúc 10 giờ tối nay.', 'expected' => 'The party will start at 10 PM tonight', 'tense' => 'Tương lai tiếp diễn'],
                        ['id' => 5, 'vi' => 'Lúc 8 giờ tối mai, tôi và các bạn sẽ đang xem bộ phim Người nhện.', 'expected' => 'At 8 PM tomorrow, my friends and I will be watching Spider-Man movie', 'tense' => 'Tương lai tiếp diễn'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'This time tomorrow, I ______ on the plane to Paris.',
                        'options_json' => ['will sit', 'will be sitting', 'sat', 'am sitting'],
                        'correct_answer' => 'will be sitting',
                        'explanation' => 'Dấu hiệu "This time tomorrow" chỉ thời điểm xác định trong tương lai -> chia "will be sitting".',
                    ],
                ],
            ],

            // 11. TƯƠNG LAI HOÀN THÀNH
            [
                'title' => 'Thì Tương Lai Hoàn Thành (Future Perfect Tense)',
                'slug' => 'tuong-lai-hoan-thanh',
                'level' => 'B2',
                'summary' => 'Hành động sẽ xảy ra và hoàn thành trước một mốc thời gian hoặc trước một hành động khác trong tương lai.',
                'badge_color' => '#8b5cf6',
                'icon' => 'bi-flag-fill',
                'order_index' => 11,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-primary">
                                <tr><th>Thể</th><th>Công thức Chủ động</th><th>Công thức Bị động</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Tương lai hoàn thành</strong></td>
                                    <td><code>S + will + have + V3/Ved + O</code></td>
                                    <td><code>S + will + have + been + V3/Ved (+ by O)</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>1. Cấu trúc ngữ pháp</h4>
                        <div class="card p-3 rounded-3 bg-light border">
                            <ul class="mb-0 fs-7">
                                <li><strong>Khẳng định (+):</strong> <code>S + will + have + V3 / Ved + O</code></li>
                                <li><strong>Phủ định (-):</strong> <code>S + won\'t + have + V3 / Ved + O</code></li>
                                <li><strong>Nghi vấn (?):</strong> <code>Will + S + have + V3 / Ved + O?</code></li>
                            </ul>
                        </div>
                    </div>

                    <div class="grammar-section mb-3">
                        <h4 class="fw-bold text-teal mb-2"><i class="bi bi-clock-history me-2"></i>2. Dấu hiệu nhận biết & Phối hợp thì</h4>
                        <p class="fs-7">
                            <strong>• By + mốc thời gian trong tương lai:</strong> By July next year, by tomorrow, by the end of this month.<br/>
                            <strong>• Phối hợp thì:</strong> <code>By the time + S + V(hiện tại đơn), S + will have + V3/Ved</code>.<br/>
                            <em>VD: When you come back, I will have written this letter.</em>
                        </p>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'They will have built that house by July next year.', 'vi' => 'Họ sẽ hoàn thành xong ngôi nhà đó trước tháng 7 năm sau.'],
                    ['en' => 'By 3 PM tomorrow, I will have finished my homework.', 'vi' => 'Trước 3 giờ chiều mai, tôi sẽ hoàn thành xong bài tập về nhà.'],
                ],
                'exercises_json' => [
                    'translations' => [
                        ['id' => 1, 'vi' => 'Họ sẽ xây xong ngôi nhà đó trước tháng 7 năm sau.', 'expected' => 'They will have built that house by July next year', 'tense' => 'Tương lai hoàn thành'],
                        ['id' => 2, 'vi' => 'Khi bạn quay lại, tôi sẽ viết xong bức thư này.', 'expected' => 'When you come back, I will have written this letter', 'tense' => 'Tương lai hoàn thành'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'By the end of this year, they ______ this bridge.',
                        'options_json' => ['will build', 'will have built', 'have built', 'built'],
                        'correct_answer' => 'will have built',
                        'explanation' => 'Dấu hiệu "By the end of this year" -> chia Tương lai hoàn thành "will have built".',
                    ],
                ],
            ],

            // 12. CÂU BỊ ĐỘNG 12 THÌ (PASSIVE VOICE)
            [
                'title' => 'Chuyên Đề Câu Bị Động Toàn Tập (Passive Voice across All Tenses)',
                'slug' => 'cau-bi-dong-12-thi',
                'level' => 'B1',
                'summary' => 'Toàn bộ quy tắc chuyển đổi từ Chủ động sang Bị động cho 12 thì và Động từ khuyết thiếu theo chuẩn tài liệu.',
                'badge_color' => '#dc2626',
                'icon' => 'bi-shield-shaded',
                'order_index' => 12,
                'active_passive_html' => '
                    <div class="table-responsive my-3">
                        <table class="table table-bordered table-sm text-center align-middle">
                            <thead class="table-dark">
                                <tr><th>Thì</th><th>Chủ động (Active)</th><th>Bị động (Passive)</th></tr>
                            </thead>
                            <tbody class="fs-8">
                                <tr><td>Hiện tại đơn</td><td>S + V(s/es) + O</td><td>S + am/is/are + V3</td></tr>
                                <tr><td>Hiện tại tiếp diễn</td><td>S + am/is/are + V-ing + O</td><td>S + am/is/are + being + V3</td></tr>
                                <tr><td>Hiện tại hoàn thành</td><td>S + have/has + V3 + O</td><td>S + have/has + been + V3</td></tr>
                                <tr><td>Quá khứ đơn</td><td>S + V2 + O</td><td>S + was/were + V3</td></tr>
                                <tr><td>Quá khứ tiếp diễn</td><td>S + was/were + V-ing + O</td><td>S + was/were + being + V3</td></tr>
                                <tr><td>Quá khứ hoàn thành</td><td>S + had + V3 + O</td><td>S + had + been + V3</td></tr>
                                <tr><td>Tương lai đơn</td><td>S + will + V-infi + O</td><td>S + will + be + V3</td></tr>
                                <tr><td>Tương lai hoàn thành</td><td>S + will + have + V3 + O</td><td>S + will + have + been + V3</td></tr>
                                <tr><td>Tương lai gần</td><td>S + am/is/are going to + V-infi + O</td><td>S + am/is/are going to + be + V3</td></tr>
                                <tr><td>Động từ khuyết thiếu</td><td>S + ĐTKT + V-infi + O</td><td>S + ĐTKT + be + V3</td></tr>
                            </tbody>
                        </table>
                    </div>
                ',
                'pronunciation_notes' => null,
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-bookmarks-fill me-2"></i>Quy tắc 3 bước chuyển sang câu Bị động</h4>
                        <ol class="fs-7">
                            <li>Xác định <strong>Tân ngữ (O)</strong> của câu chủ động để đưa lên làm <strong>Chủ ngữ mới (S)</strong>.</li>
                            <li>Xác định thì của động từ chính và áp dụng công thức <code>BE + V3/Ved</code> tương ứng với thì đó.</li>
                            <li>Đưa <strong>Chủ ngữ cũ (S)</strong> về sau làm tân ngữ: <code>by + O</code> (có thể lược bỏ nếu là by someone, by people, by them...).</li>
                        </ol>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'This flower is watered by my father every morning.', 'vi' => 'Bông hoa này được bố tôi tưới nước mỗi buổi sáng.'],
                    ['en' => 'Fiona was invited to John\'s birthday party last night.', 'vi' => 'Fiona đã được mời đến bữa tiệc sinh nhật của John tối qua.'],
                    ['en' => 'Our teeth should be cleaned twice a day.', 'vi' => 'Răng của chúng ta nên được làm sạch 2 lần mỗi ngày.'],
                ],
                'exercises_json' => [
                    'passive_conversions' => [
                        ['id' => 1, 'active' => 'My father waters this flower every morning.', 'expected' => 'This flower is watered by my father every morning'],
                        ['id' => 2, 'active' => 'John invited Fiona to his birthday party last night.', 'expected' => 'Fiona was invited to his birthday party last night by John'],
                        ['id' => 3, 'active' => 'Her mother is preparing the dinner in the kitchen.', 'expected' => 'The dinner is being prepared in the kitchen by her mother'],
                        ['id' => 4, 'active' => 'We should clean our teeth twice a day.', 'expected' => 'Our teeth should be cleaned twice a day'],
                        ['id' => 5, 'active' => 'Our teachers have explained the English grammar.', 'expected' => 'The English grammar has been explained by our teachers'],
                        ['id' => 6, 'active' => 'Some drunk drivers caused the accident in this city.', 'expected' => 'The accident in this city was caused by some drunk drivers'],
                        ['id' => 7, 'active' => 'Tom will visit Mary next month.', 'expected' => 'Mary will be visited by Tom next month'],
                        ['id' => 8, 'active' => 'The manager didn\'t phone the secretary this morning.', 'expected' => 'The secretary was not phoned by the manager this morning'],
                        ['id' => 9, 'active' => 'The students have discussed the pollution problems since last week.', 'expected' => 'The pollution problems have been discussed by the students since last week'],
                        ['id' => 10, 'active' => 'Some people will interview the new president on TV.', 'expected' => 'The new president will be interviewed on TV'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'My father waters this flower every morning. -> This flower ______ by my father every morning.',
                        'options_json' => ['is watered', 'was watered', 'is being watered', 'watered'],
                        'correct_answer' => 'is watered',
                        'explanation' => 'Thì hiện tại đơn dạng bị động: "is watered".',
                    ],
                ],
            ],

            // 13. QUY TẮC PHÁT ÂM ĐUÔI -ED VÀ -S/ES THỰC HÀNH
            [
                'title' => 'Chuyên Đề Phát Âm Đuôi -ed và -s/es Thực Hành (30 Từ Vựng)',
                'slug' => 'quy-tac-phat-am-ed-s-es',
                'level' => 'A1',
                'summary' => 'Nắm trọn bảng quy tắc phát âm đuôi -ed (/ɪd/, /t/, /d/) và đuôi -s/es (/ɪz/, /s/, /z/) cùng 30 từ thực hành chuẩn.',
                'badge_color' => '#eab308',
                'icon' => 'bi-volume-up-fill',
                'order_index' => 13,
                'active_passive_html' => null,
                'pronunciation_notes' => '
                    <div class="row g-3 fs-7 mb-4">
                        <div class="col-md-6">
                            <div class="card p-3 rounded-3 bg-light border h-100">
                                <h6 class="fw-bold text-success mb-2">Quy tắc đuôi -ed:</h6>
                                <ul>
                                    <li><strong>/ɪd/:</strong> sau âm <code>/t, d/</code> (end, need, wait, plant, want).</li>
                                    <li><strong>/t/:</strong> sau các âm vô thanh <code>/p, k, s, f, tʃ, ʃ/</code> (watch, cook, wash, rush, help).</li>
                                    <li><strong>/d/:</strong> các trường hợp còn lại (stay, call, live, share, plan).</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card p-3 rounded-3 bg-light border h-100">
                                <h6 class="fw-bold text-primary mb-2">Quy tắc đuôi -s/es:</h6>
                                <ul>
                                    <li><strong>/ɪz/:</strong> sau các âm <code>s, z, tʃ, dʒ, ʃ, ʒ</code> (watch, wash, rush, close, cross).</li>
                                    <li><strong>/s/:</strong> sau các âm vô thanh <code>/p, k, t, f, θ/</code> (count, wait, cook, help, plant).</li>
                                    <li><strong>/z/:</strong> các trường hợp còn lại (live, share, stay, need, call, seem).</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                ',
                'theory_html' => '
                    <div class="grammar-section mb-4">
                        <h4 class="fw-bold text-teal mb-3"><i class="bi bi-mic-fill me-2"></i>Thực hành 30 từ phát âm đuôi chuẩn Oxford</h4>
                        <p class="fs-7 text-muted">Bảng tra cứu và thực hành nhận diện cách đọc đuôi -ed và -s/es có trong trang 38 tài liệu.</p>
                    </div>
                ',
                'examples_json' => [
                    ['en' => 'He ended the call.', 'vi' => 'Anh ấy đã kết thúc cuộc gọi. (ended -> /ɪd/)'],
                    ['en' => 'She watched the movie.', 'vi' => 'Cô ấy đã xem bộ phim. (watched -> /t/)'],
                    ['en' => 'They stayed at home.', 'vi' => 'Họ đã ở nhà. (stayed -> /d/)'],
                ],
                'exercises_json' => [
                    'practice_30_words' => [
                        ['num' => 1, 'word' => 'end', 'ipa' => '/end/', 'ed' => '/ɪd/', 's' => '/z/'],
                        ['num' => 2, 'word' => 'watch', 'ipa' => '/wɒtʃ/', 'ed' => '/t/', 's' => '/ɪz/'],
                        ['num' => 3, 'word' => 'count', 'ipa' => '/kaʊnt/', 'ed' => '/ɪd/', 's' => '/s/'],
                        ['num' => 4, 'word' => 'stay', 'ipa' => '/steɪ/', 'ed' => '/d/', 's' => '/z/'],
                        ['num' => 5, 'word' => 'need', 'ipa' => '/niːd/', 'ed' => '/ɪd/', 's' => '/z/'],
                        ['num' => 6, 'word' => 'call', 'ipa' => '/kɔːl/', 'ed' => '/d/', 's' => '/z/'],
                        ['num' => 7, 'word' => 'seem', 'ipa' => '/siːm/', 'ed' => '/d/', 's' => '/z/'],
                        ['num' => 8, 'word' => 'plan', 'ipa' => '/plæn/', 'ed' => '/d/', 's' => '/z/'],
                        ['num' => 9, 'word' => 'wait', 'ipa' => '/weɪt/', 'ed' => '/ɪd/', 's' => '/s/'],
                        ['num' => 10, 'word' => 'wash', 'ipa' => '/wɒʃ/', 'ed' => '/t/', 's' => '/ɪz/'],
                        ['num' => 11, 'word' => 'want', 'ipa' => '/wɒnt/', 'ed' => '/ɪd/', 's' => '/s/'],
                        ['num' => 12, 'word' => 'walk', 'ipa' => '/wɔːk/', 'ed' => '/t/', 's' => '/s/'],
                        ['num' => 13, 'word' => 'push', 'ipa' => '/pʊʃ/', 'ed' => '/t/', 's' => '/ɪz/'],
                        ['num' => 14, 'word' => 'spell', 'ipa' => '/spel/', 'ed' => '/d/', 's' => '/z/'],
                        ['num' => 15, 'word' => 'plant', 'ipa' => '/plænt/', 'ed' => '/ɪd/', 's' => '/s/'],
                        ['num' => 16, 'word' => 'rush', 'ipa' => '/rʌʃ/', 'ed' => '/t/', 's' => '/ɪz/'],
                        ['num' => 17, 'word' => 'part', 'ipa' => '/pɑːt/', 'ed' => '/ɪd/', 's' => '/s/'],
                        ['num' => 18, 'word' => 'cook', 'ipa' => '/kʊk/', 'ed' => '/t/', 's' => '/s/'],
                        ['num' => 19, 'word' => 'rent', 'ipa' => '/rent/', 'ed' => '/ɪd/', 's' => '/s/'],
                        ['num' => 20, 'word' => 'live', 'ipa' => '/lɪv/', 'ed' => '/d/', 's' => '/z/'],
                    ],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'Từ "watched" có phần đuôi "-ed" được phát âm là gì?',
                        'options_json' => ['/ɪd/', '/t/', '/d/', '/ed/'],
                        'correct_answer' => '/t/',
                        'explanation' => 'Âm cuối của "watch" là /tʃ/ (vô thanh), do đó thêm đuôi "-ed" đọc là /t/.',
                    ],
                ],
            ],
        ];

        foreach ($lessons as $lessonData) {
            $questions = $lessonData['questions'] ?? [];
            unset($lessonData['questions']);

            $lessonData['topic_id'] = $topicId;
            $grammar = Grammar::create($lessonData);

            foreach ($questions as $qIdx => $qData) {
                GrammarQuestion::create([
                    'grammar_id' => $grammar->id,
                    'type' => $qData['type'],
                    'question' => $qData['question'],
                    'options_json' => $qData['options_json'],
                    'correct_answer' => $qData['correct_answer'],
                    'explanation' => $qData['explanation'] ?? '',
                    'order_index' => $qIdx + 1,
                ]);
            }
        }
    }
}
