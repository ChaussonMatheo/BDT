<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->decimal('tarif', 8, 2)->after('prestation_id')->nullable(); // Champ pour le tarif
            $table->enum('type_de_voiture', ['petite_voiture', 'berline', 'suv_4x4'])->after('tarif'); // Type de voiture
        });
    }

    public function down()
    {
        Schema::table('rendez_vous', function (Blueprint $table) {
            $table->dropColumn(['tarif', 'type_de_voiture']);
        });
    }

};
