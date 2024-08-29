<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    public function index()
    {
        return view('Login.login');
    }

    public function login(Request $request)
    {
        try {
            if(auth()->user()){
                return redirect()->route('home.index');
            }
            if($request->input('login.cpf') == null || $request->input('login.senha') == null){
                throw new \Exception('Preencha todos os campos');
            }
            $credentials = [
                'cpf' => string()->replace(['.', '-'], '', $request->input('login.cpf')),
                'password' => $request->input('login.senha'),
            ];
            if (auth()->attempt($credentials, true)) {
                $request->session()->regenerate();
                return redirect()->route('home.index')->with('success', 'Login efetuado com sucesso');
            }

            throw new \Exception('Usuário ou senha inválidos');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
