<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\InventarioPermiso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Exports\ProductosExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class ProductoController extends Controller
{
    private function getRolSobreInventario(int $ownerUserId): ?string
    {
        if (Auth::id() === $ownerUserId) {
            return 'owner';
        }

        return InventarioPermiso::where('owner_user_id', $ownerUserId)
            ->where('invited_user_id', Auth::id())
            ->value('rol');
    }

    private function canViewInventario(int $ownerUserId): bool
    {
        return in_array($this->getRolSobreInventario($ownerUserId), ['owner', 'lectura', 'editor', 'admin'], true);
    }

    private function canEditInventario(int $ownerUserId): bool
    {
        return in_array($this->getRolSobreInventario($ownerUserId), ['owner', 'editor', 'admin'], true);
    }

    private function canCreateEnInventario(int $ownerUserId): bool
    {
        return in_array($this->getRolSobreInventario($ownerUserId), ['owner', 'admin'], true);
    }

    private function canDeleteEnInventario(int $ownerUserId): bool
    {
        return in_array($this->getRolSobreInventario($ownerUserId), ['owner', 'admin'], true);
    }

    public function index(Request $request)
    {
        $sharedOwnerIds = InventarioPermiso::where('invited_user_id', Auth::id())
            ->pluck('owner_user_id')
            ->toArray();

        $accessibleOwnerIds = array_unique(array_merge([Auth::id()], $sharedOwnerIds));

        $inventarios = User::whereIn('id', $accessibleOwnerIds)
            ->orderBy('name')
            ->get();

        $selectedOwner = $request->get('owner');

        $query = Producto::with(['owner', 'updatedByUser']);

        if ($selectedOwner && $selectedOwner !== 'todos') {
            abort_unless(in_array((int) $selectedOwner, $accessibleOwnerIds, true), 403);
            $query->where('user_id', $selectedOwner);
        } else {
            $query->whereIn('user_id', $accessibleOwnerIds);
        }

        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', '%' . $request->buscar . '%');
        }

        switch ($request->orden) {
            case 'nombre_asc':
                $query->orderBy('nombre', 'asc');
                break;

            case 'nombre_desc':
                $query->orderBy('nombre', 'desc');
                break;

            case 'antiguos':
                $query->orderBy('created_at', 'asc');
                break;

            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $productos = $query->get();

        return view('productos.index', compact('productos', 'inventarios', 'selectedOwner'));
    }

    public function create()
    {
        $adminOwnerIds = InventarioPermiso::where('invited_user_id', Auth::id())
            ->where('rol', 'admin')
            ->pluck('owner_user_id')
            ->toArray();

        $creatableOwnerIds = array_unique(array_merge([Auth::id()], $adminOwnerIds));

        $inventariosDisponibles = User::whereIn('id', $creatableOwnerIds)
            ->orderBy('name')
            ->get();

        return view('productos.create', compact('inventariosDisponibles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        abort_unless($this->canCreateEnInventario((int) $data['user_id']), 403);

        $data['updated_by'] = Auth::id();

        Producto::create($data);

        return redirect()->route('productos.index', ['owner' => $data['user_id']])
            ->with('mensaje', 'Producto creado correctamente.');
    }

    public function show(Producto $producto)
    {
        abort_unless($this->canViewInventario($producto->user_id), 403);

        $producto->load(['owner', 'updatedByUser']);

        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        abort_unless($this->canEditInventario($producto->user_id), 403);

        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        abort_unless($this->canEditInventario($producto->user_id), 403);

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $data['updated_by'] = Auth::id();

        $producto->update($data);

        return redirect()->route('productos.index')
            ->with('mensaje', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        abort_unless($this->canDeleteEnInventario($producto->user_id), 403);

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('mensaje', 'Producto eliminado correctamente.');
    }

    public function export(Request $request, string $format)
    {
        $format = strtolower($format);

        $allowed = [
            'xlsx' => ExcelFormat::XLSX,
            'csv'  => ExcelFormat::CSV,
            'ods'  => ExcelFormat::ODS,
            'html' => ExcelFormat::HTML,
        ];

        abort_unless(array_key_exists($format, $allowed), 404);

        return Excel::download(
            new ProductosExport(
                $request->get('owner'),
                $request->get('buscar'),
                $request->get('orden')
            ),
            "productos.{$format}",
            $allowed[$format]
        );
    }
}
