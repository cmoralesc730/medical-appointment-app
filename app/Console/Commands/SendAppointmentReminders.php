<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature   = 'appointments:send-reminders';
    protected $description = 'Envía recordatorios por WhatsApp a pacientes con cita en las próximas 24 horas';

    public function handle(WhatsAppService $whatsapp): int
    {
        $target = Carbon::tomorrow()->toDateString();

        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $target)
            ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_CONFIRMED])
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay citas programadas para mañana.');
            return self::SUCCESS;
        }

        $enviados = 0;

        foreach ($appointments as $appointment) {
            $phone = $appointment->notification_phone ?? null;

            if (! $phone) {
                $this->warn("Cita #{$appointment->id}: no tiene número de WhatsApp registrado.");
                continue;
            }

            $fecha    = Carbon::parse($appointment->date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY');
            $inicio   = Carbon::parse($appointment->start_time)->format('h:i A');
            $fin      = Carbon::parse($appointment->end_time)->format('h:i A');
            $duracion = $appointment->duration;
            $motivo   = $appointment->reason ? "📋 *Motivo:* {$appointment->reason}\n" : '';

            $mensaje = "⏰ *Recordatorio de Cita - Healthify*\n\n"
                . "Hola, *{$appointment->patient->user->name}*.\n"
                . "Te recordamos que tienes una cita médica *mañana*. Aquí tienes el resumen:\n\n"
                . "👨‍⚕️ *Doctor:* {$appointment->doctor->user->name}\n"
                . "📅 *Fecha:* " . ucfirst($fecha) . "\n"
                . "⏰ *Hora de inicio:* {$inicio}\n"
                . "🔚 *Hora de fin:* {$fin}\n"
                . "⏱ *Duración:* {$duracion} minutos\n"
                . $motivo
                . "\nPor favor confirma tu asistencia o contáctanos si necesitas reprogramar.";

            $whatsapp->send($phone, $mensaje);
            $this->info("Recordatorio enviado → Cita #{$appointment->id} | {$appointment->patient->user->name}");
            $enviados++;
        }

        $this->info("Total enviados: {$enviados}");
        return self::SUCCESS;
    }
}
