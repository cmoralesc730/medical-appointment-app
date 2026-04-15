<x-admin-layout title="Usuarios" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'href' => route('admin.users.index'),
    ],
    [
        'name' => 'Crear',
    ]
]">

    <x-wire-card>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="grid lg:grid-cols-2 gap-4">
                <x-wire-input label="Nombre" name="name" placeholder="Nombre completo" :value="old('name')">
                </x-wire-input>
                <x-wire-input label="Contraseña" name="password" type="password" placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
                </x-wire-input>
                <x-wire-input label="Confirmar contraseña" name="password_confirmation" type="password" placeholder="Repita la contraseña" required autocomplete="new-password">
                </x-wire-input>
                <x-wire-input label="Número de ID" name="id_number" placeholder="Número de identificación" autocomplete="off" inputmode="numeric" required :value="old('id_number')">
                </x-wire-input>
                <x-wire-input label="Teléfono" name="phone" placeholder="Número de teléfono" autocomplete="new-phone" required :value="old('phone')">
                </x-wire-input>
                </div>
            <x-wire-input label="Email" name="email" placeholder="Email" :value="old('email')">
            </x-wire-input>
            <x-wire-input label="Dirección" name="address" placeholder="Ej. Calle 90 293" required autocomplete="street-address" :value="old('address')">
            </x-wire-input>
            <div class ="space-y-1">
                <x-wire-native-select name="role_id" label="Rol" required>
                    <option value="">Seleccionar rol</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
                @endforeach
                </x-wire-native-select>
                <p class="text-sm text-gray-500">El rol determina los permisos y el acceso del usuario dentro del sistema.</p>
            </div>
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Crear</x-wire-button>
            </div>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>