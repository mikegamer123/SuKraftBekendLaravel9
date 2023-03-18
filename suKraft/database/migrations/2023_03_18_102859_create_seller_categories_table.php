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
        Schema::create('seller_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoryID')->references('id')->on('categories');
            $table->foreignId('sellerID')->references('id')->on('sellers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_categories');
    }
};
