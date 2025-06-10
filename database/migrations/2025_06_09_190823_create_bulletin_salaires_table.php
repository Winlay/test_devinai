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
        Schema::create('bulletin_salaires', function (Blueprint $table) {
            $table->id();
            $table->integer('mois');
            $table->integer('annee');
            $table->decimal('salaire_brut', 10, 2);
            $table->decimal('total_primes', 10, 2)->default(0);
            $table->decimal('total_retenues', 10, 2)->default(0);
            $table->decimal('salaire_net', 10, 2);
            $table->timestamp('date_generation');
            $table->foreignId('employe_id')->constrained()->onDelete('cascade');
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['employe_id', 'mois', 'annee']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulletin_salaires');
    }
};
