<?php

namespace App\Http\Controllers;

use App\Models\permissions;
use App\Http\Controllers\BaseControllers\BaseController;
use Illuminate\Http\Request;

class PermissionsController extends BaseController
{
    public $validations = [
        "slug" => "required",
        "name" => "required",
        "description" => "required",
    ];

    public function __construct()
    {
        $this->entity = new permissions();
    }
    public function validateData(Request $request, $entity)
    {
        $where = [['permissions.deleted_at', '=', null]];

        if ($request->slug)
            array_push($where, ['permissions.slug', 'like', "%$request->slug%"]);
        if ($request->name)
            array_push($where, ['permissions.name', 'like', "%$request->name%"]);
        if ($request->description)
            array_push($where, ['permissions.description', 'like', "%$request->description%"]);
        if ($request->id) array_push($where, ['permissions.id', '=', $request->id]);

        return $entity->Where($where);
    }

    public function addIncludes()
    {
        return $this->entity->with("permissionsrole.roles");
    }
    public function addIncludesById()
    {
        return $this->entity->with("permissionsrole.roles");
    }

    public function toEntity(Request $request)
    {
        $crear = $request->id == null;
        if ($crear) $this->validations["slug"] = "required|unique:permissions";

        $request->validate($this->validations);

        $permissions = $crear ? new permissions() : $this->GetById($request->id);

        $permissions->slug = $request->slug;
        $permissions->name = $request->name;
        $permissions->description = $request->description;
        $permissions->save();
        return $permissions;
    }
}
