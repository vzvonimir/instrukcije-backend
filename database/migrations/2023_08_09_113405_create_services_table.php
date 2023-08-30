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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructor_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('category_id');
            $table->string('description');
            $table->decimal('price', 5, 2);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('instructor_id')
                  ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')
                  ->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('category_id')
                  ->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
