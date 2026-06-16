<div>
    <x-wire-card class="mb-8">
        <div class="lg:flex lg:justify-between lg:items-center mb-4">
            <div>
                <p class="text-2xl font-bold text-gray-900">Atender cita</p>
                <p class="text-sm text-gray-500 mt-1">
                    <span class="font-semibold">Paciente:</span> {{ $appointment->patient->user->name }}
                    &nbsp;|&nbsp;
                    <span class="font-semibold">Doctor:</span> {{ $appointment->doctor->user->name }}
                    &nbsp;|&nbsp;
                    <span class="font-semibold">Fecha:</span> {{ \Illuminate\Support\Carbon::parse($appointment->date)->format('d/m/Y') }}
                    &nbsp;|&nbsp;
                    <span class="font-semibold">Hora:</span> {{ \Illuminate\Support\Carbon::parse($appointment->start_time)->format('h:i A') }}
                </p>
            </div>
            <div class="flex space-x-3 mt-6 lg:mt-0">
                <x-wire-button outline gray wire:click="openHistoryModal">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    Consultas anteriores
                </x-wire-button>
                <x-wire-button outline gray href="{{ route('admin.appointments.index') }}">
                    Volver
                </x-wire-button>
                <x-wire-button wire:click="save">
                    <i class="fa-solid fa-check"></i>
                    Guardar consulta
                </x-wire-button>
            </div>
        </div>
    </x-wire-card>

    <x-wire-card>
        <div x-data="{ tab: 'consulta' }">
            <div class="border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                    <li class="me-2">
                        <a href="#" x-on:click.prevent="tab = 'consulta'"
                            :class="{
                                'text-blue-600 border-blue-600 active': tab === 'consulta',
                                'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== 'consulta'
                            }"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                            <i class="fa-solid fa-stethoscope me-2"></i>
                            Consulta
                        </a>
                    </li>
                    <li class="me-2">
                        <a href="#" x-on:click.prevent="tab = 'receta'"
                            :class="{
                                'text-blue-600 border-blue-600 active': tab === 'receta',
                                'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== 'receta'
                            }"
                            class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                            <i class="fa-solid fa-pills me-2"></i>
                            Receta
                        </a>
                    </li>
                </ul>
            </div>

            <div class="px-4 mt-4">
                <div x-show="tab === 'consulta'" class="space-y-4">
                    <x-wire-textarea label="Diagnóstico" wire:model="diagnosis" />
                    <x-wire-textarea label="Tratamiento" wire:model="treatment" />
                    <x-wire-textarea label="Notas" wire:model="notes" />
                </div>

                <div x-show="tab === 'receta'" style="display: none;" class="space-y-4">
                    @foreach ($medications as $index => $medication)
                        <div wire:key="medication-{{ $index }}" class="grid lg:grid-cols-12 gap-4 items-end pb-4 border-b border-gray-100">
                            <div class="lg:col-span-3">
                                <x-wire-input label="Medicamento" wire:model="medications.{{ $index }}.medication_name" />
                            </div>
                            <div class="lg:col-span-2">
                                <x-wire-input label="Dosis" wire:model="medications.{{ $index }}.dosage" />
                            </div>
                            <div class="lg:col-span-2">
                                <x-wire-input label="Frecuencia" wire:model="medications.{{ $index }}.frequency" />
                            </div>
                            <div class="lg:col-span-2">
                                <x-wire-input label="Duración" wire:model="medications.{{ $index }}.duration" />
                            </div>
                            <div class="lg:col-span-2">
                                <x-wire-input label="Indicaciones" wire:model="medications.{{ $index }}.instructions" />
                            </div>
                            <div class="lg:col-span-1">
                                <x-wire-button type="button" red xs wire:click="removeMedication({{ $index }})">
                                    <i class="fa-solid fa-trash"></i>
                                </x-wire-button>
                            </div>
                        </div>
                    @endforeach

                    <x-wire-button type="button" outline blue wire:click="addMedication">
                        <i class="fa-solid fa-plus"></i>
                        Agregar medicamento
                    </x-wire-button>
                </div>
            </div>
        </div>
    </x-wire-card>

    <x-modal name="consultation-history" wire:model="showHistoryModal" title="Consultas anteriores">
        @forelse ($this->previousConsultations as $previous)
            <div class="border-b border-gray-100 pb-3 mb-3 last:border-b-0 last:mb-0 last:pb-0">
                <p class="text-xs text-gray-500 mb-1">
                    {{ $previous->created_at->format('d/m/Y') }} — Dr. {{ $previous->doctor->user->name }}
                </p>
                <p class="text-sm"><span class="font-semibold">Diagnóstico:</span> {{ $previous->diagnosis }}</p>
                <p class="text-sm"><span class="font-semibold">Tratamiento:</span> {{ $previous->treatment }}</p>
            </div>
        @empty
            <p class="text-sm text-gray-500">Este paciente no tiene consultas anteriores registradas.</p>
        @endforelse
    </x-modal>
</div>
