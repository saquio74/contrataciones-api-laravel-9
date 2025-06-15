<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseControllers\BaseController;
use App\Models\contratos;
use App\Models\proveedors;
use Illuminate\Http\Request;

class ContratosController extends BaseController
{
    public $validations = [
        "proveedor_id" => "required",
        "especialidad_id" => "required",
        "contrato" => "required",
        "fecha_inicio" => "required|date",
        "fecha_fin" => "required|date"
    ];

    public function __construct()
    {
        $this->entity = new contratos();
    }
    public function validateData($request, $entity)
    {
        return $entity;
    }
    public function addIncludes()
    {
        return $this->entity;
    }

    public function toEntity(Request $request)
    {
        $crear = $request->id == null;
        $request->validate($this->validations);

        $contrato = $crear  ? new contratos() : $this->GetById($request->id);

        $proveedores = new proveedors();
        $proveedores::where([['id', '=', $request->proveedor_id], ['deleted_at', '=', null]])->firstOrFail();

        $contrato->proveedor_id = $request->proveedor_id;
        $contrato->especialidad_id = $request->especialidad_id;
        $contrato->contrato = $request->contrato;
        $contrato->fecha_inicio = $request->fecha_inicio;
        $contrato->fecha_fin = $request->fecha_fin;
        return $contrato;
    }
    public function addIncludesById()
    {
        return $this->entity;
    }
}
