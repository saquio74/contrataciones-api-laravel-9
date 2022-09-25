<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class complementaria extends Model
{
    use HasFactory;
    protected $table = 'complementaria';
    protected $fillable = [
        'agente_id',
        'periodo',
        'anio',
        'horas',
        'inciso_id',
        'valor',
        'bonificacion',
        'subtot',
        'bonvalor',
        'total',
        'hospital_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
