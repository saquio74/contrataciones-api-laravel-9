<?php

namespace App\Http\Controllers;

use App\Models\ageninc;
use App\Models\incisos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgenincController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(int $incisoId, int $agenteId)
    {
        $ageninc = new ageninc(['agente_id'=> $agenteId,'inciso_id'=> $incisoId]);
        $ageninc->created_at= now()->timestamp;
        $ageninc->created_by = Auth()->User()->id;
        $ageninc->save();
    }
    public function deleteIncisoByAgente(int $id){
        $ageninc = $this->incisosByAgente($id);
        foreach($ageninc as $inciso){
            $this->setBase("deleted",$inciso);
            $inciso->save();
        }
    }

    
    public function incisosByAgente(int $agenteId)
    {
        return ageninc::where([['agente_id','=',$agenteId],['deleted_at','=',null]])->get();
    }
    
    public function incisoArrayInt(int $id){
        $ageninc = $this->incisosByAgente($id);
        return $ageninc->map(function(ageninc $ageninc){
            return $ageninc->inciso_id;
        });
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ageninc  $ageninc
     * @return \Illuminate\Http\Response
     */
    public function edit(ageninc $ageninc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ageninc  $ageninc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ageninc $ageninc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ageninc  $ageninc
     * @return \Illuminate\Http\Response
     */
    public function destroy(ageninc $ageninc)
    {
        //
    }
}
