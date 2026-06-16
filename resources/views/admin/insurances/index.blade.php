<x-admin-layout title="Seguros | Healthify" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Seguros',
        'href' => route('admin.insurances.index'),
    ],
]">
    <x-slot name="action">
        <x-wire-button href="{{ route('admin.insurances.create') }}" primary>
            <i class="fa-solid fa-plus me-1"></i>
            Nuevo seguro
        </x-wire-button>
    </x-slot>

    @livewire('admin.datatables.insurance-table')

</x-admin-layout>
