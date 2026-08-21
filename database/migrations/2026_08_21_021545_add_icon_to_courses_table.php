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
            $table->string('icon', 50)->nullable()->after('fee_type');
        });

        // Some rows previously had the icon name typed by hand into duration_label
        // (e.g. "payments 4 ປີ | ປະລິນຍາຕີ"). Move that into the new icon column.
        DB::table('courses')->orderBy('id')->each(function ($course) {
            $icon = $course->fee_type === 'scholarship' ? 'card_giftcard' : 'payments';
            $duration = $course->duration_label;

            if (is_string($duration) && preg_match('/^([a-z_]+)\s+(.*)$/', $duration, $matches)) {
                $icon = $matches[1];
                $duration = $matches[2];
            }

            DB::table('courses')->where('id', $course->id)->update([
                'icon' => $icon,
                'duration_label' => $duration,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
