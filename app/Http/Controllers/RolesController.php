<?php

namespace App\Http\Controllers;

use App\Models\roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    public $validations = [
        "roles" => "required",
    ];
    public function index(Request $request)
    {
        $where = [['roles.deleted_at', '=', null]];

        if ($request->rol)
            array_push($where, ['roles.name', 'like', "%$request->rol%"]);
        if ($request->description)
            array_push($where, ['roles.description', 'like', "%$request->description%"]);
        if ($request->id)
            array_push($where, ['roles.id', '=', $request->id]);

        $roles = roles::with('permissionsrole.permissions')->Where($where)
            ->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1)
            ;

        return response()->json($roles);
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
     * @param  \App\Models\roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function show(roles $roles)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function edit(roles $roles)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, roles $roles)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\roles  $roles
     * @return \Illuminate\Http\Response
     */
    public function destroy(roles $roles)
    {
        //
    }
}