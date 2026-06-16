@php
    // Arreglo de íconos unificado
    $links = [
        [
            'name' => 'Dashboard',
            'icon' => 'fa-solid fa-gauge',
            'href' => route('admin.dashboard'),
            'active' => request()->routeIs('admin.dashboard'),
        ],
        [
            'header' => 'Gestión',
        ],
        [
            'name' => 'Roles y permisos',
            'icon' => 'fa-solid fa-shield-halved',
            'href' => route('admin.roles.index'),
            'active' => request()->routeIs('admin.roles.*'),
        ],
        [
            'name' => 'Usuarios',
            'icon' => 'fa-solid fa-users',
            'href' => route('admin.users.index'),
            'active' => request()->routeIs('admin.users.*'),
        ],
        [
            'name' => 'Pacientes',
            'icon' => 'fa-solid fa-user-injured',
            'href' => route('admin.patients.index'),
            'active' => request()->routeIs('admin.patients.*'),
        ],
        [
            'name' => 'Doctores',
            'icon' => 'fa-solid fa-user-doctor',
            'href' => route('admin.doctors.index'),
            'active' => request()->routeIs('admin.doctors.*'),
        ],
        [
            'name' => 'Citas',
            'icon' => 'fa-solid fa-calendar-check',
            'href' => route('admin.appointments.index'),
            'active' => request()->routeIs('admin.appointments.*'),
        ],
        [
            'name' => 'Seguros',
            'icon' => 'fa-solid fa-shield-heart',
            'href' => route('admin.insurances.index'),
            'active' => request()->routeIs('admin.insurances.*'),
        ],
    ];
@endphp

<aside id="top-bar-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-neutral-primary-soft border-e border-default">
        <a href="/" class="flex items-center ps-2.5 mb-5">
            <div class="h-6 me-3"></div> 
            <span class="self-center text-lg text-heading font-semibold whitespace-nowrap"></span>
        </a>

        <ul class="space-y-2 font-medium">
            @foreach ($links as $link)
                <li>
                    {{-- Renderizado de Encabezados --}}
                    @isset($link['header'])
                        <div class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase">
                            {{ $link['header'] }}
                        </div>
                    @else
                        {{-- Renderizado de Links Normales --}}
                        <a href="{{ $link['href'] }}" @class([
                                'flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group',
                                'bg-gray-100 dark:bg-gray-700' => $link['active'],
                            ])>
                            <span class="w-6 h-6 inline-flex justify-center items-center text-gray-500">
                                <i class="{{ $link['icon'] }}"></i>
                            </span>
                            <span class="ms-3">{{ $link['name'] }}</span>
                        </a>
                    @endisset
                </li>
            @endforeach
        </ul>
    </div>
</aside>