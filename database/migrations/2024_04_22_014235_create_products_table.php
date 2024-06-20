<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('image');
            $table->double('price');
            $table->boolean('available')->default(true);
            $table->text('disc');
            $table->double('quantity');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
