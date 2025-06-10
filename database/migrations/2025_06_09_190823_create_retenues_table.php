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
        Schema::create('retenues', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->enum('type', ['cotisation_sociale', 'impot', 'avance', 'autre']);
            $table->decimal('montant', 10, 2);
            $table->integer('mois');
            $table->integer('annee');
            $table->text('description')->nullable();
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retenues');
    }
};
