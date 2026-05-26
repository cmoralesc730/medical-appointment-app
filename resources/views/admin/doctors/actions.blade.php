<div class="flex items-center gap-2">
    <x-wire-button href="{{ route('admin.doctors.edit', $doctor) }}" title="Editar" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>
    <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST"
        onsubmit="return confirm('¿Estás seguro de que deseas eliminar este doctor?')">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" title="Eliminar" red xs>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
</div>
