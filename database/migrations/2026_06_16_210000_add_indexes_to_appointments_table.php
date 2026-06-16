<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Usado por la validación de solapamiento: WHERE doctor_id = ? AND date = ?
            $table->index(['doctor_id', 'date'], 'appointments_doctor_date_idx');

            // Usado en guards de estado y filtros del datatable
            $table->index('status', 'appointments_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('appointments_doctor_date_idx');
            $table->dropIndex('appointments_status_idx');
        });
    }
};
