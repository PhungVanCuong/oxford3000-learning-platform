<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Enhance phrasal_verbs table
        Schema::table('phrasal_verbs', function (Blueprint $table) {
            $table->string('type', 50)->default('phrasal_verb')->change();
            if (!Schema::hasColumn('phrasal_verbs', 'category')) {
                $table->string('category', 50)->default('phrasal_verb')->after('type');
            }
            if (!Schema::hasColumn('phrasal_verbs', 'part_number')) {
                $table->integer('part_number')->default(1)->after('category');
            }
        });

        // 2. Add header_nav_json to users table
        if (!Schema::hasColumn('users', 'header_nav_json')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('header_nav_json')->nullable()->after('theme_mode');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'header_nav_json')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('header_nav_json');
            });
        }
        Schema::table('phrasal_verbs', function (Blueprint $table) {
            if (Schema::hasColumn('phrasal_verbs', 'part_number')) {
                $table->dropColumn('part_number');
            }
            if (Schema::hasColumn('phrasal_verbs', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
