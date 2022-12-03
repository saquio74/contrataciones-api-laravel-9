<?php

namespace App\Http\Controllers;

use App\Models\sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    public $validations = [
        "sector" => "required",
    ];
    public function index(Request $request)
    {
        $where = [['sector.deleted_at', '=', null]];

        if ($request->sector) array_push($where, ['sector.sector', 'like', "%$request->sector%"]);
        if ($request->id) array_push($where, ['sector.id', '=', $request->id]);

        $sector = sector::Where($where)
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);

        return response()->json($sector);
    }


    public function store(Request $request)
    {
        $this->ValidarModelo($request, $this->validations);
        $sector = new sector($request->all());
        $this->setBase('created', $sector);
        $sector->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    public function update(Request $request)
    {
        $this->ValidarModelo($request, $this->validations, true);
        $sector = sector::find($request->id);
        $this->setBase('updated', $sector);
        $sector->sector = $request->sector;
        $sector->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    public function destroy(int $id)
    {
        $sector = $this->sectorById($id);
        if (!$sector) return response()->json(["mensaje" => "no se encontro sector"], 422);
        $this->setBase('deleted', $sector);

        $sector->save();

        return response()->json(["mensaje" => "sector borrado correctamente"], 201);
    }
    public function sectorById(int $id)
    {
        $condiciones = [
            ['id', $id],
            ['deleted_at', '=', null]
        ];
        $sector = sector::where($condiciones)->first();
        return $sector;
    }
}
