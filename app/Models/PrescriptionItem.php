<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'consultation_id',
        'medication_name',
        'dosage',
        'frequency',
        'duration',
        'instructions',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
