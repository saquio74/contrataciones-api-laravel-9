<?php

namespace App\Exports;

use App\Models\agenfac;
use App\Models\exportLiquidacion;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgenfacExport implements FromArray, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private string $periodo;
    private int $anio;
    private int $hospital_id;

    function __construct($datos)
    {
        $this->periodo      = $datos->periodo;
        $this->anio         = $datos->anio;
        $this->hospital_id  = $datos->hospital_id;
    }

    public function headings(): array
    {
        return [
            "LEGAJO",
            "INCISO",
            "NOMBRE",
            "HORAS",
            "SUBTOTAL",
            "BONIFICACION",
            "TOTAL",
            "SECTOR",
            "SERVICIO",
            "FUNCION"
        ];
    }

    public function array(): array
    {

        $coleccion = agenfac::select('agentes.legajo', 'incisos.inciso', 'agentes.nombre', 'horas', 'subtot', 'bonvalor', 'total', 'sector.sector', 'servicio.servicio')
            ->join('agentes', 'agentes.id', '=', 'agenfac.agente_id')
            ->join('incisos', 'incisos.id', '=', 'agenfac.inc')
            ->join('sector', 'sector.id', '=', 'agentes.sector_id')
            ->join('servicio', 'servicio.id', '=', 'agentes.servicio_id')
            ->orderBy('agentes.legajo')
            ->where([['periodo', '=', $this->periodo], ['anio', '=', $this->anio], ['hospital_id', '=', $this->hospital_id]])
            ->get();

        $coleccion = $coleccion->map(fn ($data) => (new exportLiquidacion($data)));

        return $coleccion->values()->all();
    }
}
