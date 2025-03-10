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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('slug');
            $table->unsignedBigInteger('category_id'); // Foreign key to category
            $table->unsignedBigInteger('main_image')->nullable(); // Foreign key to image
            $table->boolean('is_showcase')->default(false);
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
