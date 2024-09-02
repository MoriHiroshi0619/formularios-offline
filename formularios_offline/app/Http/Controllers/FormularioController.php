<?php

namespace App\Http\Controllers;

use App\Models\Formularios\Formulario;
use Illuminate\Http\Request;

class FormularioController extends Controller
{
    public function index()
    {
        $formularios = Formulario::all();
        return view('formulario.index', compact('formularios'));
    }

    public function create()
    {
        return view('formulario.create');
    }

    public function store(Request $request)
    {
        dd("teste", $request->all());
    }
}
