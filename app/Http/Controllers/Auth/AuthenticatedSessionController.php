<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Muestra la vista de login (no se usa si es un modal, pero es bueno tenerla).
     */
    public function create()
    {
        return redirect()->route('home');
    }

    /**
     * Maneja una petición de autenticación entrante.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validamos los datos manualmente
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Intentamos autenticar al usuario
        $user = Usuario::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // 3. Si todo es correcto, iniciamos sesión
        Auth::login($user, $request->boolean('remember'));

        // 4. Regeneramos la sesión por seguridad
        $request->session()->regenerate();

        // 5. Redirigimos a la página de inicio (Inertia se encargará del resto)
        return redirect()->route('home');
    }

    /**
     * Destruye una sesión autenticada.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
