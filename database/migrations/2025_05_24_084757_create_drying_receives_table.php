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
        Schema::create('drying_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drying_id');
            $table->foreign('drying_id')->references('id')->on('dryings')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('unit', 8, 2);
            $table->decimal('available_unit', 8, 2);
            $table->decimal('wastage', 8, 2)->nullable();
            $table->decimal('per_unit_cost', 8, 2);
            $table->decimal('total_amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drying_receives');
    }
};
