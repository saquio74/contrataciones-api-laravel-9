<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class prestacion extends Model
{
    use HasFactory;
    protected $table = 'prestacion';

    protected $fillable = [
        'id',
        'nombre',
        'valor',
        'especialidad_id',
        'vigente_desde,',
        'vigente_hasta',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
