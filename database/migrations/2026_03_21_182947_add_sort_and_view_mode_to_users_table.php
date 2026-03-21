<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('view_mode', 10)->default('list');
            $table->string('sort_by', 20)->default('name');
            $table->string('sort_direction', 4)->default('asc');
            $table->string('right_sort_by', 20)->default('name');
            $table->string('right_sort_direction', 4)->default('asc');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['view_mode', 'sort_by', 'sort_direction', 'right_sort_by', 'right_sort_direction']);
        });
    }
};
