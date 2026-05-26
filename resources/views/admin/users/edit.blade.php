
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
		'name' => 'Editar',
	]
]">

	<x-wire-card>
		<x-validation-errors class="mb-4" />
		<form action="{{ route('admin.users.update', $user) }}" method="POST">
			@csrf
			@method('PATCH')
			<div class="space-y-4">
				<div class="grid lg:grid-cols-2 gap-4">
				<x-wire-input label="Nombre" name="name" placeholder="Nombre completo" :value="old('name', $user->name)">
				</x-wire-input>
				<x-wire-input label="Contraseña" name="password" type="password" placeholder="Dejar en blanco para mantener la actual" autocomplete="new-password">
				</x-wire-input>
				<x-wire-input label="Confirmar contraseña" name="password_confirmation" type="password" placeholder="Repita la contraseña" autocomplete="new-password">
				</x-wire-input>
				<x-wire-input label="Número de ID" name="id_number" placeholder="Número de identificación" autocomplete="off" inputmode="numeric" :value="old('id_number', $user->id_number)">
				</x-wire-input>
				<x-wire-input label="Teléfono" name="phone" placeholder="Número de teléfono" autocomplete="new-phone" inputmode="numeric" :value="old('phone', $user->phone)">
				</x-wire-input>
				<x-wire-input label="Email" name="email" placeholder="Email" :value="old('email', $user->email)">
				</x-wire-input>
				</div>
			<x-wire-input label="Dirección" name="address" placeholder="Ej. Calle 90 293" autocomplete="street-address" :value="old('address', $user->address)">
			</x-wire-input>
			<div class ="space-y-1">
				@php $roles = \Spatie\Permission\Models\Role::all(); @endphp
				<x-wire-native-select name="role_id" label="Rol" required>
					<option value="">Seleccionar rol</option>
				@foreach ($roles as $role)
					<option value="{{ $role->id }}" @selected(old('role_id', $user->roles->first()->id ?? null) == $role->id)>{{ $role->name }}</option>
				@endforeach
				</x-wire-native-select>
				<p class="text-sm text-gray-500">El rol determina los permisos y el acceso del usuario dentro del sistema.</p>
			</div>
			<div class="flex justify-end mt-4">
				<x-wire-button type="submit" blue>Actualizar</x-wire-button>
			</div>
			</div>
		</form>
	</x-wire-card>
</x-admin-layout>