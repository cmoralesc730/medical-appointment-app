<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Consultation;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ConsultationManager extends Component
{
    public Appointment $appointment;

    public string $diagnosis = '';
    public string $treatment = '';
    public string $notes = '';

    public array $medications = [];

    public bool $showHistoryModal = false;

    public function mount(Appointment $appointment): void
    {
        $this->appointment = $appointment->load(['patient.user', 'doctor.user']);
        $this->medications[] = $this->emptyMedication();
    }

    protected function emptyMedication(): array
    {
        return [
            'medication_name' => '',
            'dosage' => '',
            'frequency' => '',
            'duration' => '',
            'instructions' => '',
        ];
    }

    public function addMedication(): void
    {
        $this->medications[] = $this->emptyMedication();
    }

    public function removeMedication(int $index): void
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications);

        if (empty($this->medications)) {
            $this->medications[] = $this->emptyMedication();
        }
    }

    #[Computed]
    public function previousConsultations()
    {
        return Consultation::where('patient_id', $this->appointment->patient_id)
            ->with('doctor.user')
            ->latest()
            ->get();
    }

    public function openHistoryModal(): void
    {
        $this->showHistoryModal = true;
    }

    protected function rules(): array
    {
        return [
            'diagnosis' => 'required|string|min:5|max:1000',
            'treatment' => 'required|string|min:5|max:1000',
            'notes' => 'nullable|string|max:1000',
            'medications.*.medication_name' => 'nullable|string|max:255',
            'medications.*.dosage' => 'nullable|string|max:100',
            'medications.*.frequency' => 'nullable|string|max:100',
            'medications.*.duration' => 'nullable|string|max:100',
            'medications.*.instructions' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        $consultation = Consultation::create([
            'appointment_id' => $this->appointment->id,
            'patient_id' => $this->appointment->patient_id,
            'doctor_id' => $this->appointment->doctor_id,
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
        ]);

        foreach ($this->medications as $medication) {
            if (empty($medication['medication_name'])) {
                continue;
            }

            $consultation->prescriptionItems()->create($medication);
        }

        $this->appointment->update(['status' => Appointment::STATUS_COMPLETED]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Consulta registrada',
            'text' => 'La consulta se ha registrado correctamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager');
    }
}
