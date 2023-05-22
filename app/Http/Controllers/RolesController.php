<?php

namespace App\Http\Controllers;

use App\Models\roles;
use App\Models\User;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public $validations = [
        "roles" => "required",
    ];

    public function index(Request $request)
    {
        return $this->getRoles($request, ['permissionsrole.permissions', 'user'])->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);
    }

    public function getRoles(Request $request, $with = [])
    {
        $where = [['roles.deleted_at', '=', null]];

        if ($request->rol)
            array_push($where, ['roles.name', 'like', "%$request->rol%"]);
        if ($request->description)
            array_push($where, ['roles.description', 'like', "%$request->description%"]);
        if ($request->id)
            array_push($where, ['roles.id', '=', $request->id]);

        return roles::with($with)->Where($where);
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
            return response()->json(["message" => "no se encontro rol"], 422);
        if ($rol->user->count() > 0)
            return response()->json(["message" => "No se puede borrar este rol por que tiene usuarios guardados"], 422);

        $this->setBase('deleted', $rol);

        $rol->save();

        return response()->json(["message" => "rol borrado correctamente"], 201);
    }

    public function rolById(int $id)
    {
        $condiciones = [
            ['id', $id],
            ['deleted_at', '=', null]
        ];
        $rol = roles::with("user")->where($condiciones)->first();
        return $rol;
    }
    public function updateRolUser(Request $request)
    {
        $condiciones = [
            ['id', $request->id]
        ];
        $usuario = User::where($condiciones)->first();
        if (is_null($usuario)) return response()->json(["message" => "No se encontro usuario", 422]);

        $usuario->role_id = $request->role_id ?? 0;
        $usuario->save();

        return response()->json(["message" => "Rol modificado correctamente"], 201);
    }
}
