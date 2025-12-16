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
        Schema::create('knitting_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('knitting_party_id');
            $table->foreign('knitting_party_id')->references('id')->on('knitting_parties')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('amount', 8, 2);
            $table->decimal('debit', 8, 2)->nullable();
            $table->decimal('credit', 8, 2)->nullable();
            $table->string('particulars')->nullable();
            $table->string('challan_no')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knitting_payments');
    }
};
