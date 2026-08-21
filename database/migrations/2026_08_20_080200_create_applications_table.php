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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('code_student', 13)->unique();

            // Personal information
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('gender', 20);
            $table->date('dob');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('village', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('province', 100)->nullable();

            // Education background
            $table->string('previous_school')->nullable();
            $table->string('accommodation_type', 20);

            // Registration info
            $table->foreignId('major_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();

            // Documents (storage paths on the `public` disk)
            $table->string('photo');
            $table->string('certificate')->nullable();
            $table->string('transcript')->nullable();

            // Tracking
            $table->string('status', 20)->default('new');
            $table->string('source_page', 50)->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
