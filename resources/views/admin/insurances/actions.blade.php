<div class="flex items-center gap-2">
    <x-wire-button href="{{ route('admin.insurances.show', $insurance) }}" title="Ver detalle" gray xs>
        <i class="fa-solid fa-eye"></i>
    </x-wire-button>
    <x-wire-button href="{{ route('admin.insurances.edit', $insurance) }}" title="Editar" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>
    <form action="{{ route('admin.insurances.destroy', $insurance) }}" method="POST" class="delete-form">
        @csrf
        @method('DELETE')
        <x-wire-button type="submit" title="Eliminar" red xs>
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
</div>
