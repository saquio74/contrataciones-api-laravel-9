<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class agenfac extends Model
{
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
    public function hospitalInfo()
    {
        return $this->belongsTo(hospitales::class, 'hospital');
    }
    public function agente()
    {
        return $this->belongsTo(agentes::class, 'agente_id');
    }
    public function inciso()
    {
        return $this->belongsTo(incisos::class, 'inc', 'id');
    }
}
