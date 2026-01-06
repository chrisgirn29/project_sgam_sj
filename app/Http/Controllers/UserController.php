<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function getUsers()
    {
        $users = User::with('rolRelacion')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->rol,
                'rol_nombre' => $user->rolRelacion ? $user->rolRelacion->rol : null,
            ];
        });

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'rol'      => 'required|exists:rols,id',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'rol'      => $request->rol,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Usuario creado exitosamente', 'user' => $user], 201);
    }

    public function getAll()
    {
        $usuarios = DB::table('users')
            ->leftJoin('rols', 'rols.id', '=', 'users.rol')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COALESCE(rols.rol, "") as rol_nombre'),
                'users.estado'
            )
            ->get();

        return response()->json($usuarios);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role'  => 'required|string|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only(['name', 'email', 'role']));

        return response()->json(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
    }

    public function show($id)
    {
        $user = User::with('rol')->find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json([
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'rol_id'     => optional($user->rol)->id,
            'rol_nombre' => optional($user->rol)->rol,
            'estado'     => $user->estado,
        ]);
    }


    // 🔹 Vista del Login
    public function loginView()
    {
        return view('login');
    }


    // 🔐 LOGIN SEGURO CON BLOQUEO 3 INTENTOS → 4 MINUTOS
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $ip = $request->ip();
        $key = Str::lower("login:{$request->email}:{$ip}");

        // Bloqueo activado
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->with([
                'error' => "Demasiados intentos fallidos. Intenta nuevamente en {$seconds} segundos."
            ]);
        }

        // Intentar autenticación
        if (Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::clear($key); // Resetear intentos al iniciar sesión
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->rol == 2) {
                return redirect()->route('buscar');
            }

            return redirect()->route('app');
        }

        // Registrar intento fallido (4 min = 240 seg)
        RateLimiter::hit($key, 240);

        return back()->with([
            'error' => 'Correo electrónico o contraseña incorrectos.'
        ]);
    }


    // 🔹 Vista principal protegida
    public function appView()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión primero.');
        }

        return view('layout.app');
    }


    // 🔐 Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}

