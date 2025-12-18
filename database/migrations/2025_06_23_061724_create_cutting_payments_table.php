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
        Schema::create('cutting_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cutting_party_id');
            $table->foreign('cutting_party_id')->references('id')->on('cutting_parties')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->integer('challan_no')->nullable();
            $table->foreign('challan_no')->references('challan_no')->on('cuttings')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->string('particulars');
            $table->decimal('amount', 8, 2);
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
        Schema::dropIfExists('cutting_payments');
    }
};
