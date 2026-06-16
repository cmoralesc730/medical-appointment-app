<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class AppointmentTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Appointment::query()
            ->with(['patient.user', 'doctor.user']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Paciente", "patient.user.name")
                ->sortable(),
            Column::make("Doctor", "doctor.user.name")
                ->sortable(),
            Column::make("Fecha", "date")
                ->sortable()
                ->format(fn ($value) => Carbon::parse($value)->format('d/m/Y')),
            Column::make("Hora de inicio", "start_time")
                ->sortable()
                ->format(fn ($value) => Carbon::parse($value)->format('h:i A')),
            Column::make("Estatus", "status")
                ->sortable()
                ->label(function ($row) {
                    return view('admin.appointments.status', ['appointment' => $row]);
                }),
            Column::make("Acciones")
                ->label(function ($row) {
                    return view('admin.appointments.actions', ['appointment' => $row]);
                }),
        ];
    }
}
