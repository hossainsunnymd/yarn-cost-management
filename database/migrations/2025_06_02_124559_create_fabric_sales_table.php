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
        Schema::create('fabric_sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drying_receive_id');
            $table->foreign('drying_receive_id')->references('id')->on('drying_receives')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('unit',15,2);
            $table->decimal('total_amount',15,2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabric_sales');
    }
};
