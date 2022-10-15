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
        $where = [['permissions.deleted_at', '=', null]];

        if ($request->search) {
            array_push($where, ['permissions.slug', 'like', "%$request->slug%"]);
            array_push($where, ['permissions.name', 'like', "%$request->name%"]);
            array_push($where, ['permissions.descripcion', 'like', "%$request->descripcion%"]);
        }
        if ($request->id) array_push($where, ['permissions.id', '=', $request->id]);

        $permissions = permissions::Where($where)
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);

        return response()->json($permissions);
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
        $permissions->permissions = $request->permissions;
        $permissions->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\permissions  $permissions
     * @return \Illuminate\Http\Response
     */
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
        return $this->findById(new permissions,$id);
    }
}
