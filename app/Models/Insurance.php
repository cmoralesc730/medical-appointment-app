<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $table = 'insurances';

    protected $fillable = [
        'name',
        'policy_number',
        'provider',
        'coverage_type',
        'coverage_details',
        'deductible',
        'coverage_limit',
        'start_date',
        'end_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'start_date'     => 'date',
        'end_date'       => 'date',
        'is_active'      => 'boolean',
        'deductible'     => 'decimal:2',
        'coverage_limit' => 'decimal:2',
    ];

    /**
     * Etiquetas legibles para los tipos de cobertura.
     */
    public static function coverageTypeLabels(): array
    {
        return [
            'basic'    => 'Básica',
            'standard' => 'Estándar',
            'premium'  => 'Premium',
        ];
    }

    /**
     * Obtiene la etiqueta legible del tipo de cobertura.
     */
    public function getCoverageTypeLabelAttribute(): string
    {
        return self::coverageTypeLabels()[$this->coverage_type] ?? ucfirst($this->coverage_type);
    }

    /**
     * Scope para filtrar solo seguros activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Verifica si el seguro está vigente (dentro de las fechas).
     */
    public function getIsCurrentAttribute(): bool
    {
        $today = now()->toDateString();
        return $this->is_active
            && $this->start_date->toDateString() <= $today
            && $this->end_date->toDateString() >= $today;
    }
}
