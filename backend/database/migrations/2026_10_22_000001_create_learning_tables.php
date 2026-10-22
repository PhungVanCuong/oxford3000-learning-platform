<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Topics (100% unlocked - no lock fields)
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // bi-briefcase, bi-heart, bi-laptop, etc.
            $table->integer('order_index')->default(1);
            $table->timestamps();
        });

        // 2. Vocabularies (3000+ words with continuous ID sequence 1..3000 for dynamic day chunking)
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->string('word')->index();
            $table->string('phonetic_us')->nullable();
            $table->string('part_of_speech')->nullable();
            $table->text('meaning_vi');
            $table->text('example_en')->nullable();
            $table->text('example_vi')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('cefr_level', 10)->default('A1')->index();
            $table->timestamps();
        });

        // 3. User Vocabulary Progress (Spaced Repetition System)
        Schema::create('user_vocabulary_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vocabulary_id')->constrained('vocabularies')->onDelete('cascade');
            $table->enum('status', ['unlearned', 'learning', 'mastered'])->default('unlearned');
            $table->integer('correct_count')->default(0);
            $table->integer('wrong_count')->default(0);
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'vocabulary_id']);
            $table->index(['user_id', 'status']);
        });

        // 4. Grammars
        Schema::create('grammars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->string('title');
            $table->string('level', 10)->default('A1');
            $table->longText('theory_html');
            $table->json('examples_json')->nullable();
            $table->integer('order_index')->default(1);
            $table->timestamps();
        });

        // 5. Grammar Questions
        Schema::create('grammar_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grammar_id')->constrained('grammars')->onDelete('cascade');
            $table->enum('type', ['multiple_choice', 'fill_in_blank'])->default('multiple_choice');
            $table->text('question');
            $table->json('options_json')->nullable();
            $table->string('correct_answer');
            $table->text('explanation')->nullable();
            $table->integer('order_index')->default(1);
            $table->timestamps();
        });

        // 6. User Grammar Progress
        Schema::create('user_grammar_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('grammar_id')->constrained('grammars')->onDelete('cascade');
            $table->boolean('is_completed')->default(false);
            $table->integer('best_score')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'grammar_id']);
        });

        // 7. IPA Sounds (44 sounds)
        Schema::create('ipa_sounds', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 20); // /i:/, /æ/, etc.
            $table->enum('category', ['monophthongs', 'diphthongs', 'consonants']);
            $table->enum('voiced_type', ['voiced', 'unvoiced'])->nullable();
            $table->text('mouth_guide_vi');
            $table->string('video_or_image_url')->nullable();
            $table->json('examples_json')->nullable();
            $table->integer('order_index')->default(1);
            $table->timestamps();
        });

        // 8. Learning Tips
        Schema::create('learning_tips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category'); // Mẹo nhớ từ vựng, Mẹo phát âm, Mẹo làm bài trắc nghiệm, Mẹo ngữ pháp
            $table->longText('content_html');
            $table->integer('order_index')->default(1);
            $table->timestamps();
        });

        // 9. Quiz Histories
        Schema::create('quiz_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('quiz_type', ['daily_vocab', 'multi_day_review', 'topic_vocab', 'grammar']);
            $table->string('reference_info'); // VD: "Ngày 1", "Ngày 1, 5, 7", "Chủ đề: Gia đình"
            $table->integer('total_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->float('accuracy_percent')->default(0);
            $table->integer('xp_earned')->default(0);
            $table->json('details_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_histories');
        Schema::dropIfExists('learning_tips');
        Schema::dropIfExists('ipa_sounds');
        Schema::dropIfExists('user_grammar_progress');
        Schema::dropIfExists('grammar_questions');
        Schema::dropIfExists('grammars');
        Schema::dropIfExists('user_vocabulary_progress');
        Schema::dropIfExists('vocabularies');
        Schema::dropIfExists('topics');
    }
};
