<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Insurance;
use Illuminate\Database\Eloquent\Builder;

class InsuranceTable extends DataTableComponent
{
    public function builder(): Builder
    {
        return Insurance::query()->latest();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['insurances.end_date']);
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('N° de Póliza', 'policy_number')
                ->sortable()
                ->searchable(),

            Column::make('Proveedor', 'provider')
                ->sortable()
                ->searchable(),

            Column::make('Tipo de Cobertura', 'coverage_type')
                ->sortable()
                ->html()
                ->label(function ($row) {
                    $labels = Insurance::coverageTypeLabels();
                    $label  = $labels[$row->coverage_type] ?? ucfirst($row->coverage_type);

                    $colors = [
                        'basic'    => 'bg-gray-100 text-gray-700',
                        'standard' => 'bg-blue-100 text-blue-700',
                        'premium'  => 'bg-purple-100 text-purple-700',
                    ];
                    $colorClass = $colors[$row->coverage_type] ?? 'bg-gray-100 text-gray-700';

                    return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {$colorClass}\">{$label}</span>";
                }),

            Column::make('Vigencia', 'start_date')
                ->sortable()
                ->label(function ($row) {
                    $start = $row->start_date ? $row->start_date->format('d/m/Y') : '—';
                    $end   = $row->end_date   ? $row->end_date->format('d/m/Y')   : '—';
                    return "{$start} → {$end}";
                }),

            Column::make('Estado', 'is_active')
                ->sortable()
                ->html()
                ->label(function ($row) {
                    if ($row->is_active) {
                        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Activo</span>';
                    }
                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactivo</span>';
                }),

            Column::make('Acciones')
                ->label(function ($row) {
                    return view('admin.insurances.actions', ['insurance' => $row]);
                }),
        ];
    }
}
