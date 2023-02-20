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
    private $select = [
        "complementaria.id",
        "legajo",
        "incisos.inciso",
        "inciso_id",
        "agente_id",
        "nombre",
        "fecha",
        "hospitales.hospital",
        "servicio.servicio",
        "sector.sector",
        'periodo',
        'complementaria.hospital_id',
        'complementaria.valor',
        'bonificacion',
        'anio',
        "horas",
        "subtotal",
        "bonvalor",
        "total"
    ];
    private $validationsExport = [
        "hospital_id" => "required",
        "fecha" => "required"
    ];
    private function getComplementaria(Request $request, $with = [], $select = null)
    {
        $select ??= $this->select;
        $where = [['complementaria.deleted_at', '=', null]];
        if ($request->nombre)
            array_push($where, ['agentes.nombre', 'like', "%$request->nombre%"]);
        if ($request->hospital)
            array_push($where, ['hospitales.hospital', 'like', "%$request->hospital%"]);
        if ($request->servicio)
            array_push($where, ['servicio.servicio', 'like', "%$request->servicio%"]);
        if ($request->sector)
            array_push($where, ['sector.sector', 'like', "%$request->sector%"]);
        if ($request->fecha)
            array_push($where, ['fecha', 'like', "%$request->fecha%"]);
        if ($request->hospital_id)
            array_push($where, ['hospitales.id', '=', $request->hospital_id]);
        if ($request->servicioId)
            array_push($where, ['servicio.id', '=', $request->servicioId]);
        if ($request->sectorId)
            array_push($where, ['sector.id', '=', $request->sectorId]);


        $agentes = complementaria::select($select)
            ->join('agentes', "complementaria.agente_id", "=", "agentes.id")
            ->join('hospitales', "complementaria.hospital_id", "=", "hospitales.id")
            ->join('servicio', "servicio.id", "=", "agentes.servicio_id")
            ->join('sector', "agentes.sector_id", "=", "sector.id")
            ->join('incisos', "complementaria.inciso_id", "=", "incisos.id")
            ->where($where)
            ->with($with);

        if ($request->anio) $agentes = $agentes->whereRaw('LOWER(`complementaria`.`anio`) = "' . $request->anio . '"');

        return !is_null($request->periodo) ? $agentes->whereRaw('LOWER(`complementaria`.`periodo`) = "' . $request->periodo . '"') : $agentes;
    }
    public function index(Request $request)
    {
        return $this->getComplementaria($request)
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);
    }

    public function store(Request $request): complementaria
    {
        $this->ValidarModelo($request, $this->validations);
        $liquidacion = new complementaria($request->all());
        $this->liquidarHoras($liquidacion);

        $this->setBase('created', $liquidacion);

        $liquidacion->save();

        return $liquidacion;
    }

    public function update(Request $request)
    {
        $validaciones = $this->validations;

        $this->ValidarModelo($request, $validaciones, true);

        $liquidacion = complementaria::find($request->id);

        if ($liquidacion == null)
            return response()->json(["mensaje" => "No se encontro liquidacion"], 422);
        $liquidacion->horas = $request->horas;
        $liquidacion->bonificacion = $request->bonificacion;
        $this->liquidarHoras($liquidacion);

        $this->setBase('updated', $liquidacion);

        $liquidacion->save();

        return response()->json($liquidacion, 200);
    }
    public function destroy(int $id)
    {
        $facturacion = $this->facturacionById($id);
        if (!$facturacion)
            return response()->json(["mensaje" => "no se encontro facturacion"], 422);
        $this->setBase('deleted', $facturacion);

        $facturacion->save();

        return response()->json(["mensaje" => "facturacion borrada correctamente"], 201);
    }

    public function liquidarHoras(complementaria $liquidacion)
    {
        $inciso = incisos::find($liquidacion->inciso_id);

        $liquidacion->valor = $inciso->valor;
        $liquidacion->subtotal = number_format($liquidacion->horas * $inciso->valor, 2, '.', '');
        $liquidacion->bonvalor = number_format($liquidacion->subtotal * $liquidacion->bonificacion / 100, 2, '.', '');
        $liquidacion->total = $liquidacion->subtotal + $liquidacion->bonvalor;
    }

    public function facturacionById(int $id)
    {
        $condiciones = [
            ['id', $id],
            ['deleted_at', '=', null]
        ];
        $facturacion = complementaria::where($condiciones)->first();
        return $facturacion;
    }

    public function GetPeriodos(Request $request)
    {
        return $this->getComplementaria($request, ['agente', 'hospitalInfo', 'inciso'], [$request->columna])->orderByDesc("complementaria.id")->distinct()->get()
            ->map(fn ($data) => strtolower($data[$request->columna]));
    }

    public function GetLiquidadosComplementaria(Request $request)
    {
        $request->validate($this->validationsExport);
        $agentes = $this->getComplementaria($request, ['agente', 'hospitalInfo', 'inciso'])->get();
        $agentes = $agentes->sortBy([
            ['agente.servicio', 'asc'], ['agente.sector', 'asc'], ['agente.legajo', 'asc']
        ]);
        return $agentes->values();
    }

    public function GuardarLiquidacion(Request $request): bool
    {
        $agenfacs = $request->all();
        $liquidaciones = [];
        foreach ($agenfacs as $liquidacion) {
            $liquidacion = $this->setBase('created', $liquidacion);
            array_push($liquidaciones, $liquidacion);
        };
        complementaria::insert($liquidaciones);
        return true;
    }
    public function GetPDF(Request $request)
    {
        return $this->createPdf($this->GetLiquidadosComplementaria($request));
    }
}
