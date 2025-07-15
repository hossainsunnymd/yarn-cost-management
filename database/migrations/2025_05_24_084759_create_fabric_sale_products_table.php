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
        Schema::create('fabric_sale_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dyeing_receive_id');
            $table->foreign('dyeing_receive_id')->references('id')->on('dyeing_receives')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('fabric_sale_id');
            $table->foreign('fabric_sale_id')->references('id')->on('fabric_sales')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('unit', 8, 2);
            $table->decimal('per_unit_cost', 8, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->decimal('role', 10, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabric_sale_products');
    }
};
