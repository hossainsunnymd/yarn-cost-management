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
        Schema::create('sewings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cutting_receive_id');
            $table->foreign('cutting_receive_id')->references('id')->on('cutting_receives')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('sewing_party_id');
            $table->foreign('sewing_party_id')->references('id')->on('sewing_parties')
            ->restrictOnDelete()->cascadeOnUpdate();
            $table->decimal('unit', 8, 2);
            $table->decimal('available_unit', 8, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sewings');
    }
};
