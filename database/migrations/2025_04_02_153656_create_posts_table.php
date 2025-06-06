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
            $table->string('title');
            $table->text('cover');
            $table->dateTime('date_publish')->nullable();
            $table->string('slug');
            $table->string('description');
            $table->text('body');
            $table->string('status');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('category_id')->constrained();
            $table->unique(['title', 'user_id']);
            $table->unique(['slug', 'user_id']);
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
