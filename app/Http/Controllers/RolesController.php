<?php

namespace App\Http\Controllers;

use App\Models\roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public $validations = [
        "roles" => "required",
    ];

    public function index(Request $request)
    {
        return $this->getRoles($request)->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);
    }

    public function getRoles(Request $request)
    {
        $where = [['roles.deleted_at', '=', null]];

        if ($request->rol)
            array_push($where, ['roles.name', 'like', "%$request->rol%"]);
        if ($request->description)
            array_push($where, ['roles.description', 'like', "%$request->description%"]);
        if ($request->id)
            array_push($where, ['roles.id', '=', $request->id]);

        return roles::with('permissionsrole.permissions')->Where($where);
    }

    public function store(Request $request)
    {
        $this->ValidarModelo($request, $this->validations);
        $rol = new roles($request->all());
        $this->setBase('created', $rol);
        $rol->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    public function destroy(int $rolId)
    {
        $rol = $this->rolById($rolId);
        if (!$rol)
            return response()->json(["mensaje" => "no se encontro rol"], 422);
        $this->setBase('deleted', $rol);

        $rol->save();

        return response()->json(["mensaje" => "rol borrado correctamente"], 201);
    }

    public function rolById(int $id)
    {
        $condiciones = [
            ['id', $id],
            ['deleted_at', '=', null]
        ];
        $rol = roles::where($condiciones)->first();
        return $rol;
    }
}
