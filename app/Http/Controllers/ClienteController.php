<?php

namespace App\Http\Controllers;

use App\Models\CidadeIbge;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientes = Cliente::with('infoCliente')->where('loja_id', auth()->user()->loja_id)
            ->whereRaw("nome like '%{$request->nome}%'")->orderBy('nome')->paginate(30);

        return view('cliente.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cliente.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $codIbge = CidadeIbge::where('codigo', trim($_POST['codIbge']))->first();
        $dados = Cliente::create([
            'loja_id' => auth()->user()->loja_id,
            'alltech_id' => '100000',
            'nome' => $_POST['nome'],
            'docto' => $_POST['documento'],
            'tipo' =>  strlen($_POST['documento']) == 11 ? 'F' : 'J',
            'email' => $_POST['email'],
            'fone1' => strlen($_POST['telefones'][0]) > 7 ? $_POST['telefones'][0] : null,
            'fone2' => strlen($_POST['telefones'][1]) > 7 ? $_POST['telefones'][1] : null,
            'celular' => strlen($_POST['telefones'][2]) > 7 ? $_POST['telefones'][2] : null,
            'cidade_ibge_id' => $codIbge->id ? $codIbge->id : null,
            'cep' => intval($_POST['cep']),
            'bairro' => $_POST['bairro'],
            'rua' => $_POST['rua']  ? $_POST['rua']  : null,
            'numero' => $_POST['numero'] ? intval($_POST['numero']) : null,
            'compto' => $_POST['complemento'] ? $_POST['complemento'] : null,
        ]);
            $dados['success'] = true;

        echo json_encode($dados);
        return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
