<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResultadoController extends Controller
{
    public function index()
    {
        return view('Resultados.index');
    }
}
