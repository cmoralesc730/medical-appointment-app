<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return view('admin.doctors.index');
    }

    public function create()
    {
        $specialties = Specialty::all();
        $users = User::whereDoesntHave('doctor')->get();
        return view('admin.doctors.create', compact('specialties', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'                => 'required|exists:users,id|unique:doctors,user_id',
            'medical_license_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9]+$/|unique:doctors,medical_license_number',
            'specialty_id'           => 'required|exists:specialties,id',
            'biography'              => 'nullable|string|min:10|max:1000',
        ]);

        Doctor::create($data);

        return redirect(route('admin.doctors.index'))->with('success', 'Doctor creado correctamente');
    }

    public function show(string $id)
    {
        $doctor = Doctor::with('user', 'specialty')->findOrFail($id);
        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(string $id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);
        $specialties = Specialty::all();
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'medical_license_number' => 'nullable|string|min:5|max:20|regex:/^[A-Za-z0-9]+$/|unique:doctors,medical_license_number,' . $id,
            'specialty_id'           => 'nullable|exists:specialties,id',
            'biography'              => 'nullable|string|min:10|max:1000',
        ]);

        $doctor = Doctor::findOrFail($id);
        $doctor->update($data);

        return redirect(route('admin.doctors.index'))->with('success', 'Doctor actualizado correctamente');
    }

    public function destroy(string $id)
    {
        Doctor::findOrFail($id)->delete();

        return redirect(route('admin.doctors.index'))->with('success', 'Doctor eliminado correctamente');
    }
}
