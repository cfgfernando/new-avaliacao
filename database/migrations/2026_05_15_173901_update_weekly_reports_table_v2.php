<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('weekly_reports', function (Blueprint $table) {
            // Renames
            if (Schema::hasColumn('weekly_reports', 'report_date')) {
                $table->renameColumn('report_date', 'meeting_date');
            }
            if (Schema::hasColumn('weekly_reports', 'mda_count')) {
                $table->renameColumn('mda_count', 'mdas_done');
            }
            if (Schema::hasColumn('weekly_reports', 'kg_social')) {
                $table->renameColumn('kg_social', 'kg_of_love');
            }
            if (Schema::hasColumn('weekly_reports', 'observations')) {
                $table->renameColumn('observations', 'notes');
            }

            // New Columns
            if (!Schema::hasColumn('weekly_reports', 'word_theme')) {
                $table->string('word_theme')->nullable()->after('meeting_date');
            }
            if (!Schema::hasColumn('weekly_reports', 'meeting_location')) {
                $table->string('meeting_location')->nullable()->after('word_theme');
            }
            if (!Schema::hasColumn('weekly_reports', 'committed_members')) {
                $table->integer('committed_members')->default(0)->after('meeting_location');
            }
            if (!Schema::hasColumn('weekly_reports', 'other_cell_visitors')) {
                $table->integer('other_cell_visitors')->default(0)->after('children');
            }
            if (!Schema::hasColumn('weekly_reports', 'house_of_peace')) {
                $table->integer('house_of_peace')->default(0)->after('other_cell_visitors');
            }
            if (!Schema::hasColumn('weekly_reports', 'reconciliations')) {
                $table->integer('reconciliations')->default(0)->after('mdas_done');
            }
            if (!Schema::hasColumn('weekly_reports', 'present_member_ids')) {
                $table->json('present_member_ids')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('weekly_reports', 'visitor_names')) {
                $table->json('visitor_names')->nullable()->after('present_member_ids');
            }
        });
    }

    public function down(): void
    {
        Schema::table('weekly_reports', function (Blueprint $table) {
            if (Schema::hasColumn('weekly_reports', 'meeting_date')) {
                $table->renameColumn('meeting_date', 'report_date');
            }
            if (Schema::hasColumn('weekly_reports', 'mdas_done')) {
                $table->renameColumn('mdas_done', 'mda_count');
            }
            if (Schema::hasColumn('weekly_reports', 'kg_of_love')) {
                $table->renameColumn('kg_of_love', 'kg_social');
            }
            if (Schema::hasColumn('weekly_reports', 'notes')) {
                $table->renameColumn('notes', 'observations');
            }

            $table->dropColumn([
                'word_theme',
                'meeting_location',
                'committed_members',
                'other_cell_visitors',
                'house_of_peace',
                'reconciliations',
                'present_member_ids',
                'visitor_names'
            ]);
        });
    }
};
