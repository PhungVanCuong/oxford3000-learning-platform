<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\Grammar;
use App\Models\GrammarQuestion;
use App\Models\User;
use App\Models\UserGrammarProgress;

class GrammarSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing grammar records
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        GrammarQuestion::truncate();
        UserGrammarProgress::truncate();
        Grammar::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $topics = Topic::all();
        $academicTopic = Topic::where('slug', 'academic-and-education')->first() ?? $topics->first();

        $lessons = [
            // 1. Hiện tại đơn
            [
                'title' => '1. Thì Hiện Tại Đơn (Present Simple Tense)',
                'level' => 'A1',
                'order_index' => 1,
                'theory_html' => '
                    <h4>1. Công thức (Formulas)</h4>
                    <p><strong>Khẳng định (+):</strong> <code>S + V(s/es) + O</code> (Với To Be: <code>S + am/is/are + O/Adj</code>)</p>
                    <p><strong>Phủ định (-):</strong> <code>S + do/does + not + V_inf</code> (Với To Be: <code>S + am/is/are + not</code>)</p>
                    <p><strong>Nghi vấn (?):</strong> <code>Do/Does + S + V_inf?</code> (Với To Be: <code>Am/Is/Are + S + O/Adj?</code>)</p>
                    <hr/>
                    <h4>2. Cách dùng chính (Usage)</h4>
                    <ul>
                        <li>Chân lý, sự thật hiển nhiên (The sun rises in the east).</li>
                        <li>Thói quen, hành động lặp đi lặp lại (I wake up at 6 AM every day).</li>
                        <li>Lịch trình tàu xe, máy bay cố định (The train departs at 7 PM).</li>
                    </ul>
                    <hr/>
                    <h4>3. Dấu hiệu nhận biết</h4>
                    <p>Always, usually, often, sometimes, rarely, never, every day/week/month, once a week.</p>
                ',
                'examples_json' => [
                    ['en' => 'Water boils at 100 degrees Celsius.', 'vi' => 'Nước sôi ở nhiệt độ 100 độ C.'],
                    ['en' => 'She drinks fresh orange juice every morning.', 'vi' => 'Cô ấy uống nước cam tươi mỗi buổi sáng.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'My sister ______ to the gym three times a week.',
                        'options_json' => ['go', 'goes', 'going', 'is go'],
                        'correct_answer' => 'goes',
                        'explanation' => 'Chủ ngữ ngôi thứ 3 số ít "My sister" chia động từ "go" thêm "-es" thành "goes".',
                    ],
                    [
                        'type' => 'fill_in_blank',
                        'question' => 'She usually ___ (wake) up early in the morning.',
                        'options_json' => null,
                        'correct_answer' => 'wakes',
                        'explanation' => 'Dấu hiệu "usually" + chủ ngữ "She" -> chia "wakes".',
                    ],
                ],
            ],

            // 2. Hiện tại tiếp diễn
            [
                'title' => '2. Thì Hiện Tại Tiếp Diễn (Present Continuous Tense)',
                'level' => 'A1',
                'order_index' => 2,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><strong>(+)</strong> <code>S + am/is/are + V-ing + O</code></p>
                    <p><strong>(-)</strong> <code>S + am/is/are + not + V-ing</code></p>
                    <p><strong>(?)</strong> <code>Am/Is/Are + S + V-ing?</code></p>
                    <hr/>
                    <h4>2. Cách dùng & Dấu hiệu</h4>
                    <p>Hành động đang diễn ra ngay lúc nói, hoặc phàn nàn thói quen xấu với "always".</p>
                    <p>Dấu hiệu: Now, right now, at the moment, at present, Look!, Listen!, Be quiet!.</p>
                ',
                'examples_json' => [
                    ['en' => 'Listen! The baby is singing in the room.', 'vi' => 'Lắng nghe này! Đứa bé đang hát trong phòng.'],
                    ['en' => 'We are preparing for our final exam right now.', 'vi' => 'Chúng tôi đang chuẩn bị cho bài thi cuối kỳ ngay lúc này.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'Listen! The teacher ______ the new grammar lesson.',
                        'options_json' => ['explains', 'is explaining', 'explained', 'has explained'],
                        'correct_answer' => 'is explaining',
                        'explanation' => 'Dấu hiệu cảm thán "Listen!" -> chia hiện tại tiếp diễn: "is explaining".',
                    ],
                    [
                        'type' => 'fill_in_blank',
                        'question' => 'What are you ___ (do) right now?',
                        'options_json' => null,
                        'correct_answer' => 'doing',
                        'explanation' => 'Cấu trúc tiếp diễn: are you + V-ing -> "doing".',
                    ],
                ],
            ],

            // 3. Hiện tại hoàn thành
            [
                'title' => '3. Thì Hiện Tại Hoàn Thành (Present Perfect Tense)',
                'level' => 'A2',
                'order_index' => 3,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><code>S + have / has + V3 / V-ed + O</code></p>
                    <p>Phủ định: <code>S + have/has + not + V3/ed</code> | Nghi vấn: <code>Have/Has + S + V3/ed?</code></p>
                    <hr/>
                    <h4>2. Cách dùng & Dấu hiệu</h4>
                    <p>Hành động bắt đầu trong quá khứ kéo dài đến hiện tại; trải nghiệm kinh nghiệm bản thân.</p>
                    <p>Dấu hiệu: Already, yet, just, ever, never, since + mốc thời gian, for + khoảng thời gian, recently, so far.</p>
                ',
                'examples_json' => [
                    ['en' => 'I have lived in London for five years.', 'vi' => 'Tôi đã sống ở London được 5 năm rồi.'],
                    ['en' => 'She has just finished her research project.', 'vi' => 'Cô ấy vừa mới hoàn thành dự án nghiên cứu của mình.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'They ______ in this company since 2018.',
                        'options_json' => ['worked', 'have worked', 'are working', 'works'],
                        'correct_answer' => 'have worked',
                        'explanation' => 'Dấu hiệu "since 2018" diễn tả hành động từ quá khứ đến nay -> "have worked".',
                    ],
                    [
                        'type' => 'fill_in_blank',
                        'question' => 'Have you ever ___ (see) an elephant before?',
                        'options_json' => null,
                        'correct_answer' => 'seen',
                        'explanation' => 'Phân từ 2 (V3) của "see" là "seen".',
                    ],
                ],
            ],

            // 4. Hiện tại hoàn thành tiếp diễn
            [
                'title' => '4. Thì Hiện Tại Hoàn Thành Tiếp Diễn (Present Perfect Continuous)',
                'level' => 'B1',
                'order_index' => 4,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><code>S + have / has + been + V-ing + O</code></p>
                    <hr/>
                    <h4>2. Cách dùng</h4>
                    <p>Nhấn mạnh tính liên tục, kéo dài không ngừng nghỉ của hành động từ quá khứ đến hiện tại và có thể tiếp diễn trong tương lai.</p>
                    <p>Dấu hiệu: All day, all morning, how long, for the past 3 hours.</p>
                ',
                'examples_json' => [
                    ['en' => 'It has been raining all morning.', 'vi' => 'Trời đã mưa liên tục suốt cả buổi sáng nay.'],
                    ['en' => 'I have been waiting for the bus for 40 minutes.', 'vi' => 'Tôi đã đợi xe buýt suốt 40 phút rồi.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'Her eyes are red because she ______ crying for an hour.',
                        'options_json' => ['has been', 'was', 'is', 'had been'],
                        'correct_answer' => 'has been',
                        'explanation' => 'Hành động khóc liên tục để lại dấu vết ở hiện tại -> Present Perfect Continuous: "has been crying".',
                    ],
                ],
            ],

            // 5. Quá khứ đơn
            [
                'title' => '5. Thì Quá Khứ Đơn (Past Simple Tense)',
                'level' => 'A2',
                'order_index' => 5,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><strong>(+)</strong> <code>S + V2 / V-ed + O</code> (To Be: was / were)</p>
                    <p><strong>(-)</strong> <code>S + did not + V_inf</code> (To Be: was/were not)</p>
                    <p><strong>(?)</strong> <code>Did + S + V_inf?</code> (To Be: Was/Were + S?)</p>
                    <hr/>
                    <h4>2. Dấu hiệu nhận biết</h4>
                    <p>Yesterday, last night/week/month/year, ago, in 2010, when I was a child.</p>
                ',
                'examples_json' => [
                    ['en' => 'We visited Da Nang city last summer.', 'vi' => 'Mùa hè năm ngoái chúng tôi đã đến thăm thành phố Đà Nẵng.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'Yesterday, he ______ a new smartphone.',
                        'options_json' => ['buys', 'bought', 'buying', 'has bought'],
                        'correct_answer' => 'bought',
                        'explanation' => 'Dấu hiệu "Yesterday", quá khứ của "buy" là "bought".',
                    ],
                ],
            ],

            // 6. Quá khứ tiếp diễn
            [
                'title' => '6. Thì Quá Khứ Tiếp Diễn (Past Continuous Tense)',
                'level' => 'A2',
                'order_index' => 6,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><code>S + was / were + V-ing + O</code></p>
                    <hr/>
                    <h4>2. Cách dùng & Dấu hiệu</h4>
                    <p>Hành động đang diễn ra tại một thời điểm xác định trong quá khứ, hoặc một hành động đang diễn ra thì hành động khác chen vào (When/While).</p>
                    <p>Dấu hiệu: At 8 PM yesterday, at this time last year, while, when.</p>
                ',
                'examples_json' => [
                    ['en' => 'At 8 PM yesterday, I was watching a documentary.', 'vi' => 'Lúc 8 giờ tối qua, tôi đang xem một bộ phim tài liệu.'],
                    ['en' => 'While we were having dinner, the telephone rang.', 'vi' => 'Trong khi chúng tôi đang ăn tối thì chuông điện thoại reo.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'When the phone rang, she ______ a warm shower.',
                        'options_json' => ['took', 'was taking', 'is taking', 'has taken'],
                        'correct_answer' => 'was taking',
                        'explanation' => 'Hành động đang diễn ra trong quá khứ ("was taking shower") bị hành động khác chen ngang ("rang").',
                    ],
                ],
            ],

            // 7. Quá khứ hoàn thành
            [
                'title' => '7. Thì Quá Khứ Hoàn Thành (Past Perfect Tense)',
                'level' => 'B1',
                'order_index' => 7,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><code>S + had + V3 / V-ed + O</code></p>
                    <hr/>
                    <h4>2. Cách dùng & Dấu hiệu</h4>
                    <p>Diễn tả hành động xảy ra và hoàn tất trước một hành động khác trong quá khứ.</p>
                    <p>Dấu hiệu: Before, after, by the time, as soon as.</p>
                ',
                'examples_json' => [
                    ['en' => 'When I arrived at the station, the train had already left.', 'vi' => 'Khi tôi đến ga, con tàu đã rời đi rồi.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'By the time we reached the cinema, the movie ______.',
                        'options_json' => ['started', 'had started', 'has started', 'was starting'],
                        'correct_answer' => 'had started',
                        'explanation' => 'Bộ phim đã chiếu trước khi chúng tôi đến rạp -> Past Perfect: "had started".',
                    ],
                ],
            ],

            // 8. Quá khứ hoàn thành tiếp diễn
            [
                'title' => '8. Thì Quá Khứ Hoàn Thành Tiếp Diễn (Past Perfect Continuous)',
                'level' => 'B2',
                'order_index' => 8,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><code>S + had + been + V-ing + O</code></p>
                    <hr/>
                    <h4>2. Cách dùng</h4>
                    <p>Diễn tả hành động đã diễn ra liên tục kéo dài trước một hành động khác trong quá khứ, nhấn mạnh khoảng thời gian kéo dài.</p>
                ',
                'examples_json' => [
                    ['en' => 'They had been driving for 5 hours before they stopped for lunch.', 'vi' => 'Họ đã lái xe liên tục suốt 5 tiếng trước khi dừng lại ăn trưa.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'He was exhausted because he ______ working non-stop all night.',
                        'options_json' => ['had been', 'has been', 'was', 'is'],
                        'correct_answer' => 'had been',
                        'explanation' => 'Hành động làm việc liên tục suốt đêm trước thời điểm kiệt sức trong quá khứ -> "had been working".',
                    ],
                ],
            ],

            // 9. Tương lai đơn
            [
                'title' => '9. Thì Tương Lai Đơn (Future Simple Tense)',
                'level' => 'A1',
                'order_index' => 9,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><code>S + will + V_inf + O</code></p>
                    <p>Phủ định: <code>S + will not (won\'t) + V_inf</code> | Nghi vấn: <code>Will + S + V_inf?</code></p>
                    <hr/>
                    <h4>2. Cách dùng & Dấu hiệu</h4>
                    <p>Quyết định đưa ra ngay tại thời điểm nói, lời hứa, dự đoán không có căn cứ rõ ràng.</p>
                    <p>Dấu hiệu: Tomorrow, next week/month, in the future, I think, I promise.</p>
                ',
                'examples_json' => [
                    ['en' => 'I promise I will help you with your homework.', 'vi' => 'Tôi hứa tôi sẽ giúp bạn làm bài tập về nhà.'],
                    ['en' => 'I am thirsty. I will buy a bottle of water.', 'vi' => 'Tôi khát nước quá. Tôi sẽ đi mua một chai nước.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'Don\'t worry! I ______ keep your secret safe.',
                        'options_json' => ['will', 'am', 'did', 'was'],
                        'correct_answer' => 'will',
                        'explanation' => 'Lời hứa tương lai: "will keep".',
                    ],
                ],
            ],

            // 10. Tương lai tiếp diễn
            [
                'title' => '10. Thì Tương Lai Tiếp Diễn (Future Continuous Tense)',
                'level' => 'B1',
                'order_index' => 10,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><code>S + will + be + V-ing + O</code></p>
                    <hr/>
                    <h4>2. Cách dùng</h4>
                    <p>Diễn tả hành động sẽ đang diễn ra tại một thời điểm cụ thể trong tương lai.</p>
                    <p>Dấu hiệu: At this time tomorrow, at 9 AM next Monday.</p>
                ',
                'examples_json' => [
                    ['en' => 'At this time tomorrow, I will be flying to Tokyo.', 'vi' => 'Vào giờ này ngày mai, tôi sẽ đang trên chuyến bay đến Tokyo.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'This time next week, we ______ on the beach in Hawaii.',
                        'options_json' => ['will relax', 'will be relaxing', 'are relax', 'relaxed'],
                        'correct_answer' => 'will be relaxing',
                        'explanation' => 'Dấu hiệu "This time next week" -> hành động đang diễn ra tại thời điểm cụ thể trong tương lai.',
                    ],
                ],
            ],

            // 11. Tương lai hoàn thành
            [
                'title' => '11. Thì Tương Lai Hoàn Thành (Future Perfect Tense)',
                'level' => 'B2',
                'order_index' => 11,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><code>S + will + have + V3 / V-ed + O</code></p>
                    <hr/>
                    <h4>2. Cách dùng</h4>
                    <p>Diễn tả hành động sẽ hoàn thành trước một thời điểm hoặc một hành động khác trong tương lai.</p>
                    <p>Dấu hiệu: By the end of this month, by 2030, by the time S + V(hiện tại đơn).</p>
                ',
                'examples_json' => [
                    ['en' => 'By next year, she will have graduated from Harvard.', 'vi' => 'Trước năm sau, cô ấy sẽ tốt nghiệp Đại học Harvard.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'By the end of this month, I ______ 1000 English words.',
                        'options_json' => ['will learn', 'will have learned', 'learned', 'have learned'],
                        'correct_answer' => 'will have learned',
                        'explanation' => 'Dấu hiệu "By the end of this month" -> Future Perfect: "will have learned".',
                    ],
                ],
            ],

            // 12. Tương lai hoàn thành tiếp diễn
            [
                'title' => '12. Thì Tương Lai Hoàn Thành Tiếp Diễn (Future Perfect Continuous)',
                'level' => 'B2',
                'order_index' => 12,
                'theory_html' => '
                    <h4>1. Công thức</h4>
                    <p><code>S + will + have + been + V-ing + O</code></p>
                    <hr/>
                    <h4>2. Cách dùng</h4>
                    <p>Nhấn mạnh khoảng thời gian kéo dài của một hành động tính đến một mốc cụ thể trong tương lai.</p>
                ',
                'examples_json' => [
                    ['en' => 'By 2030, Mr. David will have been teaching here for 25 years.', 'vi' => 'Tính đến năm 2030, thầy David sẽ đã giảng dạy ở đây được 25 năm liên tục.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'By December, we ______ living in this apartment for ten years.',
                        'options_json' => ['will have been', 'will be', 'have been', 'are'],
                        'correct_answer' => 'will have been',
                        'explanation' => 'Nhấn mạnh tính kéo dài liên tục đến thời điểm tương lai -> Future Perfect Continuous: "will have been living".',
                    ],
                ],
            ],

            // 13. Câu Bị Động (Passive Voice)
            [
                'title' => '13. Câu Bị Động Toàn Diện (Passive Voice)',
                'level' => 'B1',
                'order_index' => 13,
                'theory_html' => '
                    <h4>1. Cấu trúc tổng quát</h4>
                    <p><code>S + Be (chia theo thì) + V3 / V-ed + (by O)</code></p>
                    <ul>
                        <li>Hiện tại đơn: <code>am/is/are + V3/ed</code></li>
                        <li>Quá khứ đơn: <code>was/were + V3/ed</code></li>
                        <li>Hiện tại hoàn thành: <code>have/has been + V3/ed</code></li>
                        <li>Động từ khuyết thiếu: <code>modal verb + be + V3/ed</code></li>
                    </ul>
                ',
                'examples_json' => [
                    ['en' => 'This bridge was built in 1995.', 'vi' => 'Cây cầu này được xây dựng vào năm 1995.'],
                    ['en' => 'English is spoken all over the world.', 'vi' => 'Tiếng Anh được nói trên toàn thế giới.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'The telephone ______ by Alexander Graham Bell.',
                        'options_json' => ['invented', 'was invented', 'is invented', 'has invented'],
                        'correct_answer' => 'was invented',
                        'explanation' => 'Hành động xảy ra trong quá khứ ở thể bị động: "was invented".',
                    ],
                ],
            ],

            // 14. Câu Điều Kiện (Conditionals)
            [
                'title' => '14. Câu Điều Kiện Loại 1, 2, 3 (Conditionals Type 1, 2, 3)',
                'level' => 'B1',
                'order_index' => 14,
                'theory_html' => '
                    <h4>1. Loại 1: Có thật ở hiện tại/tương lai</h4>
                    <p><code>If + S + V(hiện tại đơn), S + will / can + V_inf</code></p>
                    <h4>2. Loại 2: Giả định trái ngược hiện tại</h4>
                    <p><code>If + S + V2/ed (were), S + would / could + V_inf</code></p>
                    <h4>3. Loại 3: Giả định trái ngược quá khứ</h4>
                    <p><code>If + S + had + V3/ed, S + would + have + V3/ed</code></p>
                ',
                'examples_json' => [
                    ['en' => 'If it rains, we will cancel the picnic.', 'vi' => 'Nếu trời mưa, chúng tôi sẽ hủy chuyến dã ngoại.'],
                    ['en' => 'If I were you, I would take that opportunity.', 'vi' => 'Nếu tôi là bạn, tôi sẽ nắm bắt cơ hội đó.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'If I ______ a bird, I would fly everywhere.',
                        'options_json' => ['am', 'was', 'were', 'had been'],
                        'correct_answer' => 'were',
                        'explanation' => 'Câu điều kiện loại 2 giả định trái hiện tại dùng "were" cho mọi ngôi.',
                    ],
                ],
            ],

            // 15. Câu Gián Tiếp (Reported Speech)
            [
                'title' => '15. Câu Tường Thuật / Gián Tiếp (Reported Speech)',
                'level' => 'B2',
                'order_index' => 15,
                'theory_html' => '
                    <h4>1. Quy tắc lùi thì (Backshift of tenses)</h4>
                    <p>Hiện tại đơn -> Quá khứ đơn; Hiện tại tiếp diễn -> Quá khứ tiếp diễn; Hiện tại hoàn thành -> Quá khứ hoàn thành; Will -> Would; Can -> Could.</p>
                ',
                'examples_json' => [
                    ['en' => 'She said: "I am happy" -> She said that she was happy.', 'vi' => 'Cô ấy nói: "Tôi hạnh phúc" -> Cô ấy nói rằng cô ấy hạnh phúc.'],
                ],
                'questions' => [
                    [
                        'type' => 'multiple_choice',
                        'question' => 'He said that he ______ to London the following month.',
                        'options_json' => ['will go', 'would go', 'went', 'goes'],
                        'correct_answer' => 'would go',
                        'explanation' => 'Lùi thì trong câu tường thuật: "will" chuyển thành "would".',
                    ],
                ],
            ],
        ];

        foreach ($lessons as $lessonData) {
            $questions = $lessonData['questions'];
            unset($lessonData['questions']);

            $lessonData['topic_id'] = $academicTopic->id;
            $g = Grammar::create($lessonData);

            foreach ($questions as $qIdx => $q) {
                $q['grammar_id'] = $g->id;
                $q['order_index'] = $qIdx + 1;
                GrammarQuestion::create($q);
            }
        }

        // Pre-seed completed grammar for Cuong Phung
        $cuong = User::where('email', 'cuongphung1810@gmail.com')->first();
        if ($cuong) {
            $g1 = Grammar::first();
            if ($g1) {
                UserGrammarProgress::create([
                    'user_id' => $cuong->id,
                    'grammar_id' => $g1->id,
                    'is_completed' => true,
                    'best_score' => 100,
                ]);
            }
        }
    }
}
