<?php

namespace App\Http\Controllers;

use App\Models\agentes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AgenincController as agenincController;


class AgentesController extends Controller
{
    public function index(Request $request)
    {   
        
        $where = [['agentes.deleted_at','=',null]];
        if($request->nombre) array_push($where,['agentes.nombre','like',"%$request->nombre%"]);
        if($request->hospital) array_push($where,['hospitales.hospital','like',"%$request->hospital%"]);
        if($request->servicio) array_push($where,['servicio.servicio','like',"%$request->servicio%"]);
        if($request->sector) array_push($where,['sector.sector','like',"%$request->sector%"]);
        if($request->inciso) array_push($where,['agentes.inciso','like',"%$request->inciso%"]);
        $agentes = DB::table("agentes")
                        ->select("agentes.id","legajo","dni","nombre","incisos.inciso", "hospitales.hospital", "servicio.servicio", "sector.sector","activo")
                        ->join('agenincs',"agentes.id","=","agenincs.agente_id")
                        ->join('hospitales',"agentes.hospital_id","=","hospitales.id")
                        ->join('servicio',"servicio.id","=","agentes.servicio_id")
                        ->join('sector',"agentes.sector_id","=","sector.id")
                        ->join('incisos',"agenincs.inciso_id","=","incisos.id")
                        ->orWhere($where)
                        ->paginate($request->perPage ??10,['*'],'page',$request->page ?? 1);
        return response()->json($agentes);
    }
    
    public function store(Request $request)
    {
        $this->validateModel($request);
        $condiciones = [
            ['legajo','=',$request->legajo],
            ['dni','=',$request->dni],
            ['deleted_at','=',null]
        ];
        
        $agenteExiste = agentes::where($condiciones)->first();
        if($agenteExiste) return response()->json(['mensaje'=>'El agente ya existe'],422);
        
        $agente = new agentes($request->all());
        
        $this->setBase('created',$agente);
        $agente->activo = true;
        $agente->save();
        
        $this->guardarIncisos(json_decode($request->incisos),$agente->id);

        return response()->json(['mensaje'=>'Guardado correctamente'],201);
    }

    public function update(Request $request)
    {
        $this->validateModel($request,"required");
        $agente = $this->agenteById($request->id);
        
        if(!$agente) return response()->json(["response"=>"no se encontro agente"],422);
        
        $agente->legajo = $request->legajo;
        $agente->dni = $request->dni;
        $agente->nombre = $request->nombre;
        $agente->hospital_id = $request->hospital_id;
        $agente->servicio_id = $request->servicio_id;
        $agente->sector_id = $request->sector_id;
        $agente->horario = $request->horario;
        $agente->telefono = $request->telefono;

        app(agenincController::class)->deleteIncisoByAgente($agente->id);
        $this->guardarIncisos($request->incisos,$agente->id);
        $this->setBase('updated',$agente);
        $agente->save();
        return response()->json(["agente"=>$agente],200);
    }

    public function destroy(int $id)
    {
        $agente = $this->agenteById($id);
        if(!$agente) return response()->json(["mensaje"=>"no se encontro agente"],422);

        $this->setBase('deleted',$agente);
        app(agenincController::class)->deleteIncisoByAgente($agente->id);
        $agente->save();
        return response()->json(["mensaje"=>"Agente borrado correctamente"],201);
    }

    public function validateModel(Request $request, string $id=""){
        $request->validate([
            "id"=>$id,
            "legajo"=>"required",
            "dni"=>"required",
            "nombre"=>"required",
            "hospital_id"=>"required",
            "servicio_id"=>"required",
            "sector_id"=>"required",
            "incisos"=>"required"
        ]);
    }

    public function guardarIncisos($arrayIncisos, int $agenteId)
    {
        foreach($arrayIncisos as $inciso){
            app(agenincController::class)->store($inciso,$agenteId);
        }
    }

    public function AgenteByIdResponse(int $id){
        $agente = $this->agenteById($id);
        $agente->incisos = app(agenincController::class)->incisoArrayInt($id);
        return response()->json(["agente"=> $agente],200);
    }


    public function agenteById(int $id){
        $condiciones = [
            ['id',$id],
            ['agentes.deleted_at','=',null]
        ];
        $agente = agentes::where($condiciones)->first();
        return $agente;
    }
}
