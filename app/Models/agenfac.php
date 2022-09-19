<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class agenfac extends Model
{
    use HasFactory;
    protected $table = 'agenfac';
    protected $primaryKey = 'id';
    protected $fillable = [
        'agente_id',
        'periodo',
        'anio',
        'horas',
        'inc',
        'valor',
        'bonificacion',
        'subtot',
        'bonvalor',
        'total',
        'hospital',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
