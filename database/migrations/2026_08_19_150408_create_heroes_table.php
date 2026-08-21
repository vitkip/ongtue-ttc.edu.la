<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->string('image_url', 500);
            $table->string('badge_text', 255)->nullable();
            $table->string('title_line1', 500);
            $table->string('title_line2', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('primary_button_text', 255)->nullable();
            $table->string('secondary_button_text', 255)->nullable();
            $table->string('secondary_button_link', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heroes');
    }
};
