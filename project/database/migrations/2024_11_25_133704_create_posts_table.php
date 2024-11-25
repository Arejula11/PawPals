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
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->timestamp('creation_date');
            $table->text('description'); 
            $table->foreignId('user_id') 
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('post_picture_id') 
                ->nullable()
                ->constrained('pictures')
                ->onDelete('set null');    
        });

        Schema::create('post_like', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')
                ->constrained('post')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')
                ->constrained('post')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('post_like');
        Schema::dropIfExists('post');
    }
};
