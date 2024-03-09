<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class contratos extends Model
{
    protected $table = 'contratos';
    use HasFactory;
    protected $fillable = [
        'id',
        'proveedor_id',
        'especialidad_id',
        'contrato',
        'fecha_inicio',
        'fecha_fin',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    // public function proveedor()
    // {
    //     return $this->hasOne(proveedors::class, 'proveedor_id');
    // }
    // public function Especialidad()
    // {
    //     return $this->hasOne(especialidades::class, 'especialidad_id');
    // }
}
