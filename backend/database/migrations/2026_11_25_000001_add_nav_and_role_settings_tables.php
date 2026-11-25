<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'more_nav_order_json')) {
                $table->json('more_nav_order_json')->nullable()->after('header_nav_json');
            }
            if (!Schema::hasColumn('users', 'notebook_words_json')) {
                $table->longText('notebook_words_json')->nullable()->after('more_nav_order_json');
            }
        });

        if (!Schema::hasTable('app_settings')) {
            Schema::create('app_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->longText('value')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
        Schema::table('users', function (Blueprint $table) {
            $cols = array_values(array_filter(['more_nav_order_json', 'notebook_words_json'], fn ($c) => Schema::hasColumn('users', $c)));
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
