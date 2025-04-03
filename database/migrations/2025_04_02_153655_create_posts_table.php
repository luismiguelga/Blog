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
            $table->text('cover')->nullable();
            $table->dateTime('date_publish');
            $table->string('slug')->nullable();
            $table->string('description');
            $table->text('body');
            $table->string('status');
            $table->unsignedInteger('user_id')->constrained();
            $table->unsignedInteger('category_id')->constrained();
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
