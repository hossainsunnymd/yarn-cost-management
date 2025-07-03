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
        Schema::create('dyeing_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dyeing_id');
            $table->foreign('dyeing_id')->references('id')->on('dyeings')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('unit', 8, 2);
            $table->decimal('available_unit', 8, 2);
            $table->decimal('wastage', 8, 2)->nullable();
            $table->decimal('per_unit_cost', 8, 2);
            $table->decimal('dyeing_cost', 8, 2);
            $table->decimal('total_cost', 8, 2);
            $table->decimal('roll', 8, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dyeing_receives');
    }
};
