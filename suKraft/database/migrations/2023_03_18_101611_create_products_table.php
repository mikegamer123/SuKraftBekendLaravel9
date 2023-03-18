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
            $table->id();
            $table->foreignId('sellerID')->references('id')->on('sellers');
            $table->foreignId('mediaID')->nullable()->references('id')->on('media');
            $table->string('name');
            $table->text('description');
            $table->decimal('price');
            $table->decimal('salePrice');
            $table->boolean('isAvailable')->nullable();
            $table->timestamps();
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
