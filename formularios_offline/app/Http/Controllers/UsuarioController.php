<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        return view('Usuario.create');
    }

    public function store(Request $request)
    {
        try {
            if($request->input('aluno') == null){
                throw new \Exception('Preencha todos os campos');
            }
            if($request->input('aluno.password') != $request->input('aluno.password2')){
                throw new \Exception('As senhas devem ser iguais');
            }
            $novoUsuario = new Usuario();
            $novoUsuario->fill($request->input('aluno'));
            $novoUsuario->cpf = Str::replace(['.', '-'], '', $novoUsuario->cpf);
            $novoUsuario->nome = Str::title($novoUsuario->nome);
            $novoUsuario->sobre_nome = Str::title($novoUsuario->sobre_nome);
            $novoUsuario->save();
            return redirect()->route('login.index')->with('success', 'Usuário criado com sucesso');
        }catch (\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
