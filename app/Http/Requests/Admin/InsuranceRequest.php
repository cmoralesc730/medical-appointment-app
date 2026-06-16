<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

abstract class InsuranceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    abstract protected function policyNumberRule(): mixed;

    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'min:3', 'max:255',
                'regex:/[a-zA-ZáéíóúÁÉÍÓÚñÑ]/',
            ],
            'policy_number' => [
                'required', 'string', 'min:3', 'max:50',
                'regex:/^[A-Za-z0-9\-\.\/]+$/',
                $this->policyNumberRule(),
            ],
            'provider' => [
                'required', 'string', 'min:3', 'max:255',
                'regex:/[a-zA-ZáéíóúÁÉÍÓÚñÑ]/',
            ],
            'coverage_type'    => ['required', Rule::in(['basic', 'standard', 'premium'])],
            'coverage_details' => ['nullable', 'string', 'max:2000'],
            'deductible'       => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'coverage_limit'   => ['nullable', 'numeric', 'min:0.01', 'max:99999999.99'],
            'start_date' => [
                'required', 'date',
                'after_or_equal:' . now()->subYears(50)->toDateString(),
                'before_or_equal:' . now()->addYears(30)->toDateString(),
            ],
            'end_date' => [
                'required', 'date',
                'after:start_date',
                'before_or_equal:' . now()->addYears(30)->toDateString(),
            ],
            'is_active' => ['sometimes', 'boolean'],
            'notes'     => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $deductible    = (float) ($this->input('deductible') ?? 0);
                $coverageLimit = $this->input('coverage_limit');
                $startDate     = $this->input('start_date');
                $endDate       = $this->input('end_date');
                $isActive      = $this->boolean('is_active');

                // Regla 1: El deducible no puede ser mayor o igual al límite de cobertura
                if ($coverageLimit !== null && $coverageLimit !== '') {
                    $limit = (float) $coverageLimit;
                    if ($limit > 0 && $deductible >= $limit) {
                        $validator->errors()->add(
                            'deductible',
                            'El deducible ($' . number_format($deductible, 2) . ') no puede ser mayor o igual al límite de cobertura ($' . number_format($limit, 2) . ').'
                        );
                    }
                }

                // Regla 2: Si el seguro está activo, la fecha de fin no puede ser anterior a hoy
                if ($isActive && $endDate) {
                    $today = now()->startOfDay();
                    $end   = \Carbon\Carbon::parse($endDate)->startOfDay();
                    if ($end->lt($today)) {
                        $validator->errors()->add(
                            'end_date',
                            'No se puede activar un seguro cuya fecha de fin (' . $end->format('d/m/Y') . ') ya ha vencido. Desactívalo o actualiza la fecha de vigencia.'
                        );
                    }
                }

                // Regla 3: La vigencia no puede ser mayor a 10 años (pólizas razonables)
                if ($startDate && $endDate) {
                    $start     = \Carbon\Carbon::parse($startDate);
                    $end       = \Carbon\Carbon::parse($endDate);
                    $diffYears = $start->diffInYears($end);
                    if ($diffYears > 10) {
                        $validator->errors()->add(
                            'end_date',
                            'El período de vigencia no puede superar los 10 años. El rango actual es de ' . $diffYears . ' años.'
                        );
                    }
                }

                // Regla 4: Premium requiere detalles de cobertura
                if ($this->input('coverage_type') === 'premium' && empty($this->input('coverage_details'))) {
                    $validator->errors()->add(
                        'coverage_details',
                        'Los seguros de cobertura Premium requieren que se especifiquen los detalles de cobertura.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'El nombre del seguro es obligatorio.',
            'name.min'                   => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex'                 => 'El nombre del seguro debe contener al menos una letra.',
            'policy_number.required'     => 'El número de póliza es obligatorio.',
            'policy_number.unique'       => 'Ya existe un seguro con ese número de póliza.',
            'policy_number.regex'        => 'El número de póliza solo puede contener letras, números, guiones (-), puntos (.) o barras (/).',
            'provider.required'          => 'El nombre del proveedor es obligatorio.',
            'provider.regex'             => 'El nombre del proveedor debe contener al menos una letra.',
            'coverage_type.required'     => 'Debes seleccionar un tipo de cobertura.',
            'coverage_type.in'           => 'El tipo de cobertura seleccionado no es válido.',
            'deductible.numeric'         => 'El deducible debe ser un valor numérico.',
            'deductible.min'             => 'El deducible no puede ser negativo.',
            'deductible.max'             => 'El deducible no puede superar $9,999,999.99.',
            'coverage_limit.numeric'     => 'El límite de cobertura debe ser un valor numérico.',
            'coverage_limit.min'         => 'El límite de cobertura debe ser mayor a $0.00.',
            'coverage_limit.max'         => 'El límite de cobertura no puede superar $99,999,999.99.',
            'start_date.required'        => 'La fecha de inicio de vigencia es obligatoria.',
            'start_date.date'            => 'La fecha de inicio no tiene un formato válido.',
            'start_date.after_or_equal'  => 'La fecha de inicio no puede ser anterior a 50 años atrás.',
            'start_date.before_or_equal' => 'La fecha de inicio no puede ser mayor a 30 años en el futuro.',
            'end_date.required'          => 'La fecha de fin de vigencia es obligatoria.',
            'end_date.date'              => 'La fecha de fin no tiene un formato válido.',
            'end_date.after'             => 'La fecha de fin debe ser posterior (al menos un día) a la fecha de inicio.',
            'end_date.before_or_equal'   => 'La fecha de fin no puede superar 30 años en el futuro.',
            'notes.max'                  => 'Las observaciones no pueden superar los 1000 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'             => 'nombre del seguro',
            'policy_number'    => 'número de póliza',
            'provider'         => 'proveedor',
            'coverage_type'    => 'tipo de cobertura',
            'coverage_details' => 'detalles de cobertura',
            'deductible'       => 'deducible',
            'coverage_limit'   => 'límite de cobertura',
            'start_date'       => 'fecha de inicio',
            'end_date'         => 'fecha de fin',
            'notes'            => 'observaciones',
        ];
    }
}
