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
        Schema::create('curriculum_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_num')->unique();
            $table->string('title');
            $table->string('title_vi');
            $table->string('slug')->unique();
            $table->string('icon')->default('bi-journal-bookmark-fill');
            $table->string('color')->default('#00b074');
            $table->text('description')->nullable();
            $table->string('csv_filename')->nullable();
            $table->unsignedInteger('total_terms')->default(200);
            $table->unsignedInteger('total_stages')->default(10);
            $table->timestamps();
        });

        Schema::create('curriculum_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('curriculum_topics')->onDelete('cascade');
            $table->unsignedInteger('order_in_topic'); // 1..200
            $table->unsignedTinyInteger('stage_num'); // 1..10
            $table->string('term');
            $table->text('definition');
            $table->string('part_of_speech')->nullable();
            $table->text('pattern')->nullable();
            $table->text('examples')->nullable();
            $table->string('audio_url')->nullable();
            $table->timestamps();

            $table->index(['topic_id', 'stage_num']);
            $table->index('term');
        });

        Schema::create('curriculum_user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('curriculum_topics')->onDelete('cascade');
            $table->foreignId('term_id')->constrained('curriculum_terms')->onDelete('cascade');
            $table->enum('status', ['learning', 'remembered'])->default('learning');
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'term_id']);
            $table->index(['user_id', 'topic_id', 'status']);
        });

        Schema::create('curriculum_stage_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained('curriculum_topics')->onDelete('cascade');
            $table->unsignedTinyInteger('stage_num');
            $table->boolean('is_completed')->default(false);
            $table->unsignedInteger('score')->default(0);
            $table->unsignedInteger('total_questions')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'topic_id', 'stage_num']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_stage_progress');
        Schema::dropIfExists('curriculum_user_progress');
        Schema::dropIfExists('curriculum_terms');
        Schema::dropIfExists('curriculum_topics');
    }
};
