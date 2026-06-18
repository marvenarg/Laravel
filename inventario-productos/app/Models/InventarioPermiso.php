<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioPermiso extends Model
{
    protected $fillable = [
        'owner_user_id',
        'invited_user_id',
        'rol',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function invitedUser()
    {
        return $this->belongsTo(User::class, 'invited_user_id');
    }
}
