<?php

namespace App\Http\Requests\Admin;

use App\Models\Appointment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id'  => ['required', 'exists:doctors,id'],
            'date'       => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'reason'     => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $overlap = Appointment::where('doctor_id', $this->input('doctor_id'))
                    ->where('date', $this->input('date'))
                    ->whereNotIn('status', [Appointment::STATUS_CANCELLED])
                    ->where('start_time', '<', $this->input('end_time'))
                    ->where('end_time', '>', $this->input('start_time'))
                    ->exists();

                if ($overlap) {
                    $validator->errors()->add(
                        'start_time',
                        'El doctor ya tiene una cita programada en ese horario. Por favor elige otro horario.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Debes seleccionar un paciente.',
            'patient_id.exists'   => 'El paciente seleccionado no existe.',
            'doctor_id.required'  => 'Debes seleccionar un doctor.',
            'doctor_id.exists'    => 'El doctor seleccionado no existe.',
            'date.required'       => 'La fecha de la cita es obligatoria.',
            'date.after_or_equal' => 'No se pueden crear citas en fechas pasadas.',
            'start_time.required' => 'La hora de inicio es obligatoria.',
            'start_time.date_format' => 'La hora de inicio no tiene un formato válido.',
            'end_time.required'   => 'La hora de fin es obligatoria.',
            'end_time.date_format' => 'La hora de fin no tiene un formato válido.',
            'end_time.after'      => 'La hora de fin debe ser posterior a la hora de inicio.',
            'reason.max'          => 'El motivo no puede superar los 1000 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'patient_id' => 'paciente',
            'doctor_id'  => 'doctor',
            'date'       => 'fecha',
            'start_time' => 'hora de inicio',
            'end_time'   => 'hora de fin',
            'reason'     => 'motivo',
        ];
    }
}
