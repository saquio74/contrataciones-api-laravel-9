<?php

namespace App\Http\Controllers;

use App\Models\permissions;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    public $validations = [
        "permissions" => "required",
    ];
    public function index(Request $request)
    {
        return $this->getPermissions($request)->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);
    }
    public function getPermissions(Request $request)
    {
        $where = [['permissions.deleted_at', '=', null]];

        if ($request->search) {
            array_push($where, ['permissions.slug', 'like', "%$request->slug%"]);
            array_push($where, ['permissions.name', 'like', "%$request->name%"]);
            array_push($where, ['permissions.descripcion', 'like', "%$request->descripcion%"]);
        }
        if ($request->id) array_push($where, ['permissions.id', '=', $request->id]);

        return permissions::Where($where);
    }


    public function store(Request $request)
    {
        $this->ValidarModelo($request, $this->validations);
        $permissions = new permissions($request->all());
        $this->setBase('created', $permissions);
        $permissions->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    public function update(Request $request)
    {
        $this->ValidarModelo($request, $this->validations, true);
        $permissions = permissions::find($request->id);
        $this->setBase('updated', $permissions);
        $permissions->slug = $request->slug;
        $permissions->name = $request->name;
        $permissions->description = $request->description;
        $permissions->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    public function destroy(int $id)
    {
        $permissions = $this->permissionsById($id);
        if (!$permissions) return response()->json(["mensaje" => "no se encontro permissions"], 422);
        $this->setBase('deleted', $permissions);

        $permissions->save();

        return response()->json(["mensaje" => "permissions borrado correctamente"], 201);
    }
    public function permissionsById(int $id)
    {
        return $this->findById(new permissions, $id);
    }
}
