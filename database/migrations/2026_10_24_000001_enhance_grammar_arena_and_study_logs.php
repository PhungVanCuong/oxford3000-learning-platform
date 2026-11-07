<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Enhance grammars table
        Schema::table('grammars', function (Blueprint $table) {
            if (!Schema::hasColumn('grammars', 'slug')) {
                $table->string('slug')->nullable()->after('title');
            }
            if (!Schema::hasColumn('grammars', 'summary')) {
                $table->text('summary')->nullable()->after('level');
            }
            if (!Schema::hasColumn('grammars', 'active_passive_html')) {
                $table->longText('active_passive_html')->nullable()->after('theory_html');
            }
            if (!Schema::hasColumn('grammars', 'pronunciation_notes')) {
                $table->text('pronunciation_notes')->nullable()->after('active_passive_html');
            }
            if (!Schema::hasColumn('grammars', 'exercises_json')) {
                $table->longText('exercises_json')->nullable()->after('examples_json');
            }
            if (!Schema::hasColumn('grammars', 'badge_color')) {
                $table->string('badge_color')->nullable()->default('#00b074')->after('order_index');
            }
            if (!Schema::hasColumn('grammars', 'icon')) {
                $table->string('icon')->nullable()->default('bi-journal-bookmark-fill')->after('badge_color');
            }
        });

        // 2. Quiz Rooms (Classroom Quiz Arena)
        if (!Schema::hasTable('quiz_rooms')) {
            Schema::create('quiz_rooms', function (Blueprint $table) {
                $table->id();
                $table->string('room_code', 20)->unique();
                $table->string('title');
                $table->foreignId('host_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->enum('status', ['waiting', 'playing', 'finished'])->default('waiting');
                $table->string('category', 50)->default('vocab_3000'); // vocab_3000, curriculum, grammar, custom
                $table->string('password')->nullable();
                $table->integer('max_players')->default(50);
                $table->integer('time_per_question')->default(15);
                $table->integer('xp_per_question')->default(15);
                $table->longText('questions_data')->nullable(); // JSON list of questions
                $table->timestamp('expires_at')->nullable();
                $table->integer('current_question_index')->default(0);
                $table->timestamps();
            });
        }

        // 3. Quiz Room Participants
        if (!Schema::hasTable('quiz_room_participants')) {
            Schema::create('quiz_room_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('room_id')->constrained('quiz_rooms')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->integer('score')->default(0);
                $table->integer('correct_count')->default(0);
                $table->integer('total_time_ms')->default(0);
                $table->json('answers_json')->nullable();
                $table->timestamps();

                $table->unique(['room_id', 'user_id']);
            });
        }

        // 4. User Study Logs (Activity, Heatmap, Weekly Productivity)
        if (!Schema::hasTable('user_study_logs')) {
            Schema::create('user_study_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->date('study_date')->index();
                $table->integer('minutes_spent')->default(0);
                $table->integer('words_learned')->default(0);
                $table->integer('xp_earned')->default(0);
                $table->integer('exercises_done')->default(0);
                $table->timestamps();

                $table->unique(['user_id', 'study_date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_study_logs');
        Schema::dropIfExists('quiz_room_participants');
        Schema::dropIfExists('quiz_rooms');
        Schema::table('grammars', function (Blueprint $table) {
            $table->dropColumn([
                'slug', 'summary', 'active_passive_html', 'pronunciation_notes', 
                'exercises_json', 'badge_color', 'icon'
            ]);
        });
    }
};
