<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInsuranceRequest;
use App\Http\Requests\Admin\UpdateInsuranceRequest;
use App\Models\Insurance;

class InsuranceController extends Controller
{
    /**
     * Muestra el listado de seguros.
     */
    public function index()
    {
        return view('admin.insurances.index');
    }

    /**
     * Muestra el formulario para crear un nuevo seguro.
     */
    public function create()
    {
        $coverageTypes = Insurance::coverageTypeLabels();
        return view('admin.insurances.create', compact('coverageTypes'));
    }

    /**
     * Almacena un nuevo seguro en la base de datos.
     * Las validaciones de negocio se gestionan en StoreInsuranceRequest.
     */
    public function store(StoreInsuranceRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        Insurance::create($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Seguro registrado',
            'text'  => 'El convenio de seguro ha sido registrado correctamente.',
        ]);

        return redirect()->route('admin.insurances.index');
    }

    /**
     * Muestra el detalle de un seguro.
     */
    public function show(Insurance $insurance)
    {
        return view('admin.insurances.show', compact('insurance'));
    }

    /**
     * Muestra el formulario para editar un seguro.
     */
    public function edit(Insurance $insurance)
    {
        $coverageTypes = Insurance::coverageTypeLabels();
        return view('admin.insurances.edit', compact('insurance', 'coverageTypes'));
    }

    /**
     * Actualiza un seguro existente.
     * Las validaciones de negocio se gestionan en UpdateInsuranceRequest.
     */
    public function update(UpdateInsuranceRequest $request, Insurance $insurance)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $insurance->update($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Seguro actualizado',
            'text'  => 'El convenio de seguro ha sido actualizado correctamente.',
        ]);

        return redirect()->route('admin.insurances.edit', $insurance);
    }

    /**
     * Elimina un seguro de la base de datos.
     */
    public function destroy(Insurance $insurance)
    {
        $insurance->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Seguro eliminado',
            'text'  => 'El convenio de seguro ha sido eliminado correctamente.',
        ]);

        return redirect()->route('admin.insurances.index');
    }
}
