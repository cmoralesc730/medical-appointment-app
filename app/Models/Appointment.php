<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    const STATUS_SCHEDULED = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELLED = 4;

    public static array $statuses = [
        self::STATUS_SCHEDULED => 'Programada',
        self::STATUS_CONFIRMED => 'Confirmada',
        self::STATUS_COMPLETED => 'Completada',
        self::STATUS_CANCELLED => 'Cancelada',
    ];

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::$statuses[$this->status] ?? 'Desconocido';
    }
}
