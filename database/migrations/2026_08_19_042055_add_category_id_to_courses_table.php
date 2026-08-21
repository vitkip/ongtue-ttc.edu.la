<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')
                ->constrained('course_categories')->nullOnDelete();
        });

        $categories = [
            ['name' => 'ພາສາບາລີ (Pali Language)', 'slug' => 'pali', 'sort_order' => 1],
            ['name' => 'ປັດຊະຍາພຸດທະສາສະໜາ (Buddhist Philosophy)', 'slug' => 'philosophy', 'sort_order' => 2],
            ['name' => 'ພຣະທຳວິໄນ (Dharma Studies)', 'slug' => 'dharma', 'sort_order' => 3],
            ['name' => 'ວິຊາສາມັນ (General Education)', 'slug' => 'general', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            $id = DB::table('course_categories')->insertGetId([
                ...$category,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('courses')->where('category', $category['slug'])->update(['category_id' => $id]);
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('category', 30)->nullable()->after('id');
        });

        foreach (DB::table('course_categories')->get() as $category) {
            DB::table('courses')->where('category_id', $category->id)->update(['category' => $category->slug]);
        }

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->index('category');
        });
    }
};
