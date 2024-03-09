<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseControllers\BaseController;
use App\Models\especialidades;
use Illuminate\Http\Request;

class EspecialidadesController extends BaseController
{
    public $validations = [
        "especialidad" => "required",
        "grado" => "required"
    ];

    public function __construct()
    {
        $this->entity = new especialidades();
    }

    public function toEntity(Request $request)
    {
        $crear = $request->id == null;
        if ($crear) $this->validations["especialidad"] = "required|unique:especialidades";

        $request->validate($this->validations);

        $especialidad = $crear  ? new especialidades() : $this->GetById($request->id);
        $especialidad->grado = $request->grado;
        $especialidad->especialidad = $request->especialidad;
        return $especialidad;
    }
    public function validateData(Request $request, $entity)
    {
        return $entity;
    }
    public function addIncludes()
    {
        return $this->entity;
    }
}
