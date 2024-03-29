<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class agentes extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id',
        'legajo',
        'dni',
        'nombre',
        'horario',
        'telefono',
        'activo',
        'hospital_id',
        'sector_id',
        'servicio_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function hospital()
    {
        return $this->belongsTo(hospitales::class, 'hospital_id');
    }

    public function sector()
    {
        return $this->belongsTo(sector::class, 'sector_id');
    }

    public function servicio()
    {
        return $this->belongsTo(servicio::class, 'servicio_id');
    }

    public function ageninc()
    {
        return $this->hasMany(ageninc::class, 'agente_id', 'id');
    }

    public function liquidacionActual()
    {
        $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        $mesLiquidacion = Date("n") - 2;
        $now = ($months[$mesLiquidacion >= 0 ? $mesLiquidacion : 11]);
        $anio = $mesLiquidacion >= 0 ? date('Y') : date('Y') - 1;
        return $this->hasMany(agenfac::class, 'agente_id', 'id')->where([
            ['agenfac.anio', '=', $anio]
        ])->whereRaw('LOWER(`agenfac`.`periodo`) = "' . $now . '"');
    }

    public function agenfac()
    {
        return $this->hasMany(agenfac::class, 'agente_id', 'id');
    }
}
