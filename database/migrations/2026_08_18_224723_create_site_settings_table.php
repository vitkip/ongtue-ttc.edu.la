<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key')->unique();
            $table->text('setting_value')->nullable();
            $table->string('setting_type', 20)->default('text');
            $table->string('group_name', 50);
            $table->string('label');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['group_name', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
