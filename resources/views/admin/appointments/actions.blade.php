<div class="flex items-center gap-2">
    <x-wire-button href="{{ route('admin.appointments.consultation', $appointment) }}" title="Atender consulta" green xs>
        <i class="fa-solid fa-stethoscope"></i>
    </x-wire-button>
    <x-wire-button href="{{ route('admin.appointments.edit', $appointment) }}" title="Editar" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>
    <form action="{{ route('admin.appointments.destroy', $appointment) }}" method="POST"
        onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?')">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" title="Eliminar" red xs>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
</div>
