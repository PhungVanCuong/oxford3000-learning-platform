<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $commonPassword = Hash::make('123456');

        $users = [
            // 1. Main Student Account (Cường Phùng)
            [
                'name' => 'Cường Phùng',
                'email' => 'cuongphung1810@gmail.com',
                'password' => $commonPassword,
                'role' => 'admin', // Full access
                'birth_date' => '2005-10-18',
                'xp_points' => 450,
                'streak_days' => 3,
                'last_study_date' => now()->toDateString(),
                'words_per_day' => 15,
                'theme_mode' => 'light',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=CuongPhung',
            ],
            // 2. Admin Account
            [
                'name' => 'Quản Trị Viên',
                'email' => 'admin@quizlet3000.com',
                'password' => $commonPassword,
                'role' => 'admin',
                'birth_date' => '1998-05-20',
                'xp_points' => 1250,
                'streak_days' => 14,
                'last_study_date' => now()->toDateString(),
                'words_per_day' => 20,
                'theme_mode' => 'dark',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=AdminOxford',
            ],
            // 3-10. Sample Student Accounts
            [
                'name' => 'Nguyễn Minh Anh',
                'email' => 'user1@gmail.com',
                'password' => $commonPassword,
                'role' => 'user',
                'birth_date' => '2004-03-12',
                'xp_points' => 880,
                'streak_days' => 7,
                'last_study_date' => now()->subDay()->toDateString(),
                'words_per_day' => 15,
                'theme_mode' => 'light',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=MinhAnh',
            ],
            [
                'name' => 'Trần Hoàng Nam',
                'email' => 'user2@gmail.com',
                'password' => $commonPassword,
                'role' => 'user',
                'birth_date' => '2003-08-25',
                'xp_points' => 720,
                'streak_days' => 5,
                'last_study_date' => now()->toDateString(),
                'words_per_day' => 20,
                'theme_mode' => 'light',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=HoangNam',
            ],
            [
                'name' => 'Lê Thu Trang',
                'email' => 'user3@gmail.com',
                'password' => $commonPassword,
                'role' => 'user',
                'birth_date' => '2005-01-30',
                'xp_points' => 610,
                'streak_days' => 4,
                'last_study_date' => now()->toDateString(),
                'words_per_day' => 15,
                'theme_mode' => 'dark',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=ThuTrang',
            ],
            [
                'name' => 'Phạm Quốc Bảo',
                'email' => 'user4@gmail.com',
                'password' => $commonPassword,
                'role' => 'user',
                'birth_date' => '2002-11-15',
                'xp_points' => 530,
                'streak_days' => 3,
                'last_study_date' => now()->subDays(2)->toDateString(),
                'words_per_day' => 25,
                'theme_mode' => 'light',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=QuocBao',
            ],
            [
                'name' => 'Võ Thị Mai',
                'email' => 'user5@gmail.com',
                'password' => $commonPassword,
                'role' => 'user',
                'birth_date' => '2006-07-04',
                'xp_points' => 390,
                'streak_days' => 2,
                'last_study_date' => now()->toDateString(),
                'words_per_day' => 15,
                'theme_mode' => 'light',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=ThiMai',
            ],
            [
                'name' => 'Đặng Tuấn Kiệt',
                'email' => 'user6@gmail.com',
                'password' => $commonPassword,
                'role' => 'user',
                'birth_date' => '2004-09-19',
                'xp_points' => 260,
                'streak_days' => 2,
                'last_study_date' => now()->subDay()->toDateString(),
                'words_per_day' => 10,
                'theme_mode' => 'dark',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=TuanKiet',
            ],
            [
                'name' => 'Hoàng Ngọc Ánh',
                'email' => 'user7@gmail.com',
                'password' => $commonPassword,
                'role' => 'user',
                'birth_date' => '2005-12-08',
                'xp_points' => 150,
                'streak_days' => 1,
                'last_study_date' => now()->toDateString(),
                'words_per_day' => 15,
                'theme_mode' => 'light',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=NgocAnh',
            ],
            [
                'name' => 'Bùi Đức Huy',
                'email' => 'user8@gmail.com',
                'password' => $commonPassword,
                'role' => 'user',
                'birth_date' => '2003-04-16',
                'xp_points' => 80,
                'streak_days' => 1,
                'last_study_date' => now()->subDays(3)->toDateString(),
                'words_per_day' => 15,
                'theme_mode' => 'light',
                'avatar_url' => 'https://api.dicebear.com/7.x/bottts/svg?seed=DucHuy',
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
