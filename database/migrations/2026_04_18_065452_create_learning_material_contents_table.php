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
        Schema::create('learning_material_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learning_material_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['text', 'image']);
            $table->longText('content')->nullable();
            $table->integer('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_material_contents');
    }
};
