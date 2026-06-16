<x-admin-layout title="Seguros | Healthify" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Seguros',
        'href' => route('admin.insurances.index'),
    ],
    [
        'name' => $insurance->name,
    ],
]">

    <x-wire-card class="mb-6">
        <div class="lg:flex lg:justify-between lg:items-center">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-shield-heart text-blue-500 text-2xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $insurance->name }}</p>
                    <p class="text-sm text-gray-500">Póliza: <strong>{{ $insurance->policy_number }}</strong></p>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-4 lg:mt-0">
                @if ($insurance->is_active)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                        <i class="fa-solid fa-circle-check me-1"></i> Activo
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                        <i class="fa-solid fa-circle-xmark me-1"></i> Inactivo
                    </span>
                @endif
                <x-wire-button outline gray href="{{ route('admin.insurances.index') }}">
                    <i class="fa-solid fa-arrow-left me-1"></i> Volver
                </x-wire-button>
                <x-wire-button href="{{ route('admin.insurances.edit', $insurance) }}" blue>
                    <i class="fa-solid fa-pen-to-square me-1"></i> Editar
                </x-wire-button>
            </div>
        </div>
    </x-wire-card>

    <div class="grid lg:grid-cols-3 gap-6">

        {{-- Info principal --}}
        <div class="lg:col-span-2 space-y-6">

            <x-wire-card>
                <p class="text-base font-semibold text-gray-700 mb-4">
                    <i class="fa-solid fa-file-contract text-gray-400 me-1"></i>
                    Información del seguro
                </p>
                <dl class="grid sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="font-semibold text-gray-500">Proveedor</dt>
                        <dd class="text-gray-800 mt-0.5">{{ $insurance->provider }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-gray-500">Tipo de cobertura</dt>
                        <dd class="mt-0.5">
                            @php
                                $colors = [
                                    'basic'    => 'bg-gray-100 text-gray-700',
                                    'standard' => 'bg-blue-100 text-blue-700',
                                    'premium'  => 'bg-purple-100 text-purple-700',
                                ];
                                $color = $colors[$insurance->coverage_type] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                {{ $insurance->coverage_type_label }}
                            </span>
                        </dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-semibold text-gray-500">Detalles de cobertura</dt>
                        <dd class="text-gray-800 mt-0.5 whitespace-pre-line">
                            {{ $insurance->coverage_details ?? '—' }}
                        </dd>
                    </div>
                </dl>
            </x-wire-card>

            @if ($insurance->notes)
                <x-wire-card>
                    <p class="text-base font-semibold text-gray-700 mb-3">
                        <i class="fa-solid fa-note-sticky text-gray-400 me-1"></i>
                        Observaciones
                    </p>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $insurance->notes }}</p>
                </x-wire-card>
            @endif

        </div>

        {{-- Panel lateral --}}
        <div class="space-y-6">

            <x-wire-card>
                <p class="text-base font-semibold text-gray-700 mb-4">
                    <i class="fa-solid fa-coins text-gray-400 me-1"></i>
                    Datos económicos
                </p>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="font-semibold text-gray-500">Deducible</dt>
                        <dd class="text-gray-800 font-mono">${{ number_format($insurance->deductible, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-semibold text-gray-500">Límite de cobertura</dt>
                        <dd class="text-gray-800 font-mono">
                            {{ $insurance->coverage_limit ? '$' . number_format($insurance->coverage_limit, 2) : '—' }}
                        </dd>
                    </div>
                </dl>
            </x-wire-card>

            <x-wire-card>
                <p class="text-base font-semibold text-gray-700 mb-4">
                    <i class="fa-solid fa-calendar-days text-gray-400 me-1"></i>
                    Vigencia
                </p>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="font-semibold text-gray-500">Inicio</dt>
                        <dd class="text-gray-800 mt-0.5">{{ $insurance->start_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-gray-500">Fin</dt>
                        <dd class="text-gray-800 mt-0.5">{{ $insurance->end_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-gray-500">¿Vigente hoy?</dt>
                        <dd class="mt-0.5">
                            @if ($insurance->is_current)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    <i class="fa-solid fa-check me-1"></i> Vigente
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    <i class="fa-solid fa-xmark me-1"></i> No vigente
                                </span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </x-wire-card>

        </div>
    </div>

</x-admin-layout>
