<?php

namespace App\Http\Controllers;

use App\Models\hospitales;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HospitalesController extends Controller
{
    public $validations = [
        "hospital" => "required"
    ];
    public function index(Request $request)
    {
        $where = [['hospitales.deleted_at', '=', null]];
        if ($request->hospital)
            array_push($where, ['hospitales.hospital', 'like', "%$request->hospital%"]);
        if ($request->id)
            array_push($where, ['hospitales.id', '=', $request->id]);

        $agentes = hospitales::Where($where)
            ->orderBy('hospitales.hospital')
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);

        return response()->json($agentes);
    }

    public function store(Request $request)
    {

        $this->ValidarModelo($request, $this->validations);
        $hospital = new hospitales($request->all());
        $this->setBase('created', $hospital);
        $hospital->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    public function update(Request $request)
    {
        $this->ValidarModelo($request, $this->validations, true);
        $hospital = hospitales::find($request->id);
        if ($hospital == null)
            return response()->json(["mensaje" => "No se encontro liquidacion"], 422);
        $hospital->hospital = $request->hospital;
        $this->setBase('updated', $hospital);
        $hospital->save();

        return response()->json(["message" => "Se ha modificado correctamente"], 201);
    }

    public function destroy(int $hospitalId)
    {
        $hospital = $this->hospitalById($hospitalId);
        if (!$hospital)
            return response()->json(["mensaje" => "no se encontro hospital"], 422);
        $this->setBase('deleted', $hospital);

        $hospital->save();

        return response()->json(["mensaje" => "hospital borrado correctamente"], 201);
    }

    public function hospitalById(int $id)
    {
        $condiciones = [
            ['id', $id],
            ['deleted_at', '=', null]
        ];
        $hospital = hospitales::where($condiciones)->first();
        return $hospital;
    }
}
