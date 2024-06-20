<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreignId('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->double('amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_devices');
    }
};
