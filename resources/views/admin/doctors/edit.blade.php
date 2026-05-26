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
        'name' => 'Editar',
    ]
]">

    <form action="{{route('admin.doctors.update', $doctor)}}" method="POST">
    @csrf
    @method('PUT')
    <x-wire-card class="mb-8">
        <div class="lg:flex lg:justify-between lg:items-center mb-4">
            <div class="flex items-center">
                <img src="{{$doctor->user->profile_photo_url}}" alt="{{$doctor->user->name}}"
                class="h-20 w-20 rounded-full object-cover object-center">
                <div>
                    <p class="text-2xl font-bold text-gray-900 ml-4">{{$doctor->user->name}}</p>
                </div>
            </div>
            <div class="flex space-x-3 mt-6 lg:mt-0">
                <x-wire-button outline gray href="{{route('admin.doctors.index')}}">
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
        <div x-data="{tab: 'datos-personales'}">

            <div class="border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">

                    <li class="me-2">
                        <a href="#" x-on:click="tab = 'datos-personales'"
                        :class="{
                            'text-blue-600 border-blue-600 active': tab === 'datos-personales',
                            'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== 'datos-personales'
                        }"
                         class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200"
                         :aria-current="tab === 'datos-personales' ? 'page' : undefined">
                         <i class="fa-solid fa-user me-2"></i>
                         Datos personales
                        </a>
                    </li>

                    <li class="me-2">
                        <a href="#" x-on:click="tab = 'datos-profesionales'"
                        :class="{
                            'text-blue-600 border-blue-600 active': tab === 'datos-profesionales',
                            'border-transparent hover:text-blue-600 hover:border-gray-300': tab !== 'datos-profesionales'
                        }"
                         class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200"
                         :aria-current="tab === 'datos-profesionales' ? 'page' : undefined">
                         <i class="fa-solid fa-stethoscope me-2"></i>
                         Datos profesionales
                        </a>
                    </li>

                </ul>
            </div>

            <div class="px-4 mt-4">

                <div x-show="tab === 'datos-personales'">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fa-solid fa-user-gear text-blue-500 text-xl mt-1"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-blue-800">
                                        Edición de cuenta de usuario
                                    </h3>
                                    <div class="mt-1 text-sm text-blue-600">
                                        <p>La <strong>información de acceso</strong> (Nombre, Email y Contraseña)
                                        debe gestionarse desde la cuenta de usuario asociada.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <x-wire-button primary sm href="{{route('admin.users.edit', $doctor->user)}}" target="_blank">
                                    Editar usuario
                                    <i class="fa-solid fa-arrow-up-right-from-square ms-2"></i>
                                </x-wire-button>
                            </div>
                        </div>
                    </div>
                    <div class="grid lg:grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-500 font-semibold ml-1">Teléfono:</span>
                            <span class="text-gray-900 text-sm ml-1">{{$doctor->user->phone}}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold ml-1">Email:</span>
                            <span class="text-gray-900 text-sm ml-1">{{$doctor->user->email}}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 font-semibold ml-1">Dirección:</span>
                            <span class="text-gray-900 text-sm ml-1">{{$doctor->user->address}}</span>
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'datos-profesionales'" style="display: none;">
                    <div class="grid lg:grid-cols-2 gap-4">
                        <div>
                            <x-wire-input label="Cédula profesional" name="medical_license_number"
                                value="{{old('medical_license_number', $doctor->medical_license_number)}}">
                            </x-wire-input>
                        </div>
                        <div>
                            <x-wire-native-select label="Especialidad" name="specialty_id">
                                <option value="">Selecciona una especialidad</option>
                                @foreach ($specialties as $specialty)
                                    <option value="{{$specialty->id}}" @selected(old('specialty_id', $doctor->specialty_id) == $specialty->id)>
                                        {{$specialty->name}}
                                    </option>
                                @endforeach
                            </x-wire-native-select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <x-wire-textarea label="Biografía" name="biography">
                            {{old('biography', $doctor->biography)}}
                        </x-wire-textarea>
                    </div>
                </div>

            </div>
        </div>
    </x-wire-card>
    </form>
</x-admin-layout>
