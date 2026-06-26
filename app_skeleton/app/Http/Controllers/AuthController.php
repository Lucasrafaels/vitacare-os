<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function tela()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function entrar(Request $request)
    {
        $credenciais = $request->validate([
            'email' => ['required', 'email'],
            'senha' => ['required', 'string'],
        ]);

        // Auth nativo do Laravel espera "password"; mapeamos aqui.
        $ok = Auth::attempt([
            'email'    => $credenciais['email'],
            'password' => $credenciais['senha'],
        ], $request->boolean('lembrar'));

        if (! $ok) {
            throw ValidationException::withMessages([
                'email' => 'E-mail ou senha inválidos.',
            ]);
        }

        $usuario = Auth::user();

        if (! $usuario->ehAtivo()) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Esta conta está inativa. Contate o gestor responsável.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended('/dashboard');
    }

    public function sair(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
