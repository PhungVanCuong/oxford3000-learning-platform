<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grammar;

class UpdateGrammarExercisesSeeder extends Seeder
{
    public function run(): void
    {
        $allTranslations = [
            // 1. Hiện tại đơn (30 câu)
            1 => [
                ["id" => 1, "vi" => "Mary ghét mèo nhưng cô ấy rất yêu chó", "expected" => "Mary hates cats but she loves dogs"],
                ["id" => 2, "vi" => "Anh ấy thường chơi bóng đá mỗi buổi chiều", "expected" => "He often plays football every afternoon"],
                ["id" => 3, "vi" => "Tôi thường xuyên làm việc tại nhà", "expected" => "I often work from home"],
                ["id" => 4, "vi" => "Họ không thường xuyên đi ăn ngoài", "expected" => "They do not often eat out"],
                ["id" => 5, "vi" => "Cô ấy và tôi sống ở một ngôi nhà to tại thành phố Hồ Chí Minh", "expected" => "She and I live in a big house in HCM city"],
                ["id" => 6, "vi" => "Cha tôi đi làm bằng xe buýt và mẹ tôi đi xe máy.", "expected" => "My father goes to work by bus and my mother goes by motorbike"],
                ["id" => 7, "vi" => "Gia đình tôi thỉnh thoảng xem ti vi vào buổi tối và nói chuyện cùng nhau", "expected" => "My family sometimes watches TV in the evening and talks together"],
                ["id" => 8, "vi" => "Chúng tôi không thích học ngoại ngữ", "expected" => "We do not like learning foreign languages"],
                ["id" => 9, "vi" => "Minh là kỹ sư và vợ anh ấy là bác sỹ", "expected" => "Minh is an engineer and his wife is a doctor"],
                ["id" => 10, "vi" => "Tôi có 2 con chó. Tôi thường đi dạo với chúng vào buổi tối", "expected" => "I have two dogs. I usually walk them in the evening"],
                ["id" => 11, "vi" => "Sơn Tùng là ca sỹ và anh ấy rất nổi tiếng ở Việt Nam", "expected" => "Son Tung is a singer and he is very famous in Vietnam"],
                ["id" => 12, "vi" => "Tôi là fan của nhạc Pop", "expected" => "I am a fan of pop music"],
                ["id" => 13, "vi" => "Anh ấy có thể uống nhiều bia", "expected" => "He can drink a lot of beer"],
                ["id" => 14, "vi" => "Cha tôi thường nấu bữa tối", "expected" => "My father usually cooks dinner"],
                ["id" => 15, "vi" => "Bạn có thường xuyên nghe nhạc không ?", "expected" => "Do you often listen to music?"],
                ["id" => 16, "vi" => "Jim học tiếng Trung ở đâu vậy ?", "expected" => "Where does Jim study Chinese?"],
                ["id" => 17, "vi" => "Khi nào thì cửa hàng này đóng cửa ?", "expected" => "When does this shop close?"],
                ["id" => 18, "vi" => "Ngọc đến trường bằng gì ?", "expected" => "How does Ngoc go to school?"],
                ["id" => 19, "vi" => "Bạn thường thức dậy lúc mấy giờ ?", "expected" => "What time do you usually get up?"],
                ["id" => 20, "vi" => "Gia đình Huy có 4 người.", "expected" => "There are 4 people in Huy's family"],
                ["id" => 21, "vi" => "Anh ta đi tập thể dục thường xuyên như thế nào ?", "expected" => "How often does he exercise?"],
                ["id" => 22, "vi" => "Mary rất giỏi về kỹ năng máy tính", "expected" => "Mary is very good at computer skills"],
                ["id" => 23, "vi" => "Bạn có hay đi biển không ?", "expected" => "Do you often go to the beach?"],
                ["id" => 24, "vi" => "Đây là một ngôi nhà đẹp.", "expected" => "This is a beautiful house"],
                ["id" => 25, "vi" => "Mỗi năm cô ấy đều đi du lịch vào mùa hè", "expected" => "She travels in the summer every year"],
                ["id" => 26, "vi" => "Duy là một nhân viên tốt, anh ấy thường đi làm rất sớm", "expected" => "Duy is a good employee, he usually goes to work very early"],
                ["id" => 27, "vi" => "Xe buýt số 7 luôn xuất phát đúng giờ", "expected" => "Bus number 7 always departs on time"],
                ["id" => 28, "vi" => "Tôi hay ăn trưa lúc 11 giờ.", "expected" => "I usually have lunch at 11"],
                ["id" => 29, "vi" => "Quản lí của Tom rất giỏi, anh ấy thường rời công ty trễ", "expected" => "Tom's manager is very good, he usually leaves work late"],
                ["id" => 30, "vi" => "Mùa hè ở Việt Nam rất nóng.", "expected" => "Summer in Vietnam is very hot"]
            ],

            // 2. Hiện tại tiếp diễn (30 câu)
            2 => [
                ["id" => 1, "vi" => "Nghe kìa! Em bé đang khóc", "expected" => "Listen! The baby is crying"],
                ["id" => 2, "vi" => "Nhìn kìa! Sơn đang đánh đàn và Duy đang hát", "expected" => "Look! Son is playing the piano and Duy is singing"],
                ["id" => 3, "vi" => "Huy đang làm bài tập và Dũng đang học Tiếng Anh", "expected" => "Huy is doing homework and Dung is learning English"],
                ["id" => 4, "vi" => "Tôi đang gội đầu", "expected" => "I am washing my hair"],
                ["id" => 5, "vi" => "Jim đang viết một lá thư cho bạn gái của anh ấy", "expected" => "Jim is writing a letter for his girlfriend"],
                ["id" => 6, "vi" => "Anh ấy đang ngồi cạnh cửa sổ và nghe radio", "expected" => "He is sitting by the window and listening to the radio"],
                ["id" => 7, "vi" => "Trời đang mưa", "expected" => "It is raining"],
                ["id" => 8, "vi" => "Bạn đang làm gì vậy ?", "expected" => "What are you doing?"],
                ["id" => 9, "vi" => "Tôi đang đợi xe buýt", "expected" => "I am waiting for the bus"],
                ["id" => 10, "vi" => "Tom đang đi đâu vậy ?", "expected" => "Where is Tom going?"],
                ["id" => 11, "vi" => "Anh ấy đang đi gặp bác sỹ", "expected" => "He is going to see the doctor"],
                ["id" => 12, "vi" => "Tôi sẽ đi xem phim với bạn gái tôi ngày mai. Tôi đã mua vé rồi.", "expected" => "I am going to the cinema with my girlfriend tomorrow. I have bought the tickets"],
                ["id" => 13, "vi" => "Tôi đặt bàn rồi, tối nay chúng ta sẽ đi nhậu.", "expected" => "I have booked the table, we are going for beer tonight"],
                ["id" => 14, "vi" => "Cuối tuần chúng ta đi ăn buffet, Tôi có 2 vé đây", "expected" => "We are going for buffet this weekend, I have two tickets"],
                ["id" => 15, "vi" => "Nhanh lên, xe tôi đang hết xăng", "expected" => "Hurry up! My car is running out of gas"],
                ["id" => 16, "vi" => "Điện thoại bạn đang đổ chuông kìa.", "expected" => "Your phone is ringing"],
                ["id" => 17, "vi" => "Ngọc đang phô tô vài thứ tài liệu", "expected" => "Ngoc is photocopying some documents"],
                ["id" => 18, "vi" => "Các bạn nam đang chơi bóng đá đằng kia", "expected" => "The boys are playing football over there"],
                ["id" => 19, "vi" => "Vợ anh ấy sẽ sinh tuần tới", "expected" => "His wife is giving birth next week"],
                ["id" => 20, "vi" => "Nhìn kìa ! Con mèo đang cố gắng bắt con chuột", "expected" => "Look! The cat is trying to catch the mouse"],
                ["id" => 21, "vi" => "Nghe đi, anh ấy đang đến.", "expected" => "Listen! He is coming"],
                ["id" => 22, "vi" => "Cô ấy đang lái xe đến trường", "expected" => "She is driving to school"],
                ["id" => 23, "vi" => "Cha tôi đang xem ti vi, và mẹ tôi thì đang lau sàn", "expected" => "My dad is watching TV, and my mother is cleaning the floor"],
                ["id" => 24, "vi" => "Tôi đang ăn thức ăn nhanh", "expected" => "I am eating fast food"],
                ["id" => 25, "vi" => "Tối nay Mai sẽ đi sinh nhật của Jim", "expected" => "Tonight Mai is going to Jim's birthday party"],
                ["id" => 26, "vi" => "Tôi đang làm việc tại câu lạc bộ từ thiện", "expected" => "I am working at a charity club"],
                ["id" => 27, "vi" => "Bố tôi đang trả lời điện thoại", "expected" => "My father is answering the phone"],
                ["id" => 28, "vi" => "Anh trai tôi đang tưới cây", "expected" => "My brother is watering the tree"],
                ["id" => 29, "vi" => "Ông chủ đang viết báo cáo", "expected" => "The boss is writing a report"],
                ["id" => 30, "vi" => "Bạn nên đem theo áo khoác, trời đang trở lạnh", "expected" => "You should bring a jacket, it is getting cold"]
            ],

            // 3. Hiện tại hoàn thành (30 câu)
            3 => [
                ["id" => 1, "vi" => "Tôi đã làm ở công ty Manulife được 3 năm", "expected" => "I have worked in Manulife company for 3 years"],
                ["id" => 2, "vi" => "Huỳnh đã đi Mỹ từ tháng 2", "expected" => "Huynh has gone to America since February"],
                ["id" => 3, "vi" => "Mary đã làm việc tại ngân hàng được 2 tháng", "expected" => "Mary has worked at the bank for 2 months"],
                ["id" => 4, "vi" => "Đồng nghiệp của tôi đã mua một chiếc điện thoại mới", "expected" => "My coworker has bought a new phone"],
                ["id" => 5, "vi" => "Cái máy tính đã bị hỏng rồi", "expected" => "The computer has broken down"],
                ["id" => 6, "vi" => "Quản lý của tôi đã ăn tối rồi", "expected" => "My manager has already eaten dinner"],
                ["id" => 7, "vi" => "Bạn đã sống ở đây bao lâu ?", "expected" => "How long have you lived here?"],
                ["id" => 8, "vi" => "Tôi đã mất xe đạp", "expected" => "I have lost my bike"],
                ["id" => 9, "vi" => "Cường chưa về quê 4 năm rồi", "expected" => "Cuong hasn't gone home for 4 years"],
                ["id" => 10, "vi" => "Nga chưa gặp tôi 8 ngày rồi.", "expected" => "Nga has not seen me for 8 days"],
                ["id" => 11, "vi" => "Ben đã ăn tối rồi còn vợ anh ấy thì chưa ăn", "expected" => "Ben has eaten dinner, but his wife hasn't eaten yet"],
                ["id" => 12, "vi" => "Cha của John vừa mua chiếc máy tính mới", "expected" => "John's father has just bought a new computer"],
                ["id" => 13, "vi" => "Chúng tôi đã không gặp nhau trong khoảng thời gian dài", "expected" => "We haven't seen each other for a long time"],
                ["id" => 14, "vi" => "Anh ấy đã không điện thoại cho gia đình từ tháng 8", "expected" => "He hasn't called his family since August"],
                ["id" => 15, "vi" => "Tôi chưa làm bài tập về nhà", "expected" => "I haven't done my homework"],
                ["id" => 16, "vi" => "An chưa hoàn thành bài báo cáo", "expected" => "An hasn't finished the report"],
                ["id" => 17, "vi" => "Huy chưa đăng kí được môn marketing", "expected" => "Huy hasn't registered for marketing yet"],
                ["id" => 18, "vi" => "Tôi vừa mới ăn trưa xong.", "expected" => "I have just had lunch"],
                ["id" => 19, "vi" => "Bạn đã từng đến Trung Quốc chưa?", "expected" => "Have you ever been to China?"],
                ["id" => 20, "vi" => "Tôi chưa từng đến Châu Âu", "expected" => "I have never been to Europe"],
                ["id" => 21, "vi" => "Hoàng đã đi Mỹ 2 lần rồi", "expected" => "Hoang has been to America twice"],
                ["id" => 22, "vi" => "Jim chưa tắm từ thứ 7 tuần trước", "expected" => "Jim hasn't taken a shower since last Saturday"],
                ["id" => 23, "vi" => "Gia đình tôi chưa đi ăn ngoài 2 tháng rồi", "expected" => "My family hasn't eaten out for 2 months"],
                ["id" => 24, "vi" => "Cô ấy đã viết 4 quyển sách về tình yêu", "expected" => "She has written four books about love"],
                ["id" => 25, "vi" => "Tôi đã nghe bài hát mới của Tùng Sơn rồi", "expected" => "I have heard Tung Son's new song"],
                ["id" => 26, "vi" => "Tôi mới sửa xong chiếc xe máy của anh, tới và kiểm tra nó xem", "expected" => "I have just fixed your motorbike, come and check it"],
                ["id" => 27, "vi" => "Vợ của Linh vừa được thăng chức", "expected" => "Linh's wife has just got a promotion"],
                ["id" => 28, "vi" => "Tôi vừa mới chơi thể thao xong", "expected" => "I have just played sports"],
                ["id" => 29, "vi" => "Bạn đã xem phim Titanic chưa?", "expected" => "Have you watched the Titanic movie yet?"],
                ["id" => 30, "vi" => "Nguyên đã rời khỏi công ty được 5 năm", "expected" => "Nguyen has left the company for 5 years"]
            ],

            // 4. Hiện tại hoàn thành tiếp diễn (10 câu)
            4 => [
                ["id" => 1, "vi" => "Tôi đã đợi bạn cả buổi chiều", "expected" => "I have been waiting for you all afternoon"],
                ["id" => 2, "vi" => "Cô ấy không đi xem phim từ năm ngoái", "expected" => "She hasn't been watching movies since last year"],
                ["id" => 3, "vi" => "Anh ấy gõ bài báo cáo từ sáng rồi đúng không?", "expected" => "Has he been typing the report since the morning?"],
                ["id" => 4, "vi" => "Tôi đã chạy bộ cả buổi sáng", "expected" => "I have been running all morning"],
                ["id" => 5, "vi" => "Cô ấy đã dạy Tiếng Anh được 10 năm", "expected" => "She has been teaching English for 10 years"],
                ["id" => 6, "vi" => "Anh đã đi đâu vậy? Tôi đã tìm anh cả ngày", "expected" => "Where have you been? I have been looking for you all day"],
                ["id" => 7, "vi" => "Jim đã đứng dưới mưa 2 tiếng phải không", "expected" => "Has Jim been standing in the rain for 2 hours?"],
                ["id" => 8, "vi" => "Mary đã ở thư viện từ trưa nay", "expected" => "Mary has been staying at the library since noon"],
                ["id" => 9, "vi" => "Mathew đã tập chơi Tennis được 7 năm.", "expected" => "Mathew has been learning to play tennis for 7 years"],
                ["id" => 10, "vi" => "Bạn hát hay thật. Bạn học hát bao lâu rồi?", "expected" => "You sing very well. How long have you been learning to sing?"]
            ],

            // 5. Quá khứ đơn (30 câu)
            5 => [
                ["id" => 1, "vi" => "Tôi đã chuyển đến ĐN năm ngoái", "expected" => "I moved to Da Nang last year"],
                ["id" => 2, "vi" => "Chúng tôi đã rất vui ngày hôm qua", "expected" => "We were very happy yesterday"],
                ["id" => 3, "vi" => "Thứ 7 tuần trước, tôi đã không đi đá bóng", "expected" => "Last Saturday, I didn't play football"],
                ["id" => 4, "vi" => "Cô ấy đã mua nhiều quà vào chuyến đi trước", "expected" => "She bought many gifts on the last trip"],
                ["id" => 5, "vi" => "Có phải hôm qua bạn đã mua quyển sách này?", "expected" => "Did you buy this book yesterday?"],
                ["id" => 6, "vi" => "Sáng nay tôi đã không ăn sáng, bây giờ tôi đói quá", "expected" => "This morning, I didn't have breakfast, now I am hungry"],
                ["id" => 7, "vi" => "Bạn đã đến đây bằng gì?", "expected" => "How did you get here?"],
                ["id" => 8, "vi" => "Quản lí đã gặp tôi 2 ngày trước, anh ấy đã tăng lương của tôi", "expected" => "The manager met me two days ago, he increased my salary"],
                ["id" => 9, "vi" => "Nga đã đi học trễ sáng nay", "expected" => "Nga went to school late this morning"],
                ["id" => 10, "vi" => "2 tuần trước tôi đã đi tham quan Bà Nà", "expected" => "Two weeks ago I visited Ba Na"],
                ["id" => 11, "vi" => "Tối qua cầu rồng không phun lửa", "expected" => "Last night the Dragon Bridge didn't breathe fire"],
                ["id" => 12, "vi" => "Nga đã hoàn thành kì thi Tiếng Anh 2 tiếng trước. Bây giờ cô ấy đang về nhà", "expected" => "Nga finished her English exam two hours ago. Now she is going home"],
                ["id" => 13, "vi" => "Minh đã là nhà vô địch hôm qua. Anh ấy đã chơi Tennis 10 năm rồi", "expected" => "Minh was the champion yesterday. He has played tennis for 10 years"],
                ["id" => 14, "vi" => "Hôm qua, Cha tôi đã đưa tôi đi công viên. Tôi đã gặp bạn tôi ở đó", "expected" => "Yesterday, my father took me to the park. I met my friend there"],
                ["id" => 15, "vi" => "Tối qua anh đã ở đâu?", "expected" => "Where were you last night?"],
                ["id" => 16, "vi" => "Con đã làm xong việc nhà chưa?", "expected" => "Did you finish your chores?"],
                ["id" => 17, "vi" => "Thứ 5 tuần trước tôi đã đi Bar", "expected" => "Last Thursday I went to a bar"],
                ["id" => 18, "vi" => "Mary không đi chơi với tôi ngày hôm qua. Cô ấy đã đi làm.", "expected" => "Mary didn't hang out with me yesterday, she went to work"],
                ["id" => 19, "vi" => "Jim đã bắt đầu học Tiếng Nhật tháng trước", "expected" => "Jim started learning Japanese last month"],
                ["id" => 20, "vi" => "Cô ấy đã tốt nghiệp Đại học ở TP Hồ Chí Minh", "expected" => "She graduated from university in HCM city"],
                ["id" => 21, "vi" => "Phát đã sơn ngôi nhà này hôm kia", "expected" => "Phat painted this house the day before yesterday"],
                ["id" => 22, "vi" => "Tối qua bạn đã gặp ai?", "expected" => "Who did you meet last night?"],
                ["id" => 23, "vi" => "Anh ấy đã không đến bữa tiệc sinh nhật của tôi tuần trước", "expected" => "He didn't come to my birthday party last week"],
                ["id" => 24, "vi" => "Xe buýt đã rời đi lúc 5 giờ sáng nay", "expected" => "The bus left at 5 AM this morning"],
                ["id" => 25, "vi" => "Hà đã đi làm trễ", "expected" => "Ha went to work late"],
                ["id" => 26, "vi" => "An đã làm cái bánh này đúng không?", "expected" => "Did An make this cake?"],
                ["id" => 27, "vi" => "Ông chủ đã đưa chìa khóa văn phòng cho tôi sáng nay", "expected" => "The boss gave me the office key this morning"],
                ["id" => 28, "vi" => "Tôi và anh ấy đã đi dạo ngoài trời", "expected" => "He and I took a walk outside"],
                ["id" => 29, "vi" => "Trời hôm qua rất nóng nhưng chúng tôi vẫn đi cắm trại", "expected" => "It was very hot yesterday but we still went camping"],
                ["id" => 30, "vi" => "Tôi đã tới ĐN 2 tiếng trước và Tom đã đón tôi tại sân bay.", "expected" => "I arrived in Da Nang two hours ago and Tom picked me up at the airport"]
            ],

            // 6. Quá khứ tiếp diễn (30 câu)
            6 => [
                ["id" => 1, "vi" => "Alice đã nhìn thấy một tai nạn khi cô ta đang bắt xe buýt", "expected" => "Alice saw an accident while she was catching the bus"],
                ["id" => 2, "vi" => "Bạn đã đang làm gì khi tôi gọi vậy?", "expected" => "What were you doing when I called?"],
                ["id" => 3, "vi" => "Tôi đã không thăm những người bạn của tôi mùa hè năm ngoái", "expected" => "I was not visiting my friends last summer"],
                ["id" => 4, "vi" => "Tháng 7 năm ngoái, trời đã mưa to", "expected" => "Last July, it was raining heavily"],
                ["id" => 5, "vi" => "Trong khi mọi người đang nói chuyện với nhau, anh ta đã đang đọc sách.", "expected" => "While everyone was talking to each other, he was reading a book"],
                ["id" => 6, "vi" => "Tháng trước chị tôi đã ăn hamburger mỗi ngày", "expected" => "Last month, my sister was eating hamburgers every day"],
                ["id" => 7, "vi" => "Mary đã ngã khi chúng tôi đang chạy tuần trước", "expected" => "Mary fell while we were running last week"],
                ["id" => 8, "vi" => "Hôm qua bạn có tìm ra chìa khóa không ?", "expected" => "Were you finding your keys yesterday?"],
                ["id" => 9, "vi" => "Hôm qua cô ấy đã nhảy với ai vậy?", "expected" => "Who was she dancing with yesterday?"],
                ["id" => 10, "vi" => "Họ đang xem phim lúc 7 giờ tối hôm qua.", "expected" => "They were watching a movie at 7 PM yesterday"],
                ["id" => 11, "vi" => "Người công nhân đã lấy mấy chiếc đồng hồ khi anh ta làm việc", "expected" => "The worker was taking some watches when he was working"],
                ["id" => 12, "vi" => "Hôm qua anh ta đang không làm việc khi quản lí đến", "expected" => "He wasn't working when the manager arrived yesterday"],
                ["id" => 13, "vi" => "Lúc 7 giờ sáng hôm qua, mẹ em đang đi chợ đúng không?", "expected" => "Was your mother going to the market at 7 AM yesterday?"],
                ["id" => 14, "vi" => "Cô ấy đang múa trong khi tôi đang hát", "expected" => "She was dancing while I was singing"],
                ["id" => 15, "vi" => "Lúc 9 giờ sáng hôm qua, anh ta đang câu cá", "expected" => "At 9 AM yesterday, he was fishing"],
                ["id" => 16, "vi" => "Trong lúc Jim tắm thì vợ anh ta đang dùng máy tính", "expected" => "While Jim was taking a bath, his wife was using the computer"],
                ["id" => 17, "vi" => "Cô ấy đang trồng cây lúc 4 giờ chiều nay à", "expected" => "Was she planting trees at 4 PM this afternoon?"],
                ["id" => 18, "vi" => "Lúc 5 giờ sáng thứ 2 tuần này, Anh đang làm gì?", "expected" => "What were you doing at 5 AM on Monday?"],
                ["id" => 19, "vi" => "Điện thoại đổ chuông khi tôi đang gội đầu", "expected" => "The phone rang while I was washing my hair"],
                ["id" => 20, "vi" => "Ann đã buồn ngủ khi cô ấy đang xem tivi, tối qua.", "expected" => "Ann felt sleepy while she was watching TV last night"],
                ["id" => 21, "vi" => "Chúng tôi thấy một tai nạn khi chúng tôi đang ăn sáng", "expected" => "We saw an accident while we were having breakfast"],
                ["id" => 22, "vi" => "Khi mọi người đang nói chuyện thì anh ấy đang đọc sách", "expected" => "While everyone was talking, he was reading a book"],
                ["id" => 23, "vi" => "Trong khi tôi đang lái xe về nhà, Peter đã đang cố gọi cho tôi", "expected" => "While I was driving home, Peter was trying to call me"],
                ["id" => 24, "vi" => "Tôi đang thuyết trình thì cái mic bị hư", "expected" => "I was presenting when the mic broke"],
                ["id" => 25, "vi" => "Bạn đang làm gì lúc 8 giờ tối hôm qua vậy", "expected" => "What were you doing at 8 PM yesterday?"],
                ["id" => 26, "vi" => "Họ đang không làm bài tập, khi giáo viên vào lớp chiều hôm qua", "expected" => "They weren't doing their homework when the teacher entered the class yesterday"],
                ["id" => 27, "vi" => "Anh đang đi đâu lúc tôi gặp anh tuần trước vậy?", "expected" => "Where were you going when I met you last week?"],
                ["id" => 28, "vi" => "Tôi đang học trong phòng khi các bạn tôi đang hát ở phòng bên cạnh", "expected" => "I was studying in my room while my friends were singing in the next room"],
                ["id" => 29, "vi" => "Vào năm 2000, họ đang làm việc tại công ty Sony", "expected" => "In 2000, they were working at Sony company"],
                ["id" => 30, "vi" => "Họ có nghe trong khi anh ấy đang nói không?", "expected" => "Were they listening while he was speaking?"]
            ],

            // 7. Quá khứ hoàn thành (30 câu)
            7 => [
                ["id" => 1, "vi" => "Sau khi ăn xong thì Ann đã đi ngủ", "expected" => "After Ann had eaten, she went to bed"],
                ["id" => 2, "vi" => "Trước khi anh ấy đến thì bữa tiệc đã kết thúc", "expected" => "Before he arrived, the party had ended"],
                ["id" => 3, "vi" => "Nguyên đã rời đi trước thời điểm bạn đến sân bay", "expected" => "Nguyen had left before you arrived at the airport"],
                ["id" => 4, "vi" => "Khi mà Jim thức dậy thì bạn gái anh ấy đã đi học rồi", "expected" => "When Jim woke up, his girlfriend had already gone to school"],
                ["id" => 5, "vi" => "Họ đã hoàn thành bài báo cáo trước thứ sáu tuần trước", "expected" => "They had finished the report before last Friday"],
                ["id" => 6, "vi" => "Trước lúc tôi đi làm thì mẹ tôi đã nấu xong bữa sáng", "expected" => "Before I went to work, my mother had cooked breakfast"],
                ["id" => 7, "vi" => "Cô ấy đã về quê trước tháng 6 năm ngoái", "expected" => "She had returned to her hometown before June last year"],
                ["id" => 8, "vi" => "Cậu ấy đã lau nhà xong trước lúc mẹ cậu ấy về nhà", "expected" => "He had finished mopping the floor before his mother came home"],
                ["id" => 9, "vi" => "Khi gia đình tôi đến sân vận động, trận đấu đã bắt đầu", "expected" => "When my family arrived at the stadium, the match had started"],
                ["id" => 10, "vi" => "Trước khi cho chó ăn thì Peter đã hoàn thành bài tập về nhà", "expected" => "Before feeding the dog, Peter had finished his homework"],
                ["id" => 11, "vi" => "Bộ phim đã kết thúc trước khi tôi đi ra ngoài phải không?", "expected" => "Had the movie ended before I went out?"],
                ["id" => 12, "vi" => "Ann đã vẽ xong bức tranh trước 7 giờ tối qua", "expected" => "Ann had finished drawing the picture before 7 PM last night"],
                ["id" => 13, "vi" => "Mary đã viết xong bức thư này trước 5 giờ chiều hôm qua phải không?", "expected" => "Had Mary written this letter before 5 PM yesterday?"],
                ["id" => 14, "vi" => "Lớp tôi đã đậu kì thi Tiếng anh trước khi tốt nghiệp vào tháng 5", "expected" => "My class had passed the English exam before graduating in May"],
                ["id" => 15, "vi" => "Giáo viên đã giảng bài này rất kĩ trước khi kiểm tra chúng tôi", "expected" => "The teacher had explained this lesson very carefully before testing us"],
                ["id" => 16, "vi" => "Con mèo của tôi đã bị mất trước sáng hôm nay", "expected" => "My cat had been lost before this morning"],
                ["id" => 17, "vi" => "Trước khi tôi dùng, cái máy in này đã hư rồi", "expected" => "Before I used it, this printer had already broken down"],
                ["id" => 18, "vi" => "Trước trưa hôm qua, Jack đã về đến quê nhà", "expected" => "Before noon yesterday, Jack had arrived at his hometown"],
                ["id" => 19, "vi" => "Trước thời điểm thư viện đóng cửa, tôi đã mượn được vài quyển sách", "expected" => "Before the library closed, I had borrowed some books"],
                ["id" => 20, "vi" => "Tôi đã ăn sáng trước khi đi làm sáng nay", "expected" => "I had eaten breakfast before I went to work this morning"],
                ["id" => 21, "vi" => "Cô ấy đã bật ti vi trước khi rời đi", "expected" => "She had turned on the TV before leaving"],
                ["id" => 22, "vi" => "Bạn tôi đã nấu bữa tối trước khi đi câu cá chiều nay", "expected" => "My friend had cooked dinner before going fishing this afternoon"],
                ["id" => 23, "vi" => "Họ đã về nhà trước khi ăn xong bữa tối bên ngoài", "expected" => "They had gone home before finishing dinner out"],
                ["id" => 24, "vi" => "Khi họ đến, máy bay đã cất cánh.", "expected" => "When they arrived, the plane had taken off"],
                ["id" => 25, "vi" => "Trước khi mua cái mũ này, tôi đã xem quanh cửa hàng rất kĩ", "expected" => "Before buying this hat, I had looked around the store very carefully"],
                ["id" => 26, "vi" => "Hòa đã chơi game online sau khi sửa xong máy tính", "expected" => "Hoa played games online after he had fixed the computer"],
                ["id" => 27, "vi" => "Mẹ tôi đã nấu ăn sau khi đi chợ sáng nay", "expected" => "My mother cooked after she had gone to the market this morning"],
                ["id" => 28, "vi" => "Trước khi đi siêu thị, anh ấy đã mua nhiều thức ăn.", "expected" => "Before going to the supermarket, he had bought a lot of food"],
                ["id" => 29, "vi" => "Tuần trước sau khi mở cửa, Jim đã bán được nhiều mặt hàng", "expected" => "Last week, after Jim had opened the shop, he sold many items"],
                ["id" => 30, "vi" => "Peter đã xây ngôi nhà này trước khi kết hôn", "expected" => "Peter had built his house before he got married"]
            ],

            // 8. Tương lai đơn (30 câu)
            8 => [
                ["id" => 1, "vi" => "Tôi sẽ chuyển đến TP Hồ chí minh năm tới", "expected" => "I will move to HCM city next year"],
                ["id" => 2, "vi" => "Tối nay lớp tôi sẽ đi tiệc", "expected" => "Tonight my class will go to the party"],
                ["id" => 3, "vi" => "Xe buýt sẽ tới đây chứ?", "expected" => "Will the bus come here?"],
                ["id" => 4, "vi" => "Cửa hàng sẽ mở cửa lại vào tuần sau", "expected" => "The store will reopen next week"],
                ["id" => 5, "vi" => "Tớ sẽ không đem bia đến bữa tiệc đâu", "expected" => "I won't bring beer to the party"],
                ["id" => 6, "vi" => "Tôi sẽ không nói với cô ấy sự thật đâu", "expected" => "I won't tell her the truth"],
                ["id" => 7, "vi" => "Bạn sẽ ra sân bay đón anh ấy à", "expected" => "Will you go to airport to pick him up?"],
                ["id" => 8, "vi" => "Tháng sau Tôi sẽ đậu kì thi Tiếng Anh", "expected" => "Next month, I will pass the English exam"],
                ["id" => 9, "vi" => "Tôi sẽ đi siêu thị để mua một ít thức ăn", "expected" => "I will go the supermarket to buy some food"],
                ["id" => 10, "vi" => "Khi nào cô ấy kết hôn", "expected" => "When will she get married?"],
                ["id" => 11, "vi" => "Ai sẽ sửa cái máy tính?", "expected" => "Who will repair the computer?"],
                ["id" => 12, "vi" => "Trời sẽ mưa ngày mai", "expected" => "It will rain tomorrow"],
                ["id" => 13, "vi" => "Tôi sẽ không bao giờ nói chuyện với bạn nữa", "expected" => "I will never talk to you again"],
                ["id" => 14, "vi" => "Nếu bài tập quá khó, Anh tôi sẽ giúp bạn làm nó", "expected" => "If the homework is too difficult, my brother will help you do it"],
                ["id" => 15, "vi" => "Anh ấy hy vọng rằng Mary sẽ không tới bữa tiệc tối nay", "expected" => "He hopes that Mary will not come to the party tonight"],
                ["id" => 16, "vi" => "John sẽ hoàn thành báo cáo trong 2 ngày", "expected" => "John will finish the report in 2 days"],
                ["id" => 17, "vi" => "Họ sẽ không uống bia vào bữa tiệc hội nghị", "expected" => "They will not drink beer at the conference party"],
                ["id" => 18, "vi" => "Tôi sẽ giúp anh ấy gởi tấm thiệp này", "expected" => "I will help him send this card"],
                ["id" => 19, "vi" => "Ngày mai ai sẽ đi tập thể dục?", "expected" => "Who will go to exercise tomorrow?"],
                ["id" => 20, "vi" => "Ai sẽ tham gia câu lạc bộ Tiếng Anh?", "expected" => "Who will join the English club?"],
                ["id" => 21, "vi" => "Tôi hứa tối sẽ làm bài tập trước khi đi học", "expected" => "I promise I will do my homework before going to school"],
                ["id" => 22, "vi" => "Tôi sẽ nhắc nhở những sinh viên của tôi về bài tập về nhà", "expected" => "I will remind my students about the homework"],
                ["id" => 23, "vi" => "Tối sẽ viết một quyển sách về Tình yêu", "expected" => "I will write a book about love"],
                ["id" => 24, "vi" => "Năm sau, chúng ta sẽ đi Paris không ?", "expected" => "Will we go to Paris next year?"],
                ["id" => 25, "vi" => "Công ty chúng tôi sẽ mở cửa một cửa hàng mới năm sau", "expected" => "Our company will open a new store next year"],
                ["id" => 26, "vi" => "Nhóm bạn sẽ tham gia cuộc thi chứ ?", "expected" => "Will your group join the competition?"],
                ["id" => 27, "vi" => "Đội bóng chúng tôi sẽ thắng trận chung kết", "expected" => "Our football team will win the final match"],
                ["id" => 28, "vi" => "Tôi sẽ trở thành nhà vô địch", "expected" => "I will become the champion"],
                ["id" => 29, "vi" => "Chiều nay, em nhớ mua hải sản cho bữa tối chứ ?", "expected" => "This afternoon, will you remember to buy seafood for dinner?"],
                ["id" => 30, "vi" => "Chồng tôi sẽ trồng cây này trong vườn", "expected" => "My husband will plant this tree in the garden"]
            ],

            // 9. Tương lai gần (10 câu)
            9 => [
                ["id" => 1, "vi" => "Tôi đã mua vé rồi, ngày mai tôi sẽ đi HCM", "expected" => "I have bought the tickets, tomorrow I am going to HCM city"],
                ["id" => 2, "vi" => "Tối nay lớp chúng ta sẽ đi nhậu", "expected" => "Tonight our class is going to have some beer"],
                ["id" => 3, "vi" => "Nhiều mây quá, trời sẽ mưa đấy.", "expected" => "It's very cloudy, it is going to rain"],
                ["id" => 4, "vi" => "Tôi vừa nhận lương, tôi sẽ mua một chiếc điện thoại mới", "expected" => "I have received my salary, I am going to buy a new phone"],
                ["id" => 5, "vi" => "Công ty đã phân công, Tháng tới tôi sẽ đi công tác ở Thái Lan", "expected" => "The company has assigned me, next month I am going to Thailand on business"],
                ["id" => 6, "vi" => "Coi chừng ! chiếc cốc sắp rơi xuống kìa", "expected" => "Watch out! The cup is going to fall down"],
                ["id" => 7, "vi" => "Tôi rất mệt, ngày mai tôi sẽ không đi học được", "expected" => "I am tired, tomorrow I am not going to go to school"],
                ["id" => 8, "vi" => "Họ sẽ không hủy cuộc họp vì ông chủ đã quay lại", "expected" => "They are not going to cancel the meeting because the boss has returned"],
                ["id" => 9, "vi" => "Bạn sẽ ở lại nhà ông bà tối nay phải không ?", "expected" => "Are you going to stay at your grandparents' house tonight?"],
                ["id" => 10, "vi" => "Cha mẹ sẽ đến thăm tôi ngày mai, họ vừa điện thoại cho tôi", "expected" => "My parents are going to visit me tomorrow, they have just called me"]
            ],

            // 10. Tương lai tiếp diễn (20 câu)
            10 => [
                ["id" => 1, "vi" => "Vào lúc này ngày mai thì tôi đang học bài", "expected" => "At this time tomorrow, I will be studying"],
                ["id" => 2, "vi" => "Sáng mai lúc 7 giờ, chúng tôi đang họp", "expected" => "At 7 AM tomorrow, we will be having a meeting"],
                ["id" => 3, "vi" => "Thứ sáu tuần sau cô ấy sẽ đang ở Nha Trang", "expected" => "Next Friday, she will be staying in Nha Trang"],
                ["id" => 4, "vi" => "Ngày mai tôi sẽ đang thi khi mà bạn quay lên thành phố. Tôi sẽ không đón bạn được", "expected" => "Tomorrow I will be taking an exam when you return to the city. I will not be able to pick you up"],
                ["id" => 5, "vi" => "Thời điểm này vào năm tới thì Anh ấy đang học ở HCM", "expected" => "At this time next year, he will be studying in HCM city"],
                ["id" => 6, "vi" => "Lúc này tháng sau thì tôi đang làm bài thi Tiếng Anh", "expected" => "At this time next month, I will be doing an English exam"],
                ["id" => 7, "vi" => "Bà ấy sẽ đang đợi khi tôi đến vào ngày mai", "expected" => "She will be waiting when I arrive tomorrow"],
                ["id" => 8, "vi" => "Bữa tiệc sẽ bắt đầu lúc 10 giờ tối nay", "expected" => "The party will be starting at 10 PM tonight"],
                ["id" => 9, "vi" => "Cô ấy sẽ đang làm việc nhà lúc 5 giờ chiều nay đúng không", "expected" => "Will she be doing housework at 5 PM this afternoon?"],
                ["id" => 10, "vi" => "Mẹ tôi sẽ đang nấu ăn khi tôi về trưa mai", "expected" => "My mother will be cooking when I come home tomorrow noon"],
                ["id" => 11, "vi" => "Lúc 8 giờ tối mai, tôi và các bạn sẽ đang xem bộ phim Người nhện", "expected" => "At 8 PM tomorrow, my friends and I will be watching the Spider-Man movie"],
                ["id" => 12, "vi" => "Bạn sẽ đang đợi xe khi cô ấy đến lúc 7 giờ tối nay chứ ?", "expected" => "Will you be waiting for the bus when she arrives at 7 PM tonight?"],
                ["id" => 13, "vi" => "Thứ 2 tuần sau, chúng tôi sẽ đang ngồi trên xe buýt đến Hà Nội", "expected" => "Next Monday, we will be sitting on the bus to Hanoi"],
                ["id" => 14, "vi" => "Ngày mai khi bạn đến, họ sẽ đang chơi thể thao", "expected" => "Tomorrow when you arrive, they will be playing sports"],
                ["id" => 15, "vi" => "Gia đình tôi sẽ đi nghỉ mát tại thời điểm này tuần tới", "expected" => "My family will be going on vacation at this time next week"],
                ["id" => 16, "vi" => "Chiều nay, khi cô ấy đến ĐN thì tôi đang đi bơi với các bạn.", "expected" => "This afternoon, when she arrives in DN, I will be swimming with my friends"],
                ["id" => 17, "vi" => "Vào giữa trưa ngày mai tôi và vợ đang đi biển tại Vũng tàu", "expected" => "At noon tomorrow, my wife and I will be going to the beach in Vung Tau"],
                ["id" => 18, "vi" => "Bạn sẽ đang đi đâu lúc 8 giờ tối nay", "expected" => "Where will you be going at 8 PM tonight?"],
                ["id" => 19, "vi" => "Jim sẽ đang làm gì thời điểm này tháng sau ?", "expected" => "What will Jim be doing at this time next month?"],
                ["id" => 20, "vi" => "Ngày mai bạn sẽ đang học tiếng Anh lúc 8 giờ tối à ?", "expected" => "Will you be studying English at 8 PM tomorrow?"]
            ],

            // 11. Tương lai hoàn thành (5 câu)
            11 => [
                ["id" => 1, "vi" => "Họ sẽ hoàn thành việc xây ngôi nhà đó trước tháng 7 năm tới", "expected" => "They will have built that house by July next year"],
                ["id" => 2, "vi" => "Khi bạn quay lại, tôi sẽ đã viết xong lá thư này.", "expected" => "When you come back, I will have written this letter"],
                ["id" => 3, "vi" => "Tôi sẽ hoàn thành bài tập về nhà trước 3 giờ chiều mai.", "expected" => "I will have finished my homework before 3 PM tomorrow"],
                ["id" => 4, "vi" => "Cô ấy sẽ chưa dừng công việc trước khi bạn tới ngày mai.", "expected" => "She will not have stopped her work before you come tomorrow"],
                ["id" => 5, "vi" => "Bố mẹ bạn sẽ đã trở về Việt Nam trước kỳ nghỉ Tết chứ?", "expected" => "Will your parents have come back to Vietnam before Tet holiday?"]
            ],

            // 12. Chuyên Đề Câu Bị Động Toàn Tập (10 câu)
            12 => [
                ["id" => 1, "vi" => "Bố tôi tưới bông hoa này mỗi sáng (Chuyển sang Bị động)", "expected" => "This flower is watered by my father every morning"],
                ["id" => 2, "vi" => "John đã mời Fiona tới tiệc sinh nhật tối qua (Chuyển sang Bị động)", "expected" => "Fiona was invited to John's birthday party last night"],
                ["id" => 3, "vi" => "Mẹ cô ấy đang chuẩn bị bữa tối trong bếp (Chuyển sang Bị động)", "expected" => "The dinner is being prepared in the kitchen by her mother"],
                ["id" => 4, "vi" => "Chúng ta nên đánh răng 2 lần mỗi ngày (Chuyển sang Bị động)", "expected" => "Our teeth should be cleaned twice a day"],
                ["id" => 5, "vi" => "Các thầy cô giáo đã giải thích ngữ pháp tiếng Anh (Chuyển sang Bị động)", "expected" => "The English grammar has been explained by our teachers"],
                ["id" => 6, "vi" => "Một vài tài xế say rượu đã gây ra tai nạn ở thành phố này (Chuyển sang Bị động)", "expected" => "The accident in this city was caused by some drunk drivers"],
                ["id" => 7, "vi" => "Tom sẽ thăm Mary vào tháng tới (Chuyển sang Bị động)", "expected" => "Mary will be visited by Tom next month"],
                ["id" => 8, "vi" => "Người quản lý đã không gọi điện thoại cho thư ký sáng nay (Chuyển sang Bị động)", "expected" => "The secretary was not phoned by the manager this morning"],
                ["id" => 9, "vi" => "Các học sinh đã thảo luận vấn đề ô nhiễm từ tuần trước (Chuyển sang Bị động)", "expected" => "The pollution problems have been discussed by the students since last week"],
                ["id" => 10, "vi" => "Một số người sẽ phỏng vấn vị tân tổng thống trên truyền hình (Chuyển sang Bị động)", "expected" => "The new president will be interviewed on TV"]
            ],

            // 13. Chuyên đề Phát Âm Đuôi -ed và -s/es Thực Hành (10 câu)
            13 => [
                ["id" => 1, "vi" => "Đồng hồ này có đuôi kết thúc bằng âm vô thanh /tʃ/", "expected" => "This watch ends with the voiceless sound"],
                ["id" => 2, "vi" => "Cô ấy đã đếm xong các con số ngày hôm qua", "expected" => "She counted all the numbers yesterday"],
                ["id" => 3, "vi" => "Chúng tôi đã ở lại khách sạn trong 3 ngày", "expected" => "We stayed at the hotel for three days"],
                ["id" => 4, "vi" => "Họ cần thêm thời gian để hoàn thành bài tập", "expected" => "They need more time to finish the exercise"],
                ["id" => 5, "vi" => "Bác sĩ đã gọi cho bệnh nhân sáng nay", "expected" => "The doctor called the patient this morning"],
                ["id" => 6, "vi" => "Kế hoạch này có vẻ rất khả thi", "expected" => "This plan seems very feasible"],
                ["id" => 7, "vi" => "Họ đã lên kế hoạch cho chuyến đi từ tháng trước", "expected" => "They planned for the trip since last month"],
                ["id" => 8, "vi" => "Tôi đã chờ xe buýt trong 20 phút", "expected" => "I waited for the bus for 20 minutes"],
                ["id" => 9, "vi" => "Anh ấy thường rửa xe vào cuối tuần", "expected" => "He usually washes his car on weekends"],
                ["id" => 10, "vi" => "Chúng tôi đã đi bộ dọc bờ biển chiều nay", "expected" => "We walked along the beach this afternoon"]
            ]
        ];

        foreach ($allTranslations as $orderIndex => $translations) {
            $grammar = Grammar::where('order_index', $orderIndex)->first();
            if (!$grammar) {
                $grammar = Grammar::find($orderIndex);
            }
            if ($grammar) {
                $currentExercises = $grammar->exercises_json ?? [];
                $currentExercises['translations'] = $translations;

                // Add Verb Conjugations & Sentence Buildings from PDF if not already present
                if (empty($currentExercises['verb_conjugations'])) {
                    if ($orderIndex == 1) {
                        $currentExercises['verb_conjugations'] = [
                            ['id' => 1, 'prompt' => 'My father always (make) _________ dinner.', 'verb' => 'make', 'expected' => 'makes', 'explain' => 'Chủ ngữ ngôi thứ 3 số ít (My father) nên động từ thêm -s/es: makes.'],
                            ['id' => 2, 'prompt' => 'Ruth (not eat) _________ eggs; they (make) her ill.', 'verb' => 'not eat', 'expected' => "doesn't eat", 'explain' => "Phủ định thì hiện tại đơn với ngôi thứ 3 số ít dùng doesn't + V nguyên mẫu."],
                            ['id' => 3, 'prompt' => 'Water (boil) _________ at 100 degrees Celsius.', 'verb' => 'boil', 'expected' => 'boils', 'explain' => 'Sự thật hiển nhiên, chân lý tự nhiên chia thì hiện tại đơn: boils.'],
                            ['id' => 4, 'prompt' => 'The train (leave) _________ at 7:30 a.m tomorrow.', 'verb' => 'leave', 'expected' => 'leaves', 'explain' => 'Lịch trình tàu xe cố định chia thì hiện tại đơn: leaves.'],
                            ['id' => 5, 'prompt' => 'They (not live) _________ in a big house.', 'verb' => 'not live', 'expected' => "don't live", 'explain' => "Chủ ngữ They số nhiều dùng trợ động từ don't."],
                        ];
                    } elseif ($orderIndex == 2) {
                        $currentExercises['verb_conjugations'] = [
                            ['id' => 1, 'prompt' => 'Listen! The baby (cry) _________.', 'verb' => 'cry', 'expected' => 'is crying', 'explain' => 'Có dấu hiệu "Listen!", hành động đang diễn ra nên dùng is crying.'],
                            ['id' => 2, 'prompt' => 'Look! Son (play) _________ piano and Duy (sing) _________.', 'verb' => 'play; sing', 'expected' => 'is playing and is singing', 'explain' => 'Có dấu hiệu "Look!", cả hai hành động cùng đang diễn ra song song.'],
                            ['id' => 3, 'prompt' => 'At the moment, I (read) _________ an interesting book.', 'verb' => 'read', 'expected' => 'am reading', 'explain' => 'Có trạng từ "at the moment", chia thì HTTD: am reading.'],
                            ['id' => 4, 'prompt' => 'Why you (run) _________ so fast now?', 'verb' => 'run', 'expected' => 'are you running', 'explain' => 'Câu hỏi thì hiện tại tiếp diễn đảo Are lên trước chủ ngữ you.'],
                        ];
                    } elseif ($orderIndex == 3) {
                        $currentExercises['verb_conjugations'] = [
                            ['id' => 1, 'prompt' => 'I (see) _________ this movie three times.', 'verb' => 'see', 'expected' => 'have seen', 'explain' => 'Kinh nghiệm trải nghiệm dùng have seen.'],
                            ['id' => 2, 'prompt' => 'She (live) _________ in Hanoi since 2015.', 'verb' => 'live', 'expected' => 'has lived', 'explain' => 'Hành động bắt đầu từ quá khứ kéo dài đến hiện tại (since 2015): has lived.'],
                            ['id' => 3, 'prompt' => 'They (not finish) _________ their homework yet.', 'verb' => 'not finish', 'expected' => "haven't finished", 'explain' => 'Có từ yet ở cuối câu phủ định HTHT: haven\'t finished.'],
                        ];
                    }
                }

                if (empty($currentExercises['sentence_buildings'])) {
                    if ($orderIndex == 1) {
                        $currentExercises['sentence_buildings'] = [
                            ['id' => 1, 'prompt' => 'he / not / be / a journalist', 'expected' => "He isn't a journalist", 'explain' => "Dạng phủ định của tobe với He là isn't hoặc is not."],
                            ['id' => 2, 'prompt' => 'They / in Tokyo?', 'expected' => 'Are they in Tokyo?', 'explain' => 'Câu hỏi nghi vấn tobe đảo Are lên trước chủ ngữ They.'],
                            ['id' => 3, 'prompt' => 'Julie / at home?', 'expected' => 'Is Julie at home?', 'explain' => 'Câu hỏi nghi vấn tobe đảo Is lên trước Julie.'],
                            ['id' => 4, 'prompt' => 'The children / at school?', 'expected' => 'Are the children at school?', 'explain' => 'The children là danh từ số nhiều nên dùng Are.'],
                            ['id' => 5, 'prompt' => 'How / you / go to school?', 'expected' => 'How do you go to school?', 'explain' => 'Câu hỏi Wh- với you dùng trợ động từ do: How do you go to school?'],
                        ];
                    } elseif ($orderIndex == 2) {
                        $currentExercises['sentence_buildings'] = [
                            ['id' => 1, 'prompt' => 'My/ father/ water/ some plants/ the garden.', 'expected' => 'My father is watering some plants in the garden', 'explain' => 'Chủ ngữ số ít My father + is watering.'],
                            ['id' => 2, 'prompt' => 'My/ mother/ clean/ floor/.', 'expected' => 'My mother is cleaning the floor', 'explain' => 'Chủ ngữ My mother + is cleaning the floor.'],
                            ['id' => 3, 'prompt' => 'Mary/ have/ lunch/ her/ friends/ a/ restaurant.', 'expected' => 'Mary is having lunch with her friends in a restaurant', 'explain' => 'Mary is having lunch with her friends in a restaurant.'],
                            ['id' => 4, 'prompt' => 'They/ ask/ a/ man/ about/ the/ way/ the/ railway station.', 'expected' => 'They are asking a man about the way to the railway station', 'explain' => 'They are asking a man about the way to the railway station.'],
                        ];
                    }
                }

                $grammar->exercises_json = $currentExercises;
                $grammar->save();
                echo "Updated Unit {$orderIndex} ({$grammar->title}) with " . count($translations) . " translations.\n";
            }
        }
    }
}
