<?php

namespace App\Http\Controllers;

use App\Models\agenfac;
use App\Models\incisos;
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
        $where = [['agentes.deleted_at','=',null]];
        if($request->nombre) array_push($where,['agentes.nombre','like',"%$request->nombre%"]);
        if($request->hospital) array_push($where,['hospitales.hospital','like',"%$request->hospital%"]);
        if($request->servicio) array_push($where,['servicio.servicio','like',"%$request->servicio%"]);
        if($request->sector) array_push($where,['sector.sector','like',"%$request->sector%"]);
        if($request->inciso) array_push($where,['agentes.inciso','like',"%$request->inciso%"]);
        $request->colums ?? ['*'];
        $agentes = DB::table("agenfac")
                        ->select("agenfac.id","legajo","incisos.inciso","nombre", "hospitales.hospital", "servicio.servicio", "sector.sector",)
                        ->join('agenincs',"agenfac.inc","=","agenincs.inciso_id")
                        ->join('agentes',"agenfac.agente_id","=","agentes.id")
                        ->join('hospitales',"agenfac.hospital","=","hospitales.id")
                        ->join('servicio',"servicio.id","=","agentes.servicio_id")
                        ->join('sector',"agentes.sector_id","=","sector.id")
                        ->join('incisos',"agenincs.inciso_id","=","incisos.id")
                        ->orWhere($where)
                        ->orderBy('sector.id')
                        ->orderBy('servicio.id')
                        ->paginate($request->perPage ??10,$request->columns,'page',$request->page ?? 1);
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
        //
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
    public function update(Request $request, agenfac $agenfac)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\agenfac  $agenfac
     * @return \Illuminate\Http\Response
     */
    public function destroy(agenfac $agenfac)
    {
        //
    }
    public function updateAmount(Request $request){
        $liquidaciones = agenfac::where('PERIODO','=',$request->periodo)->where('ANIO','=',2022)->get();
        $listado = [];
        foreach($liquidaciones as $liquidacionOne){
            array_push($listado,$this->updateFac($liquidacionOne));
        };
        return response()->json($listado);
    }
    public function updateFac(agenfac $liquidacionOne){
        $inciso = incisos::where("ID",$liquidacionOne->INC)->first();
            
        $liquidacionOne->SUBTOT = number_format($liquidacionOne->HORAS * $inciso->VALOR,2,'.',''); 
        $liquidacionOne->BONVALOR = number_format($liquidacionOne->SUBTOT * $liquidacionOne->BONIFICACION / 100,2,'.','');
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
}
