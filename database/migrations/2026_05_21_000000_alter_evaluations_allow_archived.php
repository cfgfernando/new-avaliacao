<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Remove unique constraint on cycle_id + evaluated_id to allow archived duplicates
        // and add 'archived' to the status enum.
        // This migration assumes MySQL. Adjust statements for other DB engines if necessary.

        if (DB::getDriverName() !== 'sqlite') {
            // Drop index if exists
            DB::statement("ALTER TABLE `evaluations` DROP INDEX `unique_cycle_evaluated`");

            // Modify enum to include 'archived'
            DB::statement("ALTER TABLE `evaluations` MODIFY `status` ENUM('pending','submitted','under_appeal','completed','archived') NOT NULL DEFAULT 'pending'");
        } else {
            // For SQLite, drop index with standard SQL syntax
            DB::statement("DROP INDEX IF EXISTS `unique_cycle_evaluated`");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            // Recreate original enum and unique index
            DB::statement("ALTER TABLE `evaluations` MODIFY `status` ENUM('pending','submitted','under_appeal','completed') NOT NULL DEFAULT 'pending'");
            Schema::table('evaluations', function (Blueprint $table) {
                $table->unique(['cycle_id', 'evaluated_id'], 'unique_cycle_evaluated');
            });
        } else {
            Schema::table('evaluations', function (Blueprint $table) {
                $table->unique(['cycle_id', 'evaluated_id'], 'unique_cycle_evaluated');
            });
        }
    }
};
