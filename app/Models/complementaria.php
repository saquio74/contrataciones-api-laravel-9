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
        'subtotal',
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
    public function hospitalInfo()
    {
        return $this->belongsTo(hospitales::class, 'hospital_id');
    }
    public function agente()
    {
        return $this->belongsTo(agentes::class, 'agente_id');
    }
    public function inciso()
    {
        return $this->belongsTo(incisos::class, 'inciso_id', 'id');
    }
}
