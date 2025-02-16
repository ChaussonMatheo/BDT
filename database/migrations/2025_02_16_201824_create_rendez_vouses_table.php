<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 🔗 Associe le rendez-vous à l'utilisateur
            $table->foreignId('garage_id')->nullable()->constrained()->onDelete('cascade'); // 🔗 Associe à un garage (si pro)
            $table->foreignId('prestation_id')->nullable()->constrained()->onDelete('cascade'); // 🔗 Associe à une prestation (si particulier)
            $table->dateTime('date_heure'); // 🕒 Date et heure du rendez-vous
            $table->enum('statut', ['en attente', 'confirmé', 'annulé'])->default('en attente'); // 🔄 Statut du rendez-vous
            $table->timestamps(); // 🕒 Ajoute created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('rendez_vous');
    }
};
