<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            if (!Schema::hasColumn('applications', 'last_edited_at')) {
                $table->timestamp('last_edited_at')->nullable()->after('admin_notes');
            }

            if (!Schema::hasColumn('applications', 'last_edited_by')) {
                $table->string('last_edited_by')->nullable()->after('last_edited_at');
            }

            if (!Schema::hasColumn('applications', 'edit_count')) {
                $table->unsignedInteger('edit_count')->default(0)->after('last_edited_by');
            }

            if (!Schema::hasColumn('applications', 'applicant_notes')) {
                $table->text('applicant_notes')->nullable()->after('edit_count');
            }
        });

        Schema::table('programs', function (Blueprint $table) {
            if (!Schema::hasColumn('programs', 'has_board_exam')) {
                $table->boolean('has_board_exam')->default(false)->after('is_active');
            }
        });

        DB::table('programs')
            ->whereIn('code', ['BSA', 'BEED', 'BSED-ENG'])
            ->update(['has_board_exam' => DB::raw($this->trueExpression())]);
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            foreach (['last_edited_at', 'last_edited_by', 'edit_count', 'applicant_notes'] as $column) {
                if (Schema::hasColumn('applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('programs', function (Blueprint $table) {
            if (Schema::hasColumn('programs', 'has_board_exam')) {
                $table->dropColumn('has_board_exam');
            }
        });
    }

    private function trueExpression(): string
    {
        return DB::connection()->getDriverName() === 'pgsql' ? 'true' : '1';
    }
};
