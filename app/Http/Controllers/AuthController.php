<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Exception;

class AuthController extends Controller
{
    public function showRegister(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Ruta válida en JSON']);
        }

        return Inertia::render('Auth/Register');
    }



    public function showLogin(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Ruta válida en JSON']);
        }

        return Inertia::render('Auth/Login');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Registro exitoso',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error en el registro',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Sesión iniciada',
                'user' => $user,
                'token' => $token
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error en el inicio de sesión',
                'details' => $e->getMessage()
            ], 500);
        }
    }


    public function logout(Request $request)
    {
        try {
            // ✅ Cierre de sesión seguro
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json(['message' => 'Sesión cerrada correctamente']);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al cerrar sesión',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
