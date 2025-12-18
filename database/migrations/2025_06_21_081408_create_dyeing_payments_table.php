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
        Schema::create('dyeing_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dyeing_party_id');
            $table->foreign('dyeing_party_id')->references('id')->on('dyeing_parties')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->string('challan_no')->nullable();
            $table->foreign('challan_no')->references('challan_no')->on('dyeings')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->string('particulars');
            $table->decimal('amount');
            $table->decimal('debit', 8, 2)->nullable();
            $table->decimal('credit', 8, 2)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dyeing_payments');
    }
};
