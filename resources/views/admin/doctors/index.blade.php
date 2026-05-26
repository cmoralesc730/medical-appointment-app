<x-admin-layout title="Doctores" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Doctores',
        'href' => route('admin.doctors.index'),
    ],
]">

@livewire('admin.datatables.doctor-table')

</x-admin-layout>