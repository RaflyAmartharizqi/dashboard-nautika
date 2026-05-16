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
        Schema::table('learning_materials', function (Blueprint $table) {
            $table->foreignId('learning_material_group_id')->nullable()->constrained('learning_material_groups')->onDelete('set null')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('learning_material', function (Blueprint $table) {
            $table->dropForeign(['learning_material_group_id']);
            $table->dropColumn('learning_material_group_id');
        });
    }
};
