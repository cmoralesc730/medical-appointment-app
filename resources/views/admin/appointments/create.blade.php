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
        'name' => 'Nueva',
    ]
]">

    <form action="{{ route('admin.appointments.store') }}" method="POST">
    @csrf
    <x-wire-card class="mb-8">
        <div class="lg:flex lg:justify-between lg:items-center mb-4">
            <div>
                <p class="text-2xl font-bold text-gray-900">Nueva cita</p>
            </div>
            <div class="flex space-x-3 mt-6 lg:mt-0">
                <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">
                    Volver
                </x-wire-button>
                <x-wire-button type="submit">
                    <i class="fa-solid fa-check"></i>
                    Guardar
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
                        <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>
                            {{ $patient->user->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>
            </div>
            <div>
                <x-wire-native-select label="Doctor" name="doctor_id">
                    <option value="">Selecciona un doctor</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>
                            {{ $doctor->user->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>
            </div>
            <div>
                <x-wire-input type="date" label="Fecha" name="date" value="{{ old('date') }}">
                </x-wire-input>
            </div>
            <div></div>
            <div>
                <x-wire-input type="time" label="Hora de inicio" name="start_time" value="{{ old('start_time') }}">
                </x-wire-input>
            </div>
            <div>
                <x-wire-input type="time" label="Hora de fin" name="end_time" value="{{ old('end_time') }}">
                </x-wire-input>
            </div>
        </div>
        <div class="mt-4">
            <x-wire-textarea label="Motivo" name="reason">
                {{ old('reason') }}
            </x-wire-textarea>
        </div>
        <div class="mt-4 border-t pt-4">
            <p class="text-sm font-semibold text-gray-600 mb-3">
                <i class="fa-brands fa-whatsapp text-green-500 me-1"></i>
                Notificación por WhatsApp
            </p>
            <x-wire-input
                type="tel"
                label="Número de WhatsApp (formato internacional)"
                name="notification_phone"
                value="{{ old('notification_phone') }}"
                placeholder="Ej: 5216641234567"
                hint="Sin espacios ni el signo +. Al guardar la cita se enviará una confirmación automática a este número."
            />
        </div>
    </x-wire-card>
    </form>
</x-admin-layout>
