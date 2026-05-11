<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hierarchy_nodes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('type', ['Network', 'District', 'Area', 'Sector']);
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('hierarchy_nodes')
                  ->nullOnDelete();
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('type');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hierarchy_nodes');
    }
};
