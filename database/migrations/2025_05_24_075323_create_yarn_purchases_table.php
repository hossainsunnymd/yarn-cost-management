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
        Schema::create('yarn_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('yarn_party_id');
            $table->foreign('yarn_party_id')->references('id')->on('yarn_parties')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->string('name');
            $table->string('description');
            $table->string('bags');
            $table->decimal('unit', 8, 2);
            $table->decimal('available_unit', 8, 2);
            $table->decimal('yarn_rate', 8, 2);
            $table->decimal('bill_amount', 8, 2);
            $table->decimal('labour_cost', 8, 2);
            $table->decimal('per_unit_cost', 8, 2);
            $table->decimal('total_amount', 8, 2);
            $table->decimal('current_total_amount', 8, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yarn_purchases');
    }
};
