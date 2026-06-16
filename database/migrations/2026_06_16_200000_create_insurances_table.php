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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('name');                         // Nombre del seguro / aseguradora
            $table->string('policy_number')->unique();      // Número de póliza
            $table->string('provider');                     // Proveedor / compañía aseguradora
            $table->enum('coverage_type', [
                'basic',
                'standard',
                'premium',
            ])->default('basic');                           // Tipo de cobertura
            $table->text('coverage_details')->nullable();   // Detalles de cobertura
            $table->decimal('deductible', 10, 2)->default(0.00);  // Deducible
            $table->decimal('coverage_limit', 12, 2)->nullable(); // Límite de cobertura
            $table->date('start_date');                     // Fecha de inicio de vigencia
            $table->date('end_date');                       // Fecha de fin de vigencia
            $table->boolean('is_active')->default(true);    // Estado activo / inactivo
            $table->text('notes')->nullable();              // Observaciones adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
