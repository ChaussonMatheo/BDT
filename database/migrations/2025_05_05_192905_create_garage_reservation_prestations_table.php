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
        Schema::create('garage_reservation_prestations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('garage_reservation_id')->constrained()->onDelete('cascade');
            $table->string('description'); // ex : "Scenic nettoyage extérieur"
            $table->decimal('montant', 8, 2);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('garage_reservation_prestations');
    }
};
