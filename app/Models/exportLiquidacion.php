<?php

namespace App\Models;

use App\Models\agenfac;

class exportLiquidacion
{
    public int $legajo;
    public string $inciso;
    public string $nombre;
    public int $horas;
    public float $subtotal;
    public float $bonificacion;
    public float $total;
    public string $sector;
    public string $servicio;
    public string $funcion;
    function __construct(agenfac $agenfac)
    {
        $this->legajo = $agenfac->legajo;
        $this->inciso = "inciso {$agenfac->inciso}";
        $this->nombre = $agenfac->nombre;
        $this->horas = $agenfac->horas;
        $this->subtotal = $agenfac->subtot;
        $this->bonificacion = $agenfac->bonvalor;
        $this->total = $agenfac->total;
        $this->sector = $agenfac->sector;
        $this->servicio = $agenfac->servicio;
        $this->funcion = $agenfac->servicio == "Administrativo" ? $agenfac->servicio : $agenfac->sector;
    }
}
