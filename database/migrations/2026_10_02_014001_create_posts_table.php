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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); #ID for the owner of the post
            # for both owner and seeker
            $table->string('title'); 
            $table->text('description'); #details

            // for Only Owner nullablr: allow to seer can add
            $table->string('company_name')->nullable(); 
            $table->string('location')->nullable();
            $table->string('wage')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // for seeker
            $table->string('desired_job')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
