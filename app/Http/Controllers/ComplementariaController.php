<?php

namespace App\Http\Controllers;

use App\Models\complementaria;
use App\Models\incisos;
use ArrayObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ComplementariaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $validations = [
            "agente_id" => "required",
            "periodo" => "required",
            "anio" => "required",
            "hospital_id" => "required",
            "horas" => "required",
            "inciso_id" => "required",
            "bonificacion" => "required"

        ];
    public function index(Request $request)
    {
        $where = [['complementaria.deleted_at', '=', null]];
        if ($request->nombre) array_push($where, ['agentes.nombre', 'like', "%$request->nombre%"]);
        if ($request->hospital) array_push($where, ['hospitales.hospital', 'like', "%$request->hospital%"]);
        if ($request->servicio) array_push($where, ['servicio.servicio', 'like', "%$request->servicio%"]);
        if ($request->sector) array_push($where, ['sector.sector', 'like', "%$request->sector%"]);
        if ($request->hospitalId) array_push($where, ['hospitales.id', '=', $request->hospitalId]);
        if ($request->servicioId) array_push($where, ['servicio.id', '=', $request->servicioId]);
        if ($request->sectorId) array_push($where, ['sector.id', '=', $request->sectorId]);
        if ($request->periodo) array_push($where, ['complementaria.periodo', '=', $request->periodo]);
        if ($request->anio) array_push($where, ['complementaria.anio', '=', $request->anio]);


        $agentes = DB::table("complementaria")
            ->select("complementaria.id", "legajo", "incisos.inciso", "nombre", "hospitales.hospital", "servicio.servicio", "sector.sector", 'periodo', 'anio', "horas", "subtotal", "bonvalor", "total")
            ->join('agenincs', "complementaria.inciso_id", "=", "agenincs.inciso_id")
            ->join('agentes', "complementaria.agente_id", "=", "agentes.id")
            ->join('hospitales', "complementaria.hospital_id", "=", "hospitales.id")
            ->join('servicio', "servicio.id", "=", "agentes.servicio_id")
            ->join('sector', "agentes.sector_id", "=", "sector.id")
            ->join('incisos', "agenincs.inciso_id", "=", "incisos.id")
            ->orWhere($where)
            ->orderBy('sector.id')
            ->orderBy('servicio.id')
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);

        return response()->json($agentes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->ValidarModelo($request,$this->validations);
        $liquidacion = new complementaria($request->all());
        $this->liquidarHoras($liquidacion);
        
        $this->setBase('created', $liquidacion);

        $liquidacion->save();

        return response()->json($liquidacion, 200);
    }

    public function update(Request $request)
    {
        $validaciones = $this->validations;
        
        $this->ValidarModelo($request, $validaciones, true);

        $liquidacion = complementaria::find($request->id);

        if ($liquidacion == null) return response()->json(["mensaje" => "No se encontro liquidacion"], 422);
        $liquidacion->horas = $request->horas;
        $liquidacion->bonificacion = $request->bonificacion;
        $this->liquidarHoras($liquidacion);
        
        $this->setBase('updated', $liquidacion);

        $liquidacion->save();

        return response()->json($liquidacion, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\complementaria  $complementaria
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $facturacion = $this->facturacionById($id);
        if(!$facturacion) return response()->json(["mensaje"=>"no se encontro facturacion"],422);
        $this->setBase('deleted',$facturacion);
        
        $facturacion->save();

        return response()->json(["mensaje"=>"facturacion borrada correctamente"],201);
    }

    public function liquidarHoras(complementaria $liquidacion)
    {
        $inciso = incisos::find($liquidacion->inciso_id);

        $liquidacion->valor = $inciso->valor;
        $liquidacion->subtotal = number_format($liquidacion->horas * $inciso->valor, 2, '.', '');
        $liquidacion->bonvalor = number_format($liquidacion->subtotal * $liquidacion->bonificacion / 100, 2, '.', '');
        $liquidacion->total = $liquidacion->subtotal + $liquidacion->bonvalor;
        
    }
    public function facturacionById(int $id){
        $condiciones = [
            ['id',$id],
            ['deleted_at','=',null]
        ];
        $facturacion = complementaria::where($condiciones)->first();
        return $facturacion;
    }
}
