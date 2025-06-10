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
        Schema::create('element_salaires', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['prime', 'indemnite', 'avantage']);
            $table->string('nom');
            $table->decimal('montant', 10, 2);
            $table->boolean('recurrent')->default(false);
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
        Schema::dropIfExists('element_salaires');
    }
};
