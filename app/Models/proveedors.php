<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proveedors extends Model
{
    use HasFactory;
    protected $fillable = [
        'proveedor',
        'nombre',
        'apellido',
        'dni',
        'cuil',
        'genero',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    public function provhops()
    {
        return $this->hasMany(provhosps::class, 'proveedor_id', 'id');
    }
}
