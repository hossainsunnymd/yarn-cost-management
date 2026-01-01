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
        Schema::create('yarn_sale_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yarn_sale_id')->index();
            $table->foreign('yarn_sale_id')->references('id')->on('yarn_sales')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('yarn_purchase_id')->index();
            $table->foreign('yarn_purchase_id')->references('id')->on('yarn_purchases')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('unit', 8, 2);
            $table->decimal('price', 8, 2);
            $table->decimal('total_amount', 8, 2);
            $table->decimal('per_unit_cost', 8, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yarn_sale_products');
    }
};
