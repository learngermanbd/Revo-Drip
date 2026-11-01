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
        Schema::create('revo_drip_order_deliveries', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('order_id')->unique();

            $table->string('zone_code')->nullable();

            $table->string('zone_label')->nullable();

            $table->decimal('delivery_charge', 12, 4)->default(0);

            $table->text('full_address')->nullable();

            $table->string('phone', 50)->nullable();

            $table->string('customer_name')->nullable();

            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revo_drip_order_deliveries');
    }
};
