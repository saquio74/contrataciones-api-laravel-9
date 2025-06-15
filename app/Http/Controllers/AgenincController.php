<?php

namespace App\Http\Controllers;

use App\Models\ageninc;
use App\Models\incisos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgenincController extends Controller
{
    public function index()
    {
        //
    }

    public function store(int $incisoId, int $agenteId)
    {
        $user = Auth::user();
        $ageninc = new ageninc(['agente_id' => $agenteId, 'inciso_id' => $incisoId]);
        $ageninc->created_at = now()->timestamp;
        $ageninc->created_by = $user->id;
        $ageninc->save();
    }
    public function deleteIncisoByAgente(int $id)
    {
        $ageninc = $this->incisosByAgente($id);
        foreach ($ageninc as $inciso) {
            $this->setBase("deleted", $inciso);
            $inciso->save();
        }
    }


    public function incisosByAgente(int $agenteId)
    {
        return ageninc::where([['agente_id', '=', $agenteId], ['deleted_at', '=', null]])->get();
    }

    public function incisoArrayInt(int $id)
    {
        $ageninc = $this->incisosByAgente($id);
        return $ageninc->map(function (ageninc $ageninc) {
            return $ageninc->inciso_id;
        });
    }

    public function update(Request $request, ageninc $ageninc)
    {
        //
    }

    public function destroy(ageninc $ageninc)
    {
        //
    }
}
