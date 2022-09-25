<?php

namespace App\Http\Controllers;

use App\Models\incisos;
use Illuminate\Http\Request;

class IncisosController extends Controller
{
    public $validations = [
        "inciso" => "required",
        "valor" => "required"
    ];
    public function index(Request $request)
    {
        $where = [['incisos.deleted_at', '=', null]];

        if ($request->inciso) array_push($where, ['incisos.inciso', 'like', "%$request->inciso%"]);
        if ($request->id) array_push($where, ['incisos.id', '=', $request->id]);

        $agentes = incisos::Where($where)
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);

        return response()->json($agentes);
    }


    public function store(Request $request)
    {
        $this->ValidarModelo($request, $this->validations);
        $hospital = new incisos($request->all());
        $this->setBase('created', $hospital);
        $hospital->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    public function update(Request $request)
    {
        $this->ValidarModelo($request, $this->validations, true);
        $inciso = incisos::find($request->id);
        $this->setBase('updated', $inciso);
        $inciso->inciso = $request->inciso;
        $inciso->valor = $request->valor;
        $inciso->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\incisos  $incisos
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $inciso = $this->incisoById($id);
        if (!$inciso) return response()->json(["mensaje" => "no se encontro inciso"], 422);
        $this->setBase('deleted', $inciso);

        $inciso->save();

        return response()->json(["mensaje" => "inciso borrado correctamente"], 201);
    }
    public function incisoById(int $id)
    {
        $condiciones = [
            ['id', $id],
            ['deleted_at', '=', null]
        ];
        $inciso = incisos::where($condiciones)->first();
        return $inciso;
    }
}
