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
        'name' => 'Editar',
    ],
]">

    <form action="{{ route('admin.insurances.update', $insurance) }}" method="POST" novalidate>
    @csrf
    @method('PUT')

    {{-- Encabezado de tarjeta --}}
    <x-wire-card class="mb-6">
        <div class="lg:flex lg:justify-between lg:items-center">
            <div>
                <p class="text-2xl font-bold text-gray-900">
                    <i class="fa-solid fa-shield-heart text-blue-500 me-2"></i>
                    {{ $insurance->name }}
                </p>
                <p class="text-sm text-gray-500 mt-1">Póliza: <strong>{{ $insurance->policy_number }}</strong></p>
            </div>
            <div class="flex space-x-3 mt-4 lg:mt-0">
                <x-wire-button outline gray href="{{ route('admin.insurances.index') }}">
                    <i class="fa-solid fa-arrow-left me-1"></i>
                    Volver
                </x-wire-button>
                <x-wire-button type="submit">
                    <i class="fa-solid fa-check me-1"></i>
                    Guardar cambios
                </x-wire-button>
            </div>
        </div>
    </x-wire-card>

    {{-- Datos principales --}}
    <x-wire-card class="mb-6">
        <p class="text-base font-semibold text-gray-700 mb-4">
            <i class="fa-solid fa-file-contract text-gray-400 me-1"></i>
            Información del seguro
        </p>
        <div class="grid lg:grid-cols-2 gap-4">
            <div>
                <x-wire-input
                    label="Nombre del seguro"
                    name="name"
                    value="{{ old('name', $insurance->name) }}"
                />
                @error('name')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <x-wire-input
                    label="Número de póliza"
                    name="policy_number"
                    value="{{ old('policy_number', $insurance->policy_number) }}"
                />
                <p class="mt-1 text-xs text-gray-400">Solo letras, números, guiones (-), puntos (.) o barras (/).</p>
                @error('policy_number')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <x-wire-input
                    label="Proveedor / Aseguradora"
                    name="provider"
                    value="{{ old('provider', $insurance->provider) }}"
                />
                @error('provider')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <x-wire-native-select label="Tipo de cobertura" name="coverage_type">
                    <option value="">Selecciona un tipo</option>
                    @foreach ($coverageTypes as $value => $label)
                        <option value="{{ $value }}" @selected(old('coverage_type', $insurance->coverage_type) === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </x-wire-native-select>
                @error('coverage_type')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
        <div class="mt-4">
            <x-wire-textarea
                label="Detalles de cobertura"
                name="coverage_details"
                placeholder="Describe los servicios y condiciones que cubre esta póliza... (Requerido para cobertura Premium)"
                rows="4"
            >{{ old('coverage_details', $insurance->coverage_details) }}</x-wire-textarea>
            @error('coverage_details')
                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                </p>
            @enderror
        </div>
    </x-wire-card>

    {{-- Datos económicos y vigencia --}}
    <x-wire-card class="mb-6">
        <p class="text-base font-semibold text-gray-700 mb-4">
            <i class="fa-solid fa-coins text-gray-400 me-1"></i>
            Datos económicos y vigencia
        </p>

        {{-- Aviso de regla de negocio --}}
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700 flex items-start gap-2">
            <i class="fa-solid fa-circle-info mt-0.5"></i>
            <span>El <strong>deducible</strong> debe ser menor al <strong>límite de cobertura</strong>. La vigencia no puede superar <strong>10 años</strong> ni exceder <strong>30 años</strong> en el futuro.</span>
        </div>

        <div class="grid lg:grid-cols-2 gap-4">
            <div>
                <x-wire-input
                    label="Deducible ($)"
                    name="deductible"
                    type="number"
                    step="0.01"
                    min="0"
                    max="9999999.99"
                    value="{{ old('deductible', $insurance->deductible) }}"
                />
                @error('deductible')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <x-wire-input
                    label="Límite de cobertura ($)"
                    name="coverage_limit"
                    type="number"
                    step="0.01"
                    min="0"
                    max="99999999.99"
                    value="{{ old('coverage_limit', $insurance->coverage_limit) }}"
                />
                @error('coverage_limit')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <x-wire-input
                    label="Fecha de inicio de vigencia"
                    name="start_date"
                    type="date"
                    value="{{ old('start_date', $insurance->start_date?->format('Y-m-d')) }}"
                />
                @error('start_date')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <x-wire-input
                    label="Fecha de fin de vigencia"
                    name="end_date"
                    type="date"
                    value="{{ old('end_date', $insurance->end_date?->format('Y-m-d')) }}"
                />
                @error('end_date')
                    <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>
    </x-wire-card>

    {{-- Estado y observaciones --}}
    <x-wire-card>
        <p class="text-base font-semibold text-gray-700 mb-4">
            <i class="fa-solid fa-sliders text-gray-400 me-1"></i>
            Estado y observaciones
        </p>
        <div class="mb-4">
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0" />
                <input
                    id="is_active_edit"
                    type="checkbox"
                    name="is_active"
                    value="1"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                    {{ old('is_active', $insurance->is_active) ? 'checked' : '' }}
                />
                <label for="is_active_edit" class="text-sm font-medium text-gray-700">
                    Seguro activo
                </label>
            </div>
            <p class="mt-1 text-xs text-gray-400 ml-7">
                <i class="fa-solid fa-info-circle me-1"></i>
                Un seguro activo no puede tener fecha de fin anterior a hoy.
            </p>
        </div>
        <div>
            <x-wire-textarea
                label="Observaciones"
                name="notes"
                rows="3"
            >{{ old('notes', $insurance->notes) }}</x-wire-textarea>
            @error('notes')
                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $message }}
                </p>
            @enderror
        </div>
    </x-wire-card>

    </form>
</x-admin-layout>
