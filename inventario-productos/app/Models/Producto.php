<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'updated_by',
        'nombre',
        'descripcion',
        'precio',
        'stock',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
