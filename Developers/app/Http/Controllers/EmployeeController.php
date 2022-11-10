<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function register(Request $request)
    {
        $validateData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|int',
            'address' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'salary' => 'required|int',
            'skills' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validateData['name'], 
            'email' => $validateData['email'],
            'phone' => $validateData['phone'],
            'address' => $validateData['address'],
            'position' => $validateData['position'],
            'salary' => $validateData['salary'],
            'skills' => $validateData['skills'],
            'password' => Hash::make($validateData['password']),
        ]);

       
        return response()->json([
            "status" => 1,
            "msg" => "Registro exitoso",
            "user" => $user,
        ],201);
    }

    public function searchEmail(Request $request)
    {
        $validateData = $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $user = User::where("email", "=", $validateData['email'])->first();

        if (isset($user->id)) {
            return response()->json([
                "status" => 1,
                "msg" => "Usuario encontrado",
                'user' => $user,
            ],200);
        }else{
            return response()->json([
                "status" => 0,
                "msg" => "Usuario no registrado",
            ],400);  
        }
    }    

    public function show()
    {
        $users = DB::select('select * from users');
        return $users; 
    }

    public function range(Request $request)
    {
        $validateData = $request->validate([
            'rangeMin' => 'required|int',
            'rangeMax' => 'required|int',
        ]);

        $users = User::where('salary', '>=', $validateData['rangeMin'])
        ->where('salary', '<=', $validateData['rangeMax'])   
        ->orderBy('id', 'desc')
        ->get();

        if (isset($users)) {
            return response()->json([
                "status" => 1,
                "msg" => "Usuario encontrado con ese rango",
                'user' => $users,
            ],200);
        }else{
            return response()->json([
                "status" => 0,
                "msg" => "No hay usuario ese rango",
            ],400);  
        }

    }

}
