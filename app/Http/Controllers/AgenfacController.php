<?php

namespace App\Http\Controllers;

use App\Models\agenfac;
use App\Models\incisos;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class AgenfacController extends Controller
{
    private $validations = [
        "agente_id" => "required",
        "periodo" => "required",
        "anio" => "required",
        "hospital" => "required",
        "horas" => "required",
        "inc" => "required",
        "bonificacion" => "required"
    ];
    private $select = [
        "agenfac.id",
        "legajo",
        "codigo",
        "incisos.inciso",
        "inc",
        "agente_id",
        "nombre",
        "hospitales.hospital",
        "servicio.servicio",
        "sector.sector",
        'periodo',
        'agenfac.valor',
        'bonificacion',
        'agenfac.hospital',
        'anio',
        "horas",
        "subtot",
        "bonvalor",
        "total"
    ];
    private $validationsExport = [
        "hospital_id" => "required",
        "periodo" => "required",
        "anio" => "required"
    ];
    private function getLiquidacion(Request $request, $with = [], $select = null)
    {
        $select ??= $this->select;
        $where = [['agenfac.deleted_at', '=', null]];
        if ($request->nombre)
            array_push($where, ['agentes.nombre', 'like', "%$request->nombre%"]);
        if ($request->hospital)
            array_push($where, ['hospitales.hospital', 'like', "%$request->hospital%"]);
        if ($request->servicio)
            array_push($where, ['servicio.servicio', 'like', "%$request->servicio%"]);
        if ($request->sector)
            array_push($where, ['sector.sector', 'like', "%$request->sector%"]);
        if ($request->hospital_id)
            array_push($where, ['hospitales.id', '=', $request->hospital_id]);
        if ($request->servicioId)
            array_push($where, ['servicio.id', '=', $request->servicioId]);
        if ($request->sectorId)
            array_push($where, ['sector.id', '=', $request->sectorId]);


        $agentes = agenfac::select($select)
            ->join('agentes', "agenfac.agente_id", "=", "agentes.id")
            ->join('hospitales', "agenfac.hospital", "=", "hospitales.id")
            ->join('servicio', "servicio.id", "=", "agentes.servicio_id")
            ->join('sector', "agentes.sector_id", "=", "sector.id")
            ->join('incisos', "agenfac.inc", "=", "incisos.id")
            ->where($where)
            ->with($with);

        if ($request->anio) $agentes = $agentes->whereRaw('LOWER(`agenfac`.`anio`) = "' . $request->anio . '"');

        return !is_null($request->periodo) ? $agentes->whereRaw('LOWER(`agenfac`.`periodo`) = "' . $request->periodo . '"') : $agentes;
    }
    public function index(Request $request)
    {
        return $this->getLiquidacion($request)
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);
    }

    public function store(Request $request)
    {

        $this->ValidarModelo($request, $this->validations);
        $liquidacion = new agenfac($request->all());
        $this->liquidarHoras($liquidacion);

        $this->setBase('created', $liquidacion);

        $liquidacion->save();

        return response()->json($liquidacion, 200);
    }

    public function update(Request $request)
    {
        $this->ValidarModelo($request, $this->validations, true);
        $liquidacion = agenfac::find($request->id);
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
        $inciso = incisos::find($liquidacionOne->INC);

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
    public function liquidarHoras(agenfac $liquidacion)
    {
        $inciso = incisos::find($liquidacion->inc);
        $liquidacion->valor = $inciso->valor;
        $liquidacion->subtot = number_format($liquidacion->horas * $inciso->valor, 2, '.', '');
        $liquidacion->bonvalor = number_format($liquidacion->subtot * $liquidacion->bonificacion / 100, 2, '.', '');
        $liquidacion->total = $liquidacion->subtot + $liquidacion->bonvalor;
    }
    public function facturacionById(int $id)
    {
        $condiciones = [
            ['id', $id],
            ['deleted_at', '=', null]
        ];
        $facturacion = agenfac::where($condiciones)->first();
        if (is_null($facturacion)) return response()->json(['message' => 'no se encontro liquidacion'], 422);
        return $facturacion;
    }

    public function GuardarLiquidacion(Request $request): bool
    {
        $agenfacs = $request->all();
        $liquidaciones = [];
        foreach ($agenfacs as $liquidacion) {
            $liquidacion = $this->setBase('created', $liquidacion);
            array_push($liquidaciones, $liquidacion);
        };
        agenfac::insert($liquidaciones);
        return true;
    }
    public function GetLiquidados(Request $request)
    {
        $agentes = $this->getLiquidacion($request, ['agente', 'hospitalInfo', 'inciso'])->get();
        $agentes = $agentes->sortBy([
            ['agente.servicio', 'asc'],
            ['agente.sector', 'asc'],
            ['agente.legajo', 'asc']
        ]);
        return $agentes->values();
    }
    public function GetPeriodos(Request $request)
    {
        return $this->getLiquidacion($request, [], [$request->columna])->distinct()->get()
            ->map(fn($data) => strtolower($data[$request->columna]))->sortDesc()->values()->all();
    }

    public function generarPDF(Request $request)
    {
        $request->validate($this->validationsExport);
        $getLiquidados = $this->GetLiquidados($request);
        if ($getLiquidados->count() > 0)
            return $this->createPdf($getLiquidados);
        abort(400, "Sin resultados");
    }
    public function generarExcelV2(Request $request)
    {
        $request->validate($this->validationsExport);

        $facturacion = agenfac::select(
            'agentes.legajo',
            'incisos.inciso',
            'agentes.codigo',
            'agentes.nombre',
            'horas',
            'subtot',
            'bonvalor',
            'total',
            'sector.sector',
            'servicio.servicio'
        )
            ->join('agentes', 'agentes.id', '=', 'agenfac.agente_id')
            ->join('incisos', 'incisos.id', '=', 'agenfac.inc')
            ->join('sector', 'sector.id', '=', 'agentes.sector_id')
            ->join('servicio', 'servicio.id', '=', 'agentes.servicio_id')
            ->orderBy('agentes.legajo')
            ->where([['periodo', '=', $request->periodo], ['anio', '=', $request->anio], ['hospital_id', '=', $request->hospital_id]])
            ->get();



        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Datos de ejemplo
        $row = 2;

        $sheet->setTitle("Liquidacion {$request->periodo} {$request->anio}");
        $sheet->setCellValue('A1', 'Legajo'); // This is where you set the column header
        $sheet->setCellValue('B1', 'Inciso'); // This is where you set the column header
        $sheet->setCellValue('C1', 'Código'); // This is where you set the column header
        $sheet->setCellValue('D1', 'Nombre'); // This is where you set the column header
        $sheet->setCellValue('E1', 'Horas'); // This is where you set the column header
        $sheet->setCellValue('F1', 'Subtotal'); // This is where you set the column header
        $sheet->setCellValue('G1', 'Bonificacion'); // This is where you set the column header
        $sheet->setCellValue('H1', 'Total'); // This is where you set the column header
        $sheet->setCellValue('I1', 'Sector'); // This is where you set the column header
        $sheet->setCellValue('J1', 'Servicio'); // This is where you set the column header
        $sheet->setCellValue('K1', 'Funcion'); // This is where you set the column header
        foreach ($facturacion as $liquidado) {
            $sheet->setCellValue('A' . $row, $liquidado->legajo);
            $sheet->setCellValue('B' . $row, "inciso {$liquidado->inciso}");
            $sheet->setCellValue('C' . $row, "COD {$liquidado->codigo}");
            $sheet->setCellValue('D' . $row, $liquidado->nombre);
            $sheet->setCellValue('E' . $row, $liquidado->horas);
            $sheet->setCellValue('F' . $row, $liquidado->subtot);
            $sheet->setCellValue('G' . $row, $liquidado->bonvalor);
            $sheet->setCellValue('H' . $row, $liquidado->total);
            $sheet->setCellValue('I' . $row, $liquidado->sector);
            $sheet->setCellValue('J' . $row, $liquidado->servicio);
            $sheet->setCellValue('K' . $row, $liquidado->servicio == "Administrativo" ? $liquidado->servicio : $liquidado->sector);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = "reporte.xlsx";
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
