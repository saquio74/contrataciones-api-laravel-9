<?php

namespace App\Http\Controllers\BaseControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Register(Request $request)
    {
        $data = $request->validate([
            "name" => 'required',
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        $user->token = $user->createToken('authToken')->accessToken;

        return response()->json([
            "user" => $user
        ]);
    }

    public function GetUser()
    {
        $user = User::with('roles.permissionsrole.permissions')->find(Auth::user()->id);
        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            "email" => "email|required",
            "password" => 'required'
        ]);
        if (!Auth::attempt($data)) {
            return response()->json(["invalid credentials"], 401);
        }
        $user = User::with('roles.permissionsrole.permissions')->find(Auth::user()->id);
        $user->token = $user->createToken('authToken')->accessToken;

        return response()->json($user, 200);
    }

    public function GetUsers(Request $request)
    {
        $where = [['users.deleted_at', '=', null]];

        if ($request->rol)
            array_push($where, ['users.name', 'like', "%$request->name%"]);
        if ($request->description)
            array_push($where, ['users.email', 'like', "%$request->email%"]);
        if ($request->id)
            array_push($where, ['users.role_id', '=', $request->rol_id]);

        return User::with('roles.permissionsrole.permissions')->Where($where)->paginate($request->perPage ?? 10, $request->colums ?? ['*'], 'page', $request->page ?? 1);;
    }
    public function Delete(int $userId)
    {
        $user = $this->GetById($userId);
        $this->setBase('deleted', $user);
        $user->save();
    }
    private function GetById(int $userId)
    {
        return User::find($userId);
    }
    public function UpdateUser(Request $request)
    {
        $request->validate(["id" => "required"]);
        $user = $this->GetById($request->id);
        $user->name = $request->name ? $request->name : $user->name;
        $user->email = $request->email ? $request->email : $user->email;
        $user->role_id = $request->role_id ? $request->role_id : $user->role_id;
        $user->save();
        return $user;
    }
    public function Logout(Request $request)
    {
        $request->user()->token()->revoke();
    }
    public function ChangePassword(Request $request)
    {
        $currentUser = $this->GetById(Auth::user()->id);

        $request->validate(["password" => "required|confirmed"]);

        if (!Hash::check($request->oldPassword, $currentUser->password)) {
            return response()->json(['La contraseña vieja no es correcta'], 422);
        }
        $currentUser->password = Hash::make($request->password);
        $currentUser->save();
        return response()->json(['Contraseña actualizada'], 200);
    }
}
