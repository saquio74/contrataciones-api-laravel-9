<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseControllers\BaseController;
use App\Models\proveedors;
use Illuminate\Http\Request;

class ProveedorsController extends BaseController
{

    public function __construct()
    {
        $this->entity = new proveedors();
    }
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\proveedors  $proveedors
     * @return \Illuminate\Http\Response
     */
    public function show(proveedors $proveedors)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\proveedors  $proveedors
     * @return \Illuminate\Http\Response
     */
    public function edit(proveedors $proveedors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\proveedors  $proveedors
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, proveedors $proveedors)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\proveedors  $proveedors
     * @return \Illuminate\Http\Response
     */
    public function destroy(proveedors $proveedors)
    {
        //
    }
    public function validateData(Request $request, $entity)
    {
        $where = [];
        if ($request->nombre) $entity->whereRaw('LOWER(TRIM(`proveedors`.`nombre`)) like "%' . $request->nombre . '%"');
        if ($request->dni) array_push($where, ['proveedors.dni', 'like', "%$request->dni%"]);

        return $entity->where($where);
    }

    public function addIncludes()
    {
    }
}
