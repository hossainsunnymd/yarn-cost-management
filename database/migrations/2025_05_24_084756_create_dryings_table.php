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
        Schema::create('dryings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('drying_party_id');
            $table->foreign('drying_party_id')->references('id')->on('drying_parties')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('knitting_receive_id');
            $table->foreign('knitting_receive_id')->references('id')->on('knitting_receives')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('unit', 8, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dryings');
    }
};
