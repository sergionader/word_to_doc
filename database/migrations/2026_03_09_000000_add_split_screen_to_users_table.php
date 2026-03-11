<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('split_screen_enabled')->default(false)->after('pinned_folders');
            $table->string('split_screen_path')->nullable()->after('split_screen_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['split_screen_enabled', 'split_screen_path']);
        });
    }
};
