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
        Schema::create('dyeings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dyeing_party_id');
            $table->foreign('dyeing_party_id')->references('id')->on('dyeing_parties')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('knitting_receive_id');
            $table->foreign('knitting_receive_id')->references('id')->on('knitting_receives')
                ->restrictOnDelete()->cascadeOnUpdate();
            $table->string('design_name')->nullable();
            $table->decimal('unit', 8, 2);
            $table->decimal('available_unit', 8, 2);
            $table->decimal('roll', 8, 2);
            $table->string('color');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dyeings');
    }
};
