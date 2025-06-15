<?php

namespace App\Http\Middleware;

use App\Models\permissions;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class validateHospitalPermmision
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::with('roles.permissionsrole.permissions')->find(Auth::user()->id);
        if ($user->roles->special == "all-access") return $next($request);

        $listaPermisos = array();
        foreach ($user->roles->permissionsrole->values()->all() as $pr) {
            if (str_contains($pr->permissions->name, "Liquidar"))
                array_push($listaPermisos, $pr->permissions->slug);
        }
        $request->query->add(["id" => json_encode($listaPermisos)]);
        return $next($request);
    }
}
