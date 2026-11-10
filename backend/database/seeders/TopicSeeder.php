<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Topic::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $topics = [
            ['name' => 'Gia đình & Mối quan hệ', 'slug' => 'family-and-relationships', 'icon' => 'bi-people', 'description' => 'Từ vựng và hội thoại về các thành viên gia đình, bạn bè và tình cảm.'],
            ['name' => 'Công việc & Nghề nghiệp', 'slug' => 'jobs-and-careers', 'icon' => 'bi-briefcase', 'description' => 'Môi trường công sở, phỏng vấn xin việc, đàm phán và thăng tiến.'],
            ['name' => 'Học thuật & Giáo dục', 'slug' => 'academic-and-education', 'icon' => 'bi-mortarboard', 'description' => 'Trường học, đại học, nghiên cứu, thi cử và bằng cấp quốc tế.'],
            ['name' => 'Du lịch & Khách sạn', 'slug' => 'travel-and-hospitality', 'icon' => 'bi-airplane', 'description' => 'Sân bay, đặt phòng khách sạn, khám phá văn hóa và điểm tham quan.'],
            ['name' => 'Công nghệ & Kỹ thuật số', 'slug' => 'technology-and-digital', 'icon' => 'bi-laptop', 'description' => 'Máy tính, phần mềm, trí tuệ nhân tạo, internet và mạng xã hội.'],
            ['name' => 'Đời sống & Nhà cửa', 'slug' => 'home-and-daily-life', 'icon' => 'bi-house-door', 'description' => 'Nội thất, đồ gia dụng, các hoạt động sinh hoạt và thói quen hàng ngày.'],
            ['name' => 'Ẩm thực & Nhà hàng', 'slug' => 'food-and-dining', 'icon' => 'bi-cup-hot', 'description' => 'Món ăn, công thức nấu nướng, đồ uống và gọi món tại nhà hàng.'],
            ['name' => 'Sức khỏe & Y tế', 'slug' => 'health-and-medicine', 'icon' => 'bi-heart-pulse', 'description' => 'Bệnh viện, triệu chứng bệnh, tập thể dục, dinh dưỡng và lối sống lành mạnh.'],
            ['name' => 'Mua sắm & Tiêu dùng', 'slug' => 'shopping-and-consumer', 'icon' => 'bi-bag', 'description' => 'Cửa hàng, siêu thị, giảm giá, thanh toán và trải nghiệm khách hàng.'],
            ['name' => 'Giao thông & Đi lại', 'slug' => 'transport-and-commuting', 'icon' => 'bi-car-front', 'description' => 'Phương tiện giao thông, biển báo, lộ trình và chỉ đường.'],
            ['name' => 'Môi trường & Thiên nhiên', 'slug' => 'environment-and-nature', 'icon' => 'bi-tree', 'description' => 'Khí hậu, bảo vệ môi trường, động thực vật và cảnh quan tự nhiên.'],
            ['name' => 'Cảm xúc & Tính cách', 'slug' => 'emotions-and-personality', 'icon' => 'bi-emoji-smile', 'description' => 'Mô tả tâm trạng, tính cách, phản ứng tâm lý và thái độ sống.'],
            ['name' => 'Kinh doanh & Tài chính', 'slug' => 'business-and-finance', 'icon' => 'bi-cash-coin', 'description' => 'Ngân hàng, đầu tư, thị trường tài chính, khởi nghiệp và kinh tế.'],
            ['name' => 'Nghệ thuật & Giải trí', 'slug' => 'arts-and-entertainment', 'icon' => 'bi-film', 'description' => 'Âm nhạc, phim ảnh, sân khấu kịch, hội họa và bảo tàng.'],
            ['name' => 'Khoa học & Vũ trụ', 'slug' => 'science-and-space', 'icon' => 'bi-stars', 'description' => 'Vật lý, hóa học, sinh học, thiên văn và các phát minh vĩ đại.'],
            ['name' => 'Pháp luật & Xã hội', 'slug' => 'law-and-society', 'icon' => 'bi-bank', 'description' => 'Hệ thống pháp lý, quyền công dân, chính sách xã hội và cộng đồng.'],
            ['name' => 'Truyền thông & Báo chí', 'slug' => 'media-and-journalism', 'icon' => 'bi-newspaper', 'description' => 'Tin tức thời sự, phát thanh truyền hình, xuất bản và truyền thông số.'],
            ['name' => 'Thời trang & Phong cách', 'slug' => 'fashion-and-style', 'icon' => 'bi-sunglasses', 'description' => 'Trang phục, phụ kiện thời trang, chất liệu vải và xu hướng thẩm mỹ.'],
            ['name' => 'Thời tiết & Khí hậu', 'slug' => 'weather-and-climate', 'icon' => 'bi-cloud-sun', 'description' => 'Dự báo thời tiết, các mùa trong năm, nhiệt độ và hiện tượng tự nhiên.'],
            ['name' => 'Lịch sử & Văn hóa', 'slug' => 'history-and-culture', 'icon' => 'bi-compass', 'description' => 'Sự kiện lịch sử, phong tục truyền thống, di sản và lễ hội dân gian.'],
            ['name' => 'Thể thao & Trò chơi', 'slug' => 'sports-and-games', 'icon' => 'bi-trophy', 'description' => 'Bóng đá, bơi lội, điền kinh, luật thi đấu và thế vận hội Olympic.'],
            ['name' => 'Giao tiếp hàng ngày', 'slug' => 'daily-conversations', 'icon' => 'bi-chat-dots', 'description' => 'Chào hỏi, cảm ơn, xin lỗi, đề nghị giúp đỡ và xã giao lịch sự.'],
            ['name' => 'Thành thị & Đô thị', 'slug' => 'cities-and-urban', 'icon' => 'bi-buildings', 'description' => 'Cơ sở hạ tầng, khu chung cư, công viên, đường phố và dịch vụ đô thị.'],
            ['name' => 'Kỹ năng mềm', 'slug' => 'soft-skills', 'icon' => 'bi-lightbulb', 'description' => 'Lãnh đạo, làm việc nhóm, giải quyết vấn đề và thuyết trình tự tin.'],
            ['name' => 'Mục tiêu & Tương lai', 'slug' => 'goals-and-future', 'icon' => 'bi-flag', 'description' => 'Định hướng cuộc đời, lập kế hoạch cá nhân, ước mơ và sự thành công.'],
        ];

        foreach ($topics as $idx => $t) {
            Topic::create([
                'name' => $t['name'],
                'slug' => $t['slug'],
                'icon' => $t['icon'],
                'description' => $t['description'],
                'order_index' => $idx + 1,
            ]);
        }
    }
}
