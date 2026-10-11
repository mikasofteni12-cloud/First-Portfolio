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
        Schema::table('posts', function (Blueprint $table) {
            //職種
            $table->foreignId('category_id')->nullable()->contained()->onDelete('set null');
            // location
            $table->string('work_style')->default('On-site');
            // 雇用形態
            $table->string('employment_type')->default('Full-time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'work_style', 'employment_type']);
        });
    }
};
