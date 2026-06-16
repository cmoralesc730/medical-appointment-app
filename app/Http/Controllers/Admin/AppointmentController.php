<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppointmentRequest;
use App\Http\Requests\Admin\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('admin.appointments.index');
    }

    public function create()
    {
        $patients = Patient::with('user:id,name')->get(['id', 'user_id']);
        $doctors = Doctor::with('user:id,name')->get(['id', 'user_id']);

        return view('admin.appointments.create', compact('patients', 'doctors'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $data = $request->validated();
        $data['duration'] = $this->calculateDuration($data['start_time'], $data['end_time']);
        $data['status'] = Appointment::STATUS_SCHEDULED;

        Appointment::create($data);

        return redirect()->route('admin.appointments.index')->with('success', 'Cita creada correctamente');
    }

    public function edit(Appointment $appointment)
    {
        if (in_array($appointment->status, [Appointment::STATUS_COMPLETED, Appointment::STATUS_CANCELLED])) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'No se puede editar una cita que ya está completada o cancelada.');
        }

        $patients = Patient::with('user:id,name')->get(['id', 'user_id']);
        $doctors = Doctor::with('user:id,name')->get(['id', 'user_id']);

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        if (in_array($appointment->status, [Appointment::STATUS_COMPLETED, Appointment::STATUS_CANCELLED])) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'No se puede editar una cita que ya está completada o cancelada.');
        }

        $data = $request->validated();
        $data['duration'] = $this->calculateDuration($data['start_time'], $data['end_time']);

        $appointment->update($data);

        return redirect()->route('admin.appointments.index')->with('success', 'Cita actualizada correctamente');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('admin.appointments.index')->with('success', 'Cita eliminada correctamente');
    }

    public function consultation(Appointment $appointment)
    {
        if ($appointment->status === Appointment::STATUS_CANCELLED) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'No se puede registrar una consulta para una cita cancelada.');
        }

        if ($appointment->consultation()->exists()) {
            return redirect()->route('admin.appointments.index')
                ->with('error', 'Esta cita ya tiene una consulta registrada.');
        }

        $appointment->load(['patient.user', 'doctor.user']);

        return view('admin.appointments.consultation', compact('appointment'));
    }

    private function calculateDuration(string $startTime, string $endTime): int
    {
        return (int) Carbon::parse($startTime)->diffInMinutes(Carbon::parse($endTime));
    }
}
