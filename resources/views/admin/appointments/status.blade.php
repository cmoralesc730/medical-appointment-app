@php
    $colors = [
        \App\Models\Appointment::STATUS_SCHEDULED => 'bg-blue-100 text-blue-800',
        \App\Models\Appointment::STATUS_CONFIRMED => 'bg-green-100 text-green-800',
        \App\Models\Appointment::STATUS_COMPLETED => 'bg-gray-100 text-gray-800',
        \App\Models\Appointment::STATUS_CANCELLED => 'bg-red-100 text-red-800',
    ];
@endphp

<span class="px-2 py-1 text-xs font-semibold rounded-full {{ $colors[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
    {{ $appointment->status_label }}
</span>
