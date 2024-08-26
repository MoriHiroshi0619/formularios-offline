<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            if($request->input('login.email') == null || $request->input('login.senha') == null){
                throw new \Exception('Preencha todos os campos');
            }
            $credentials = [
                'email' => $request->input('login.email'),
                'password' => $request->input('login.senha'),
            ];
            if (auth()->attempt($credentials, true)) {
                $request->session()->regenerate();
                return redirect()->back()->with('success', 'Login efetuado com sucesso');
            }
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
