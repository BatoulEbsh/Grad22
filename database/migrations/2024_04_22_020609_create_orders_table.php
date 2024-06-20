<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('image')->nullable();
            $table->text('desc')->nullable();
            $table->double('totalVoltage')->nullable();
            $table->double('chargeHours')->nullable();
            $table->text('location')->nullable();
            $table->text('state')->default('waiting');
            $table->foreignId('type_id')->constrained('types')->onDelete('cascade'); // تعديل هذا السطر ليشير إلى جدول 'types'
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
