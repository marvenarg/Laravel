<?php

namespace App\Http\Controllers;

use App\Models\InventarioPermiso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarioPermisoController extends Controller
{
    public function index()
    {
        $permisos = InventarioPermiso::with('invitedUser')
            ->where('owner_user_id', Auth::id())
            ->get();

        $usuarios = User::where('id', '!=', Auth::id())->get();

        return view('permisos.index', compact('permisos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invited_user_id' => 'required|exists:users,id',
            'rol' => 'required|in:lectura,editor,admin',
        ]);

        InventarioPermiso::updateOrCreate(
            [
                'owner_user_id' => Auth::id(),
                'invited_user_id' => $data['invited_user_id'],
            ],
            [
                'rol' => $data['rol'],
            ]
        );

        return redirect()->route('permisos.index')->with('mensaje', 'Permiso guardado correctamente.');
    }

    public function update(Request $request, InventarioPermiso $permiso)
    {
        abort_unless($permiso->owner_user_id === Auth::id(), 403);

        $data = $request->validate([
            'rol' => 'required|in:lectura,editor,admin',
        ]);

        $permiso->update($data);

        return redirect()->route('permisos.index')->with('mensaje', 'Permiso actualizado correctamente.');
    }

    public function destroy(InventarioPermiso $permiso)
    {
        abort_unless($permiso->owner_user_id === Auth::id(), 403);

        $permiso->delete();

        return redirect()->route('permisos.index')->with('mensaje', 'Permiso eliminado correctamente.');
    }
}
