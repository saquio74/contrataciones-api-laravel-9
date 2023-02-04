<?php

namespace App\Http\Controllers;

use App\Models\agentes;
use Illuminate\Http\Request;
use App\Http\Controllers\AgenincController as agenincController;
use DateTime;

class AgentesController extends Controller
{
    private $baseSelect = [
        "agentes.id",
        "agentes.legajo",
        "agentes.dni",
        "agentes.nombre",
        "agentes.horario",
        "agentes.telefono",
        "agentes.activo",
        "agentes.created_at",
        "agentes.updated_at",
        "agentes.deleted_at",
        "agentes.created_by",
        "agentes.updated_by",
        "agentes.deleted_by",
        "agentes.hospital_id",
        "agentes.sector_id",
        "agentes.servicio_id"
    ];

    public function baseGetAgentes($select, $where, $orderBy, $with = [])
    {
        array_push($where, ['agentes.deleted_at', '=', null]);
        $agente = agentes::Select($select)
            ->Where($where)
            ->with($with)
            ->join('hospitales', 'hospitales.id', '=', 'agentes.hospital_id')
            ->join('servicio', 'servicio.id', '=', 'agentes.servicio_id')
            ->join('sector', 'sector.id', '=', 'agentes.sector_id')
            ->join('agenfac', 'agenfac.agente_id', '=', 'agentes.id')
            ->distinct();
        if ($orderBy) $agente->orderBy($orderBy);
        return $agente;
    }

    public function index(Request $request)
    {
        return $this->searchAgentes($request, ['sector', 'hospital', 'servicio', 'ageninc.inciso'])
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);
    }

    public function searchAgentes(Request $request, $with = [])
    {
        $where = [];
        if ($request->nombre)
            array_push($where, ['agentes.nombre', 'like', "%$request->nombre%"]);
        if ($request->dni)
            array_push($where, ['agentes.dni', 'like', "%$request->dni%"]);
        if ($request->legajo)
            array_push($where, ['agentes.legajo', 'like', "%$request->legajo%"]);
        if ($request->hospital)
            array_push($where, ['hospitales.hospital', 'like', "%$request->hospital%"]);
        if ($request->servicio)
            array_push($where, ['servicio.servicio', 'like', "%$request->servicio%"]);
        if ($request->sector)
            array_push($where, ['sector.sector', 'like', "%$request->sector%"]);
        if ($request->inciso)
            array_push($where, ['agentes.inciso', 'like', "%$request->inciso%"]);
        if ($request->hospitalId)
            array_push($where, ['hospitales.id', '=', $request->hospitalId]);
        if ($request->servicioId)
            array_push($where, ['servicio.id', '=', $request->servicioId]);
        if ($request->sectorId)
            array_push($where, ['sector.id', '=', $request->sectorId]);

        // DD($now);
        return $this->baseGetAgentes(
            $this->baseSelect,
            $where,
            null,
            $with
        );
    }

    public function store(Request $request)
    {
        $this->validateModel($request);
        $condiciones = [
            ['legajo', '=', $request->legajo],
            ['dni', '=', $request->dni],
            ['deleted_at', '=', null]
        ];

        $agenteExiste = agentes::where($condiciones)->first();
        if ($agenteExiste)
            return response()->json(['message' => 'El agente ya existe'], 422);

        $agente = new agentes($request->all());

        $this->setBase('created', $agente);
        $agente->activo = true;
        $agente->save();

        $this->guardarIncisos($request->incisos, $agente->id);

        return response()->json(['message' => 'Guardado correctamente'], 201);
    }

    public function update(Request $request)
    {
        $this->validateModel($request, "required");
        $agente = $this->getById($request->id);

        if (!$agente)
            return response()->json(["response" => "no se encontro agente"], 422);

        $agente->legajo = $request->legajo;
        $agente->dni = $request->dni;
        $agente->nombre = $request->nombre;
        $agente->hospital_id = $request->hospital_id;
        $agente->servicio_id = $request->servicio_id;
        $agente->sector_id = $request->sector_id;
        $agente->horario = $request->horario;
        $agente->telefono = $request->telefono;


        app(agenincController::class)->deleteIncisoByAgente($agente->id);
        $this->guardarIncisos($request->incisos, $agente->id);
        $this->setBase('updated', $agente);
        $agente->save();
        return response()->json(["agente" => $agente], 200);
    }

    public function destroy(int $id)
    {
        $agente = $this->getById($id);
        if (!$agente)
            return response()->json(["message" => "no se encontro agente"], 422);

        $this->setBase('deleted', $agente);
        app(agenincController::class)->deleteIncisoByAgente($agente->id);
        $agente->save();
        return response()->json(["message" => "Agente borrado correctamente"], 201);
    }

    public function validateModel(Request $request, string $id = "")
    {
        $request->validate([
            "id" => $id,
            "legajo" => "required",
            "dni" => "required",
            "nombre" => "required",
            "hospital_id" => "required",
            "servicio_id" => "required",
            "sector_id" => "required",
            "incisos" => "required"
        ]);
    }

    public function guardarIncisos($arrayIncisos, int $agenteId)
    {
        foreach ($arrayIncisos as $inciso) {
            app(agenincController::class)->store($inciso, $agenteId);
        }
    }

    public function agenteById(int $id)
    {
        $agente = $this->getById($id);
        if ($agente == null)
            return response()->json("No se encontro agente", 422);
        return response()->json($agente, 200);
    }

    public function getById(int $id)
    {
        return $this->baseGetAgentes($this->baseSelect, [
            ['agentes.id', $id],
            ['agentes.deleted_at', '=', null]
        ], null, ['sector', 'hospital', 'servicio', 'ageninc.inciso'])->first();
    }

    public function getServicios(Request $request)
    {
        $request->validate([
            "hospitalId" => 'required'
        ]);
        $where = [['hospitales.id', '=', $request->hospitalId]];
        if ($request->servicio)
            array_push($where, ['servicio.servicio', 'like', "%$request->servicio%"]);

        return $this->baseGetAgentes(['servicio.id', 'servicio.servicio'], $where, 'servicio.servicio')->get();
    }

    public function getSectores(Request $request)
    {
        $request->validate([
            "hospitalId" => 'required',
            "servicioId" => 'required'
        ]);

        $where = [['hospitales.id', '=', $request->hospitalId], ['servicio.id', '=', $request->servicioId]];
        if ($request->sector)
            array_push($where, ['sector.sector', 'like', "%$request->sector%"]);

        return $this->baseGetAgentes(
            ['sector.id', 'sector.sector'],
            $where,
            'sector.sector'
        )->get();
    }

    public function getAgentesLiquidar(Request $request)
    {
        $request->validate([
            "hospitalId" => 'required',
            "servicioId" => 'required',
            "sectorId" => 'required'
        ]);

        return $this->searchAgentes($request, ['sector', 'hospital', 'servicio', 'ageninc.inciso', 'liquidacionActual'])->get();
    }
    public function getLiquidados(Request $request)
    {
        $request->validate([
            "hospitalId" => 'required'
        ]);
        $agentes = $this->searchAgentes($request, ['sector', 'hospital', 'servicio', 'ageninc.inciso', 'liquidacionActual'])->get();
        $agentes = $agentes->sortBy([
            fn ($a, $b) => $a['servicio_id'] <=> $b['servicio_id'],
            fn ($a, $b) => $b['sector_id'] <=> $a['sector_id'],
        ]);

        return $agentes->values()->all();
    }
}
