<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            'Cardiología',
            'Dermatología',
            'Endocrinología',
            'Gastroenterología',
            'Neurología',
            'Oncología',
            'Pediatría',
            'Psiquiatría',
            'Radiología'
        ];

        foreach ($specialties as $specialty) {
            \App\Models\Specialty::create(['name' => $specialty]);
        }
    }
}
