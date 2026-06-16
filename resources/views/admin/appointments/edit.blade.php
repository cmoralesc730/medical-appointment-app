<x-admin-layout title="Citas | Healthify" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Editar',
    ]
]">

    <form action="{{ route('admin.appointments.update', $appointment) }}" method="POST">
    @csrf
    @method('PUT')
    <x-wire-card class="mb-8">
        <div class="lg:flex lg:justify-between lg:items-center mb-4">
            <div>
                <p class="text-2xl font-bold text-gray-900">Editar cita</p>
            </div>
            <div class="flex space-x-3 mt-6 lg:mt-0">
                <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">
                    Volver
                </x-wire-button>
                <x-wire-button type="submit">
                    <i class="fa-solid fa-check"></i>
                    Guardar cambios
                </x-wire-button>
            </div>
        </div>
    </x-wire-card>
    <x-wire-card>
        <div class="grid lg:grid-cols-2 gap-4">
            <div>
                <x-wire-native-select label="Paciente" name="patient_id">
                    <option value="">Selecciona un paciente</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" @selected(old('patient_id', $appointment->patient_id) == $patient->id)>
                            {{ $patient->user->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>
            </div>
            <div>
                <x-wire-native-select label="Doctor" name="doctor_id">
                    <option value="">Selecciona un doctor</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(old('doctor_id', $appointment->doctor_id) == $doctor->id)>
                            {{ $doctor->user->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>
            </div>
            <div>
                <x-wire-input type="date" label="Fecha" name="date"
                    value="{{ old('date', $appointment->date) }}">
                </x-wire-input>
            </div>
            <div>
                <x-wire-native-select label="Estatus" name="status">
                    @foreach (\App\Models\Appointment::$statuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $appointment->status) == $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </x-wire-native-select>
            </div>
            <div>
                <x-wire-input type="time" label="Hora de inicio" name="start_time"
                    value="{{ old('start_time', \Illuminate\Support\Carbon::parse($appointment->start_time)->format('H:i')) }}">
                </x-wire-input>
            </div>
            <div>
                <x-wire-input type="time" label="Hora de fin" name="end_time"
                    value="{{ old('end_time', \Illuminate\Support\Carbon::parse($appointment->end_time)->format('H:i')) }}">
                </x-wire-input>
            </div>
        </div>
        <div class="mt-4">
            <x-wire-textarea label="Motivo" name="reason">
                {{ old('reason', $appointment->reason) }}
            </x-wire-textarea>
        </div>
    </x-wire-card>
    </form>
</x-admin-layout>
