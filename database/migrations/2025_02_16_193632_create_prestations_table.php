<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('prestations', function (Blueprint $table) {
            $table->id();
            $table->string('service'); // Nom du service
            $table->text('description'); // Description détaillée
            $table->decimal('tarif_petite_voiture', 8, 2);
            $table->decimal('tarif_berline', 8, 2);
            $table->decimal('tarif_suv_4x4', 8, 2);
            $table->string('duree_estimee'); // Durée au format texte
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestations');
    }
};
