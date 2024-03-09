<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseControllers\BaseController;
use App\Models\proveedors;
use Illuminate\Http\Request;

class ProveedorsController extends BaseController
{

    public function __construct()
    {
        $this->entity = new proveedors();
    }
    private $validations = [
        "cuil" => "required",
        "proveedor" => "required",
        "nombre" => "required",
        "apellido" => "required",
        "dni" => "required",
        "genero" => "required"
    ];

    public function validateData(Request $request, $entity)
    {
        $where = [];
        if ($request->nombre)
            // $entity->where();
            $entity->whereRaw('(LOWER(CONCAT(TRIM(`proveedors`.`nombre`)," ",TRIM(`proveedors`.`apellido`))) like "%' . strtolower($request->nombre) . '%" or
                                LOWER(CONCAT(TRIM(`proveedors`.`apellido`)," ",TRIM(`proveedors`.`nombre`))) like "%' . strtolower($request->nombre) . '%")');
        if ($request->dni) array_push($where, ['proveedors.dni', 'like', "%$request->dni%"]);

        return $entity->where($where);
    }

    public function addIncludes()
    {
        return $this->entity->with("provhops.hospital");
    }

    public function toEntity(Request $request)
    {
        // dd($this->validations);
        $crear = $request->id == null || $request->id == 0;

        if ($crear) $this->validations["cuil"] = "required|unique:proveedors";

        $request->validate($this->validations);

        $proveedor = $crear  ? new proveedors() : $this->GetById($request->id);

        $proveedor->proveedor = $request->proveedor;
        $proveedor->nombre = $request->nombre;
        $proveedor->apellido = $request->apellido;
        $proveedor->dni = $request->dni;
        $proveedor->cuil = $request->cuil;
        $proveedor->genero = $request->genero;
        return $proveedor;
    }
}
