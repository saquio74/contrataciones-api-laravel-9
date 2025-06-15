<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseControllers\BaseController;
use App\Models\prestacion;
use Illuminate\Http\Request;

class PrestacionController extends BaseController
{
    public $validations = [
        "nombre" => "required",
        "valor" => "required",
        "especialidad_id" => "required",
        "vigente_desde" => "required|date"
    ];

    public function __construct()
    {
        $this->entity = new prestacion();
    }
    public function addIncludes()
    {
        return $this->entity;
    }
    public function validateData(Request $request, $entity)
    {
        return $entity;
    }
    public function toEntity(Request $request)
    {
        $crear = $request->id == null;

        if (!$crear) {
            $this->validations["vigente_hasta"] = "required|date";
            $request->validate($this->validations);

            $currentPrestacion = $this->entity
                ->where('id', $request->id)
                ->where('vigente_hasta', null)
                ->where('deleted_at', null)
                ->firstOrFail();
            $currentPrestacion->vigente_hasta = $request->vigente_hasta;
            $currentPrestacion->save();
        } else {
            $request->validate($this->validations);
        }

        $prestacion = new prestacion();

        $prestacion->nombre = $request->nombre;
        $prestacion->valor = $request->valor;
        $prestacion->especialidad_id = $request->especialidad_id;
        $prestacion->vigente_desde = $request->vigente_desde;
        return $prestacion;
    }
    public function addIncludesById()
    {
        return $this->entity;
    }
}
