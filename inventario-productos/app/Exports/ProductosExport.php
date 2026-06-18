<?php

namespace App\Exports;

use App\Models\InventarioPermiso;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductosExport implements FromCollection, WithHeadings
{
    protected ?string $owner;
    protected ?string $buscar;
    protected ?string $orden;

    public function __construct(?string $owner = null, ?string $buscar = null, ?string $orden = null)
    {
        $this->owner = $owner;
        $this->buscar = $buscar;
        $this->orden = $orden;
    }

    public function collection()
    {
        $sharedOwnerIds = InventarioPermiso::where('invited_user_id', Auth::id())
            ->pluck('owner_user_id')
            ->toArray();

        $accessibleOwnerIds = array_unique(array_merge([Auth::id()], $sharedOwnerIds));

        $query = Producto::with(['owner', 'updatedByUser']);

        if ($this->owner && $this->owner !== 'todos') {
            abort_unless(in_array((int) $this->owner, $accessibleOwnerIds, true), 403);
            $query->where('user_id', $this->owner);
        } else {
            $query->whereIn('user_id', $accessibleOwnerIds);
        }

        if ($this->buscar) {
            $query->where('nombre', 'like', '%' . $this->buscar . '%');
        }

        switch ($this->orden) {
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

        return $query->get()->map(function ($producto) {
            return [
                'propietario' => $producto->owner?->name ?? 'Sin registro',
                'ultima_edicion_por' => $producto->updatedByUser?->name ?? 'Sin registro',
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precio,
                'stock' => $producto->stock,
                'creado_en' => optional($producto->created_at)?->format('Y-m-d H:i:s'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Propietario',
            'Última edición por',
            'Nombre',
            'Descripción',
            'Precio',
            'Stock',
            'Creado en',
        ];
    }
}
