<?php

namespace App\Http\Controllers;

use App\Models\agenfac;
use App\Models\incisos;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AgenfacController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where = [['agentes.deleted_at', '=', null]];
        if ($request->nombre) array_push($where, ['agentes.nombre', 'like', "%$request->nombre%"]);
        if ($request->hospital) array_push($where, ['hospitales.hospital', 'like', "%$request->hospital%"]);
        if ($request->servicio) array_push($where, ['servicio.servicio', 'like', "%$request->servicio%"]);
        if ($request->sector) array_push($where, ['sector.sector', 'like', "%$request->sector%"]);
        if ($request->hospitalId) array_push($where, ['hospitales.id', '=', $request->hospitalId]);
        if ($request->servicioId) array_push($where, ['servicio.id', '=', $request->servicioId]);
        if ($request->sectorId) array_push($where, ['sector.id', '=', $request->sectorId]);
        if ($request->periodo) array_push($where, ['agenfac.periodo', '=', $request->periodo]);
        if ($request->anio) array_push($where, ['agenfac.anio', '=', $request->anio]);


        $agentes = DB::table("agenfac")
            ->select("agenfac.id", "legajo", "incisos.inciso", "nombre", "hospitales.hospital", "servicio.servicio", "sector.sector", 'periodo', 'anio', "horas", "subtot", "bonvalor", "total")
            ->join('agenincs', "agenfac.inc", "=", "agenincs.inciso_id")
            ->join('agentes', "agenfac.agente_id", "=", "agentes.id")
            ->join('hospitales', "agenfac.hospital", "=", "hospitales.id")
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validateModel($request);
        $liquidacion = new agenfac($request->all());
        $this->liquidarHoras($liquidacion);
        
        $this->setBase('created', $liquidacion);

        $liquidacion->save();

        return response()->json($liquidacion, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\agenfac  $agenfac
     * @return \Illuminate\Http\Response
     */
    public function show(agenfac $agenfac)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\agenfac  $agenfac
     * @return \Illuminate\Http\Response
     */
    public function edit(agenfac $agenfac)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\agenfac  $agenfac
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validateModel($request, 'required');
        $liquidacion = agenfac::find($request->id);
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
     * @param  \App\Models\agenfac  $agenfac
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
    public function updateAmount(Request $request)
    {
        $liquidaciones = agenfac::where('PERIODO', '=', $request->periodo)->where('ANIO', '=', 2022)->get();
        $listado = [];
        foreach ($liquidaciones as $liquidacionOne) {
            array_push($listado, $this->updateFac($liquidacionOne));
        };
        return response()->json($listado);
    }
    public function updateFac(agenfac $liquidacionOne)
    {
        $inciso = incisos::where("ID", $liquidacionOne->INC)->first();

        $liquidacionOne->SUBTOT = number_format($liquidacionOne->HORAS * $inciso->VALOR, 2, '.', '');
        $liquidacionOne->BONVALOR = number_format($liquidacionOne->SUBTOT * $liquidacionOne->BONIFICACION / 100, 2, '.', '');
        $liquidacionOne->TOTAL = $liquidacionOne->SUBTOT + $liquidacionOne->BONVALOR;

        $liquidacion = new agenfac();
        $liquidacion->LEG = $liquidacionOne->LEG;
        $liquidacion->PERIODO = "JULIO-NUEVO";

        $liquidacion->ANIO = $liquidacionOne->ANIO;
        $liquidacion->HORAS = $liquidacionOne->HORAS;
        $liquidacion->INC = $liquidacionOne->INC;
        $liquidacion->VALOR = $liquidacionOne->VALOR;
        $liquidacion->BONIFICACION = $liquidacionOne->BONIFICACION;
        $liquidacion->SUBTOT = $liquidacionOne->SUBTOT;
        $liquidacion->BONVALOR = $liquidacionOne->BONVALOR;
        $liquidacion->TOTAL = $liquidacionOne->TOTAL;
        $liquidacion->HOSPITAL = $liquidacionOne->HOSPITAL;
        $liquidacion->save();
        return $liquidacion;
    }
    public function validateModel(Request $request, string $id = "")
    {
        $request->validate([
            "id" => $id,
            "agente_id" => "required",
            "periodo" => "required",
            "anio" => "required",
            "hospital" => "required",
            "horas" => "required",
            "inc" => "required",
            "bonificacion" => "required"

        ]);
    }
    public function liquidarHoras(agenfac $liquidacion)
    {
        $inciso = incisos::find($liquidacion->inc);
        $liquidacion->valor = $inciso->valor;
        $liquidacion->subtot = number_format($liquidacion->horas * $inciso->valor, 2, '.', '');
        $liquidacion->bonvalor = number_format($liquidacion->subtot * $liquidacion->bonificacion / 100, 2, '.', '');
        $liquidacion->total = $liquidacion->subtot + $liquidacion->bonvalor;
        
    }
    public function facturacionById(int $id){
        $condiciones = [
            ['id',$id],
            ['deleted_at','=',null]
        ];
        $facturacion = agenfac::where($condiciones)->first();
        return $facturacion;
    }
    public function AgenteByIdResponse(int $id){
        $facturacion = $this->facturacionById($id);
        return response()->json(["facturacion"=> $facturacion],200);
    }
}
