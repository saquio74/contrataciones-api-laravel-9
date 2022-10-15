<?php

namespace App\Http\Controllers;

use App\Models\servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public $validations = [
        "servicio" => "required",
    ];
    public function index(Request $request)
    {
        $where = [['servicio.deleted_at', '=', null]];

        if ($request->servicio) array_push($where, ['servicio.servicio', 'like', "%$request->servicio%"]);
        if ($request->id) array_push($where, ['servicio.id', '=', $request->id]);

        $servicio = servicio::Where($where)
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);

        return response()->json($servicio);
    }


    public function store(Request $request)
    {
        $this->ValidarModelo($request, $this->validations);
        $servicio = new servicio($request->all());
        $this->setBase('created', $servicio);
        $servicio->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    public function update(Request $request)
    {
        $this->ValidarModelo($request, $this->validations, true);
        $servicio = servicio::find($request->id);
        $this->setBase('updated', $servicio);
        $servicio->servicio = $request->servicio;
        $servicio->save();

        return response()->json(["message" => "Guardado correctamente"], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\servicio  $servicio
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $servicio = $this->servicioById($id);
        if (!$servicio) return response()->json(["mensaje" => "no se encontro servicio"], 422);
        $this->setBase('deleted', $servicio);

        $servicio->save();

        return response()->json(["mensaje" => "servicio borrado correctamente"], 201);
    }
    public function servicioById(int $id)
    {
        $condiciones = [
            ['id', $id],
            ['deleted_at', '=', null]
        ];
        $servicio = servicio::where($condiciones)->first();
        return $servicio;
    }
}
