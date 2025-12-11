<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Estas rutas manejan la autenticación y el acceso con Inertia.js.
|--------------------------------------------------------------------------
*/

# ✅ Página principal
Route::get('/', function () {
    return Inertia::render('Home');
});

# ✅ Rutas de autenticación (Disponible para todos)
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'showLogin']);

# ✅ Cierre de sesión (Solo para usuarios autenticados)
Route::middleware(['auth'])->post('/logout', [AuthController::class, 'logout']);

# ✅ Rutas protegidas para usuarios autenticados
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
Route::middleware('auth')->get('/dashboard', function () {
    return Inertia::render('Auth/Dashboard', [
        'user' => auth()->user(),
    ]);
});

# ✅ Rutas protegidas para administradores
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return Inertia::render('AdminDashboard');
    });
});
