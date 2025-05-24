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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_party_id');
            $table->foreign('sale_party_id')->references('id')->on('sale_parties')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('knitting_id');
            $table->foreign('knitting_id')->references('id')->on('knittings')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('unit', 8, 2);
            $table->decimal('total_amount', 8, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
