<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models as modelo;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setBase(string $property, $model)
    {
        $model["{$property}_at"] = date('Y-m-d H:i:s');
        $model["{$property}_by"] = Auth()->User()->id;
        return $model;
    }

    public function ValidarModelo(Request $request, array $validations, bool $validateId = false)
    {
        if ($validateId) $validations['id'] = 'required';
        $request->validate($validations);
    }

    public function findById($query, $id)
    {
        return $query->where(
            ['id', $id],
            ['deleted_at', '=', null]
        );
    }
}
