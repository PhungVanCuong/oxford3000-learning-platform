<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Phrasal Verbs & Collocations
        Schema::create('phrasal_verbs', function (Blueprint $table) {
            $table->id();
            $table->string('phrase')->index();
            $table->enum('type', ['phrasal_verb', 'collocation', 'idiom'])->default('phrasal_verb');
            $table->string('meaning_vi');
            $table->text('example_en')->nullable();
            $table->text('example_vi')->nullable();
            $table->json('component_words_json')->nullable(); // Array of single words in this phrase
            $table->string('cefr_level', 10)->default('B1');
            $table->timestamps();
        });

        // 2. User Phrase Progress
        Schema::create('user_phrase_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('phrase_id')->constrained('phrasal_verbs')->onDelete('cascade');
            $table->enum('status', ['unlearned', 'learning', 'mastered'])->default('unlearned');
            $table->integer('correct_count')->default(0);
            $table->timestamp('last_reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'phrase_id']);
        });

        // 3. Add permissions_json to users if not present
        if (!Schema::hasColumn('users', 'permissions_json')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('permissions_json')->nullable()->after('role');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_phrase_progress');
        Schema::dropIfExists('phrasal_verbs');
        if (Schema::hasColumn('users', 'permissions_json')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('permissions_json');
            });
        }
    }
};
