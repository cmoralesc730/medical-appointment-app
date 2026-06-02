<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9]+$/|unique:users',
            'phone' => 'required|string|digits_between:7,15',
            'address' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create($data);

        $user->roles()->attach($data['role_id']);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado correctamente',
            'text' => 'El usuario ha sido creado correctamente',
        ]);

        if($user->hasRole('Paciente')){
            $patient = $user->patient()->create([]);
            return redirect()->route('admin.patients.edit', $patient);
        }

        if($user->hasRole('Doctor')){
            $doctor = $user->doctor()->create([]);
            return redirect()->route('admin.doctors.edit', $doctor);
        }

        return redirect(route('admin.users.index'))->with('success', 'Usuario creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9]+$/|unique:users,id_number,' . $user->id,
            'phone'     => 'required|string|digits_between:7,15',
            'address'   => 'required|string|max:255',
            'role_id'   => 'required|exists:roles,id',
        ]);

        $updateData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'id_number' => $data['id_number'],
            'phone'     => $data['phone'],
            'address'   => $data['address'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        $user->update($updateData);
        $user->roles()->sync([$data['role_id']]);
        $user->refresh();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text'  => 'El usuario ha sido actualizado correctamente',
        ]);

        if ($user->hasRole('Paciente')) {
            $user->doctor()->delete();
            $patient = $user->patient()->firstOrCreate([]);
            return redirect()->route('admin.patients.edit', $patient);
        }

        if ($user->hasRole('Doctor')) {
            $user->patient()->delete();
            $doctor = $user->doctor()->firstOrCreate([]);
            return redirect()->route('admin.doctors.edit', $doctor);
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            abort(403, 'No puedes eliminarte a ti mismo.');
        }

        $user->roles()->detach();
        $user->delete();
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado correctamente',
            'text' => 'El usuario ha sido eliminado correctamente',
        ]);

        return redirect(route('admin.users.index'));
    }
}
