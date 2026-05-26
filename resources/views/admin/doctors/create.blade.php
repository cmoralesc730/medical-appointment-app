<x-admin-layout title="Doctores | Healthify" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
    [
        'name' => 'Nuevo',
    ]
]">

    <form action="{{route('admin.doctors.store')}}" method="POST">
    @csrf
    <x-wire-card class="mb-8">
        <div class="lg:flex lg:justify-between lg:items-center mb-4">
            <div>
                <p class="text-2xl font-bold text-gray-900">Nuevo doctor</p>
            </div>
            <div class="flex space-x-3 mt-6 lg:mt-0">
                <x-wire-button outline gray href="{{route('admin.doctors.index')}}">
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
                <x-wire-native-select label="Usuario" name="user_id">
                    <option value="">Selecciona un usuario</option>
                    @foreach ($users as $user)
                        <option value="{{$user->id}}" @selected(old('user_id') == $user->id)>
                            {{$user->name}} — {{$user->email}}
                        </option>
                    @endforeach
                </x-wire-native-select>
            </div>
            <div>
                <x-wire-native-select label="Especialidad" name="specialty_id">
                    <option value="">Selecciona una especialidad</option>
                    @foreach ($specialties as $specialty)
                        <option value="{{$specialty->id}}" @selected(old('specialty_id') == $specialty->id)>
                            {{$specialty->name}}
                        </option>
                    @endforeach
                </x-wire-native-select>
            </div>
            <div>
                <x-wire-input label="Cédula profesional" name="medical_license_number"
                    value="{{old('medical_license_number')}}">
                </x-wire-input>
            </div>
        </div>
        <div class="mt-4">
            <x-wire-textarea label="Biografía" name="biography">
                {{old('biography')}}
            </x-wire-textarea>
        </div>
    </x-wire-card>
    </form>
</x-admin-layout>
