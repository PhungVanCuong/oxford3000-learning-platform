<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IpaSound;

class IpaSeeder extends Seeder
{
    public function run(): void
    {
        $sounds = [
            // --- 1. NGUYÊN ÂM ĐƠN (12 MONOPHTHONGS) ---
            [
                'symbol' => '/i:/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Miệng mở rộng sang hai bên như đang mỉm cười nhẹ. Đầu lưỡi nâng lên cao chạm nhẹ vào nướu răng dưới, phát âm âm "i" kéo dài khoảng 1.5 giây.',
                'examples_json' => [
                    ['word' => 'see', 'phonetic' => '/si:/', 'meaning' => 'nhìn thấy', 'example' => 'I see a bird.'],
                    ['word' => 'meat', 'phonetic' => '/mi:t/', 'meaning' => 'thịt', 'example' => 'He loves eating meat.'],
                    ['word' => 'team', 'phonetic' => '/ti:m/', 'meaning' => 'đội nhóm', 'example' => 'We are a great team.'],
                ],
            ],
            [
                'symbol' => '/ɪ/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Miệng mở tự nhiên, khoảng cách hai môi hẹp hơn /i:/. Lưỡi hơi nâng lên, phát âm âm "i" thật ngắn, dứt khoát và dồn lực từ cơ bụng.',
                'examples_json' => [
                    ['word' => 'sit', 'phonetic' => '/sɪt/', 'meaning' => 'ngồi', 'example' => 'Please sit down here.'],
                    ['word' => 'hit', 'phonetic' => '/hɪt/', 'meaning' => 'đánh, va chạm', 'example' => 'He hit the baseball.'],
                    ['word' => 'fish', 'phonetic' => '/fɪʃ/', 'meaning' => 'con cá', 'example' => 'The fish swims fast.'],
                ],
            ],
            [
                'symbol' => '/e/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Mở miệng tự nhiên rộng hơn âm /ɪ/. Lưỡi đặt thấp hơn, phát âm âm "e" ngắn gọn, rõ ràng.',
                'examples_json' => [
                    ['word' => 'bed', 'phonetic' => '/bed/', 'meaning' => 'cái giường', 'example' => 'Time to go to bed.'],
                    ['word' => 'pen', 'phonetic' => '/pen/', 'meaning' => 'cây bút', 'example' => 'I write with a blue pen.'],
                    ['word' => 'send', 'phonetic' => '/send/', 'meaning' => 'gửi đi', 'example' => 'Please send the email.'],
                ],
            ],
            [
                'symbol' => '/æ/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Mở miệng thật rộng, khóe miệng kéo căng sang hai bên, hạ hàm dưới sâu. Đầu lưỡi chạm vào răng cửa dưới, phát âm âm "a bẹt" lai giữa a và e.',
                'examples_json' => [
                    ['word' => 'cat', 'phonetic' => '/kæt/', 'meaning' => 'con mèo', 'example' => 'The cat is sleeping.'],
                    ['word' => 'apple', 'phonetic' => '/ˈæpl/', 'meaning' => 'quả táo', 'example' => 'She eats a red apple.'],
                    ['word' => 'bad', 'phonetic' => '/bæd/', 'meaning' => 'xấu, tồi tệ', 'example' => 'This is not a bad idea.'],
                ],
            ],
            [
                'symbol' => '/ʌ/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Miệng mở vừa phải, lưỡi hơi nâng lên ở phần giữa. Phát âm âm "ă" ngắn, dứt khoát.',
                'examples_json' => [
                    ['word' => 'cup', 'phonetic' => '/kʌp/', 'meaning' => 'cái tách, cúp', 'example' => 'A hot cup of tea.'],
                    ['word' => 'bus', 'phonetic' => '/bʌs/', 'meaning' => 'xe buýt', 'example' => 'I catch the morning bus.'],
                    ['word' => 'love', 'phonetic' => '/lʌv/', 'meaning' => 'yêu thương', 'example' => 'They love each other.'],
                ],
            ],
            [
                'symbol' => '/ɑ:/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Hạ hàm mở miệng rộng hình bầu dục dọc, kéo lưỡi về phía sau cổ họng, phát âm âm "a" sâu và ngân dài.',
                'examples_json' => [
                    ['word' => 'car', 'phonetic' => '/kɑ:r/', 'meaning' => 'ô tô', 'example' => 'He drives a fast car.'],
                    ['word' => 'heart', 'phonetic' => '/hɑ:rt/', 'meaning' => 'trái tim', 'example' => 'Listen to your heart.'],
                    ['word' => 'star', 'phonetic' => '/stɑ:r/', 'meaning' => 'ngôi sao', 'example' => 'Look at the bright star.'],
                ],
            ],
            [
                'symbol' => '/ɒ/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Môi hơi tròn hướng về trước, hạ hàm dưới, lưỡi đặt thấp trong khoang miệng. Phát âm âm "o" ngắn gọn.',
                'examples_json' => [
                    ['word' => 'hot', 'phonetic' => '/hɒt/', 'meaning' => 'nóng', 'example' => 'Today is very hot.'],
                    ['word' => 'box', 'phonetic' => '/bɒks/', 'meaning' => 'cái hộp', 'example' => 'Open the gift box.'],
                    ['word' => 'stop', 'phonetic' => '/stɒp/', 'meaning' => 'dừng lại', 'example' => 'Never stop learning.'],
                ],
            ],
            [
                'symbol' => '/ɔ:/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Chu tròn môi, nâng cuống lưỡi lên gần ngạc mềm, phát âm âm "o" sâu từ cổ họng và ngân dài.',
                'examples_json' => [
                    ['word' => 'door', 'phonetic' => '/dɔ:r/', 'meaning' => 'cánh cửa', 'example' => 'Please close the door.'],
                    ['word' => 'talk', 'phonetic' => '/tɔ:k/', 'meaning' => 'nói chuyện', 'example' => 'Let us talk together.'],
                    ['word' => 'four', 'phonetic' => '/fɔ:r/', 'meaning' => 'số bốn', 'example' => 'I have four pencils.'],
                ],
            ],
            [
                'symbol' => '/ʊ/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Môi hơi đưa nhẹ về phía trước nhưng không chu tròn quá chặt. Phát âm âm "u" thật ngắn và nhẹ.',
                'examples_json' => [
                    ['word' => 'book', 'phonetic' => '/bʊk/', 'meaning' => 'cuốn sách', 'example' => 'Read this good book.'],
                    ['word' => 'good', 'phonetic' => '/ɡʊd/', 'meaning' => 'tốt, giỏi', 'example' => 'Have a good morning.'],
                    ['word' => 'foot', 'phonetic' => '/fʊt/', 'meaning' => 'bàn chân', 'example' => 'My left foot hurts.'],
                ],
            ],
            [
                'symbol' => '/u:/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Môi chu tròn chặt về phía trước giống như huýt sáo, cuống lưỡi nâng cao, phát âm âm "u" ngân dài.',
                'examples_json' => [
                    ['word' => 'blue', 'phonetic' => '/blu:/', 'meaning' => 'màu xanh lam', 'example' => 'The sky is clear blue.'],
                    ['word' => 'food', 'phonetic' => '/fu:d/', 'meaning' => 'thức ăn', 'example' => 'Vietnamese food is delicious.'],
                    ['word' => 'moon', 'phonetic' => '/mu:n/', 'meaning' => 'mặt trăng', 'example' => 'The moon is full tonight.'],
                ],
            ],
            [
                'symbol' => '/ɜ:/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Môi mở tự nhiên thả lỏng, lưỡi hơi nâng nhẹ ở phần giữa, phát âm âm "ơ" sâu và ngân dài.',
                'examples_json' => [
                    ['word' => 'bird', 'phonetic' => '/bɜ:rd/', 'meaning' => 'con chim', 'example' => 'The early bird catches the worm.'],
                    ['word' => 'learn', 'phonetic' => '/lɜ:rn/', 'meaning' => 'học tập', 'example' => 'We learn English every day.'],
                    ['word' => 'work', 'phonetic' => '/wɜ:rk/', 'meaning' => 'làm việc', 'example' => 'Hard work pays off.'],
                ],
            ],
            [
                'symbol' => '/ə/',
                'category' => 'monophthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Âm Schwa phổ biến nhất trong tiếng Anh. Thả lỏng toàn bộ cơ miệng và hàm, phát âm âm "ơ" cực kỳ nhẹ và lướt qua.',
                'examples_json' => [
                    ['word' => 'about', 'phonetic' => '/əˈbaʊt/', 'meaning' => 'về, khoảng', 'example' => 'Tell me about your job.'],
                    ['word' => 'banana', 'phonetic' => '/bəˈnænə/', 'meaning' => 'quả chuối', 'example' => 'Monkeys love bananas.'],
                    ['word' => 'teacher', 'phonetic' => '/ˈtiːtʃər/', 'meaning' => 'giáo viên', 'example' => 'She is an inspiring teacher.'],
                ],
            ],

            // --- 2. NGUYÊN ÂM ĐÔI (8 DIPHTHONGS) ---
            [
                'symbol' => '/eɪ/',
                'category' => 'diphthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Bắt đầu từ âm /e/ mở tự nhiên rồi lướt nhẹ môi thu hẹp chuyển dần sang âm /ɪ/.',
                'examples_json' => [
                    ['word' => 'face', 'phonetic' => '/feɪs/', 'meaning' => 'khuôn mặt', 'example' => 'A smiling face.'],
                    ['word' => 'day', 'phonetic' => '/deɪ/', 'meaning' => 'ngày', 'example' => 'Have a wonderful day.'],
                    ['word' => 'rain', 'phonetic' => '/reɪn/', 'meaning' => 'cơn mưa', 'example' => 'It starts to rain.'],
                ],
            ],
            [
                'symbol' => '/aɪ/',
                'category' => 'diphthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Mở miệng rộng phát âm /ɑ:/ rồi dần dần nâng hàm khép môi lướt sang âm /ɪ/.',
                'examples_json' => [
                    ['word' => 'time', 'phonetic' => '/taɪm/', 'meaning' => 'thời gian', 'example' => 'Time is precious.'],
                    ['word' => 'fly', 'phonetic' => '/flaɪ/', 'meaning' => 'bay', 'example' => 'Eagles fly high.'],
                    ['word' => 'night', 'phonetic' => '/naɪt/', 'meaning' => 'ban đêm', 'example' => 'Good night and sleep well.'],
                ],
            ],
            [
                'symbol' => '/ɔɪ/',
                'category' => 'diphthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Bắt đầu từ âm /ɔ:/ chu môi tròn rồi di chuyển lướt môi sang vị trí phát âm /ɪ/.',
                'examples_json' => [
                    ['word' => 'boy', 'phonetic' => '/bɔɪ/', 'meaning' => 'cậu bé', 'example' => 'The boy plays football.'],
                    ['word' => 'choice', 'phonetic' => '/tʃɔɪs/', 'meaning' => 'sự lựa chọn', 'example' => 'Make a wise choice.'],
                    ['word' => 'voice', 'phonetic' => '/vɔɪs/', 'meaning' => 'giọng nói', 'example' => 'She has a lovely voice.'],
                ],
            ],
            [
                'symbol' => '/aʊ/',
                'category' => 'diphthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Mở miệng tròn phát âm /ɑ:/ rồi thu hẹp môi chu về phía trước chuyển sang /ʊ/.',
                'examples_json' => [
                    ['word' => 'now', 'phonetic' => '/naʊ/', 'meaning' => 'bây giờ', 'example' => 'Start doing it now.'],
                    ['word' => 'house', 'phonetic' => '/haʊs/', 'meaning' => 'ngôi nhà', 'example' => 'Welcome to my house.'],
                    ['word' => 'mouth', 'phonetic' => '/maʊθ/', 'meaning' => 'miệng', 'example' => 'Open your mouth wide.'],
                ],
            ],
            [
                'symbol' => '/əʊ/',
                'category' => 'diphthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Bắt đầu từ âm /ə/ thả lỏng rồi chu tròn môi nhẹ nhàng chuyển sang âm /ʊ/.',
                'examples_json' => [
                    ['word' => 'go', 'phonetic' => '/ɡəʊ/', 'meaning' => 'đi', 'example' => 'Let us go home.'],
                    ['word' => 'home', 'phonetic' => '/həʊm/', 'meaning' => 'tổ ấm', 'example' => 'Home sweet home.'],
                    ['word' => 'cold', 'phonetic' => '/kəʊld/', 'meaning' => 'lạnh', 'example' => 'The winter is cold.'],
                ],
            ],
            [
                'symbol' => '/ɪə/',
                'category' => 'diphthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Phát âm âm /ɪ/ ngắn rồi mở miệng tự nhiên chuyển lướt sang âm /ə/.',
                'examples_json' => [
                    ['word' => 'near', 'phonetic' => '/nɪər/', 'meaning' => 'gần', 'example' => 'The park is near here.'],
                    ['word' => 'clear', 'phonetic' => '/klɪər/', 'meaning' => 'rõ ràng', 'example' => 'The sky is very clear.'],
                    ['word' => 'hear', 'phonetic' => '/hɪər/', 'meaning' => 'nghe thấy', 'example' => 'Can you hear me?'],
                ],
            ],
            [
                'symbol' => '/eə/',
                'category' => 'diphthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Bắt đầu từ âm /e/ mở tự nhiên rồi thả lỏng môi chuyển dần sang âm /ə/.',
                'examples_json' => [
                    ['word' => 'hair', 'phonetic' => '/heər/', 'meaning' => 'mái tóc', 'example' => 'She has long hair.'],
                    ['word' => 'care', 'phonetic' => '/keər/', 'meaning' => 'chăm sóc', 'example' => 'Take care of yourself.'],
                    ['word' => 'chair', 'phonetic' => '/tʃeər/', 'meaning' => 'cái ghế', 'example' => 'Sit on the armchair.'],
                ],
            ],
            [
                'symbol' => '/ʊə/',
                'category' => 'diphthongs',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Bắt đầu từ vị trí âm /ʊ/ tròn môi rồi dần mở môi thả lỏng chuyển sang /ə/.',
                'examples_json' => [
                    ['word' => 'tour', 'phonetic' => '/tʊər/', 'meaning' => 'chuyến du lịch', 'example' => 'We booked a city tour.'],
                    ['word' => 'cure', 'phonetic' => '/kjʊər/', 'meaning' => 'chữa khỏi', 'example' => 'Doctors cure patients.'],
                    ['word' => 'pure', 'phonetic' => '/pjʊər/', 'meaning' => 'tinh khiết', 'example' => 'Drink pure water.'],
                ],
            ],

            // --- 3. PHỤ ÂM (24 CONSONANTS) ---
            [
                'symbol' => '/p/',
                'category' => 'consonants',
                'voiced_type' => 'unvoiced',
                'mouth_guide_vi' => 'Mím chặt hai môi lại nén luồng hơi, sau đó bật mạnh môi ra tạo tiếng nổ nhẹ mà không rung thanh quản (đặt tờ giấy trước miệng sẽ bay).',
                'examples_json' => [['word' => 'pen', 'phonetic' => '/pen/', 'meaning' => 'cây bút', 'example' => 'A nice pen.']],
            ],
            [
                'symbol' => '/b/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Khẩu hình giống /p/ nhưng làm rung mạnh dây thanh quản ở cổ họng khi bật môi ra.',
                'examples_json' => [['word' => 'book', 'phonetic' => '/bʊk/', 'meaning' => 'quyển sách', 'example' => 'Read this book.']],
            ],
            [
                'symbol' => '/t/',
                'category' => 'consonants',
                'voiced_type' => 'unvoiced',
                'mouth_guide_vi' => 'Đặt đầu lưỡi chạm vào nướu răng cửa trên chặn luồng hơi, hạ mạnh đầu lưỡi xuống bật luồng hơi ra mà không rung thanh quản.',
                'examples_json' => [['word' => 'tea', 'phonetic' => '/ti:/', 'meaning' => 'trà', 'example' => 'Drink hot tea.']],
            ],
            [
                'symbol' => '/d/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Khẩu hình giống /t/ nhưng bật hơi kết hợp rung mạnh dây thanh quản.',
                'examples_json' => [['word' => 'day', 'phonetic' => '/deɪ/', 'meaning' => 'ngày', 'example' => 'Good day to you.']],
            ],
            [
                'symbol' => '/k/',
                'category' => 'consonants',
                'voiced_type' => 'unvoiced',
                'mouth_guide_vi' => 'Nâng phần cuống lưỡi chạm vào ngạc mềm ở vòm họng nén hơi, rồi hạ lưỡi bật luồng hơi ra, không rung họng.',
                'examples_json' => [['word' => 'cat', 'phonetic' => '/kæt/', 'meaning' => 'con mèo', 'example' => 'Cute cat.']],
            ],
            [
                'symbol' => '/ɡ/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Khẩu hình giống /k/ nhưng kết hợp rung dây thanh quản khi bật cuống lưỡi.',
                'examples_json' => [['word' => 'good', 'phonetic' => '/ɡʊd/', 'meaning' => 'tốt', 'example' => 'Feel good today.']],
            ],
            [
                'symbol' => '/f/',
                'category' => 'consonants',
                'voiced_type' => 'unvoiced',
                'mouth_guide_vi' => 'Đặt răng cửa hàm trên chạm nhẹ vào môi dưới, đẩy luồng hơi liên tục qua khe hở giữa răng và môi mà không rung họng.',
                'examples_json' => [['word' => 'fish', 'phonetic' => '/fɪʃ/', 'meaning' => 'con cá', 'example' => 'Fresh fish.']],
            ],
            [
                'symbol' => '/v/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Khẩu hình giống /f/ nhưng làm rung mạnh dây thanh quản khi luồng hơi thổi qua.',
                'examples_json' => [['word' => 'voice', 'phonetic' => '/vɔɪs/', 'meaning' => 'giọng nói', 'example' => 'Soft voice.']],
            ],
            [
                'symbol' => '/θ/',
                'category' => 'consonants',
                'voiced_type' => 'unvoiced',
                'mouth_guide_vi' => 'Âm "th" vô thanh: Đặt đầu lưỡi nhẹ nhàng giữa hai hàng răng cửa, thổi luồng hơi ra ngoài qua kẽ răng, không rung họng.',
                'examples_json' => [['word' => 'think', 'phonetic' => '/θɪŋk/', 'meaning' => 'suy nghĩ', 'example' => 'I think so.']],
            ],
            [
                'symbol' => '/ð/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Âm "th" hữu thanh: Đặt đầu lưỡi giữa hai hàm răng giống /θ/ nhưng làm rung dây thanh quản khi luồng hơi thoát ra.',
                'examples_json' => [['word' => 'this', 'phonetic' => '/ðɪs/', 'meaning' => 'cái này', 'example' => 'This is my bag.']],
            ],
            [
                'symbol' => '/s/',
                'category' => 'consonants',
                'voiced_type' => 'unvoiced',
                'mouth_guide_vi' => 'Đặt hai hàm răng gần sát nhau, nâng đầu lưỡi sát chân răng trên, xì luồng hơi thật sắc ra ngoài, không rung họng.',
                'examples_json' => [['word' => 'sun', 'phonetic' => '/sʌn/', 'meaning' => 'mặt trời', 'example' => 'The sun shines.']],
            ],
            [
                'symbol' => '/z/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Khẩu hình giống /s/ nhưng rung mạnh dây thanh quản tạo tiếng vo ve như tiếng ong.',
                'examples_json' => [['word' => 'zoo', 'phonetic' => '/zu:/', 'meaning' => 'sở thú', 'example' => 'Visit the zoo.']],
            ],
            [
                'symbol' => '/ʃ/',
                'category' => 'consonants',
                'voiced_type' => 'unvoiced',
                'mouth_guide_vi' => 'Chu tròn môi hướng về trước, nâng thân lưỡi lên ngạc cứng vòm miệng, thổi luồng hơi mạnh ra như khi ra hiệu "suỵt".',
                'examples_json' => [['word' => 'she', 'phonetic' => '/ʃi:/', 'meaning' => 'cô ấy', 'example' => 'She is smiling.']],
            ],
            [
                'symbol' => '/ʒ/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Khẩu hình giống /ʃ/ nhưng kết hợp làm rung dây thanh quản trong cổ họng.',
                'examples_json' => [['word' => 'vision', 'phonetic' => '/ˈvɪʒn/', 'meaning' => 'tầm nhìn', 'example' => 'Clear vision.']],
            ],
            [
                'symbol' => '/tʃ/',
                'category' => 'consonants',
                'voiced_type' => 'unvoiced',
                'mouth_guide_vi' => 'Chu tròn môi, đặt đầu lưỡi chạm nướu răng trên như /t/, sau đó bật lưỡi lướt nhanh sang âm /ʃ/, không rung họng.',
                'examples_json' => [['word' => 'chair', 'phonetic' => '/tʃeər/', 'meaning' => 'cái ghế', 'example' => 'Sit on the chair.']],
            ],
            [
                'symbol' => '/dʒ/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Khẩu hình giống /tʃ/ nhưng kết hợp rung mạnh dây thanh quản.',
                'examples_json' => [['word' => 'joy', 'phonetic' => '/dʒɔɪ/', 'meaning' => 'niềm vui', 'example' => 'Pure joy.']],
            ],
            [
                'symbol' => '/m/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Mím nhẹ hai môi, luồng hơi đi qua mũi, rung thanh quản phát ra âm "m".',
                'examples_json' => [['word' => 'man', 'phonetic' => '/mæn/', 'meaning' => 'người đàn ông', 'example' => 'A gentle man.']],
            ],
            [
                'symbol' => '/n/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Đầu lưỡi chạm vào nướu răng cửa trên chặn khoang miệng, luồng hơi đi qua mũi, rung thanh quản tạo âm "n".',
                'examples_json' => [['word' => 'now', 'phonetic' => '/naʊ/', 'meaning' => 'bây giờ', 'example' => 'Do it now.']],
            ],
            [
                'symbol' => '/ŋ/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Nâng cuống lưỡi chạm vào ngạc mềm ở vòm họng chặn hơi, luồng hơi thoát ra qua đường mũi kèm rung họng (âm "ng").',
                'examples_json' => [['word' => 'sing', 'phonetic' => '/sɪŋ/', 'meaning' => 'hát', 'example' => 'Sing a happy song.']],
            ],
            [
                'symbol' => '/l/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Đầu lưỡi chạm chắc vào nướu răng cửa trên, luồng hơi thoát ra qua hai bên thân lưỡi, rung dây thanh quản.',
                'examples_json' => [['word' => 'light', 'phonetic' => '/laɪt/', 'meaning' => 'ánh sáng', 'example' => 'Bright light.']],
            ],
            [
                'symbol' => '/r/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Đầu lưỡi hơi cong ngược về phía sau vòm họng nhưng không chạm vào bất cứ đâu, môi hơi chu tròn nhẹ, rung họng.',
                'examples_json' => [['word' => 'red', 'phonetic' => '/red/', 'meaning' => 'màu đỏ', 'example' => 'Red rose.']],
            ],
            [
                'symbol' => '/w/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Chu tròn môi chặt về phía trước giống /u:/, nâng cuống lưỡi rồi mở nhanh môi sang hai bên tạo luồng hơi rung họng.',
                'examples_json' => [['word' => 'water', 'phonetic' => '/ˈwɔːtər/', 'meaning' => 'nước', 'example' => 'Clean water.']],
            ],
            [
                'symbol' => '/j/',
                'category' => 'consonants',
                'voiced_type' => 'voiced',
                'mouth_guide_vi' => 'Thân lưỡi nâng cao về phía ngạc cứng gần như chạm vào giống âm "y", rung dây thanh quản rồi lướt sang nguyên âm tiếp theo.',
                'examples_json' => [['word' => 'yes', 'phonetic' => '/jes/', 'meaning' => 'vâng, có', 'example' => 'Say yes to life.']],
            ],
            [
                'symbol' => '/h/',
                'category' => 'consonants',
                'voiced_type' => 'unvoiced',
                'mouth_guide_vi' => 'Miệng mở tự nhiên, thở nhẹ một luồng hơi từ cổ họng ra ngoài như tiếng thở dốc, không rung thanh quản.',
                'examples_json' => [['word' => 'hot', 'phonetic' => '/hɒt/', 'meaning' => 'nóng', 'example' => 'Hot summer.']],
            ],
        ];

        foreach ($sounds as $idx => $s) {
            IpaSound::create([
                'symbol' => $s['symbol'],
                'category' => $s['category'],
                'voiced_type' => $s['voiced_type'],
                'mouth_guide_vi' => $s['mouth_guide_vi'],
                'examples_json' => $s['examples_json'],
                'order_index' => $idx + 1,
            ]);
        }
    }
}
