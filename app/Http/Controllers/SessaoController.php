<?php
namespace App\Http\Controllers;

use App\Models\SessaoJogo;
use Illuminate\Http\Request;

class SessaoController extends Controller
{
    public function show($id)
    {
        $sessao = SessaoJogo::findOrFail($id);

        return view('sessoes.show', compact('sessao'));
    }
}
