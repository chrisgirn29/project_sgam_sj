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
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{

public function getUsers()
{
    $users = User::with('rolRelacion')->get()->map(function ($user) {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'estado'     => $user->estado,
            'rol_id'     => $user->rol_id, // la columna real
            'rol_nombre' => $user->rolRelacion ? $user->rolRelacion->rol : '-', // nombre real
        ];
    });

    return response()->json($users);
}

public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'rol_id'   => 'required|exists:rols,id', // ✅ columna correcta
            'estado'   => 'required|integer',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Error de validación',
            'errors' => $e->errors()
        ], 422);
    }

    User::create([
        'name'     => $validated['name'],
        'email'    => $validated['email'],
        'rol_id'   => $validated['rol_id'], // ✅ guardar correctamente
        'estado'   => $validated['estado'],
        'password' => Hash::make($validated['password']),
    ]);

    return response()->json(['success' => true]);
}



public function getAll()
{
    $usuarios = DB::table('users')
        ->leftJoin('rols', 'rols.id', '=', 'users.rol_id')
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
      public function all()
    {
        $users = User::with('rolRelacion')->get();

        $users->map(function ($u) {
            $u->rol_nombre = $u->rolRelacion?->rol;
            return $u;
        });

        return response()->json($users);
    }


  public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email,' . $user->id,
        'rol_id'   => 'required|exists:rols,id', // ✅ columna correcta
        'estado'   => 'required|boolean',
        'password' => 'nullable|confirmed|min:6',
    ]);

    // 🔐 Password solo si viene
    if (!empty($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }

    $user->update($validated);

    return response()->json([
        'message' => 'Usuario actualizado correctamente'
    ]);
}

public function updateEstado(Request $request, $id)
{
    $request->validate([
        'estado' => 'required|in:0,1',
    ]);

    $user = User::findOrFail($id);
    $user->estado = $request->estado;
    $user->save();

    return response()->json(['success' => true]);
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

        return view('portada');
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

