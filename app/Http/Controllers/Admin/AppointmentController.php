<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppointmentRequest;
use App\Http\Requests\Admin\UpdateAppointmentRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\WhatsAppService;
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

        $appointment = Appointment::create($data);
        $appointment->load(['patient.user', 'doctor.user']);

        $phone = $appointment->notification_phone ?? null;

        if ($phone) {
            try {
                $fecha    = Carbon::parse($appointment->date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');
                $inicio   = Carbon::parse($appointment->start_time)->format('h:i A');
                $fin      = Carbon::parse($appointment->end_time)->format('h:i A');
                $duracion = $appointment->duration;
                $motivo   = $appointment->reason ? "📋 *Motivo:* {$appointment->reason}\n" : '';

                $mensaje = "✅ *Cita Confirmada - Healthify*\n\n"
                    . "Hola, *{$appointment->patient->user->name}*.\n"
                    . "Tu cita médica ha sido registrada. Aquí tienes el resumen:\n\n"
                    . "👨‍⚕️ *Doctor:* {$appointment->doctor->user->name}\n"
                    . "📅 *Fecha:* " . ucfirst($fecha) . "\n"
                    . "⏰ *Hora de inicio:* {$inicio}\n"
                    . "🔚 *Hora de fin:* {$fin}\n"
                    . "⏱ *Duración:* {$duracion} minutos\n"
                    . $motivo
                    . "\nPor favor llega 10 minutos antes. Si necesitas cancelar o reprogramar, contáctanos con anticipación.";

                app(WhatsAppService::class)->send($phone, $mensaje);

                return redirect()->route('admin.appointments.index')
                    ->with('success', 'Cita creada y confirmación enviada por WhatsApp.');
            } catch (\Throwable $e) {
                report($e);
                return redirect()->route('admin.appointments.index')
                    ->with('success', 'Cita creada correctamente.')
                    ->with('warning', 'No se pudo enviar el WhatsApp: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.appointments.index')->with('success', 'Cita creada correctamente.');
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
