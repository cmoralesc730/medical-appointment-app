<x-admin-layout title="Citas | Healthify" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Citas',
        'href' => route('admin.appointments.index'),
    ],
    [
        'name' => 'Atender consulta',
    ]
]">

    @livewire('admin.consultation-manager', ['appointment' => $appointment])

</x-admin-layout>
