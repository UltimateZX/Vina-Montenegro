<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
{
    // 1. Obtenemos el usuario (como ya hacía Breeze)
    $user = $request->user();

    // 2. ¡NUEVO! Obtenemos todos sus pedidos
    // (Usamos la relación 'pedidos()' que ya definimos en el Modelo Usuario)
    $pedidos = $user->pedidos()
                ->orderBy('fecha_pedido', 'desc')
                ->get();

    // 3. Enviamos los pedidos a la vista, además del usuario
    return view('profile.edit', [
        'user' => $user,
        'pedidos' => $pedidos, // <-- ¡NUEVA VARIABLE!
    ]);
}

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
