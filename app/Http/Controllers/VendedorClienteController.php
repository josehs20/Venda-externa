<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrinho;
use App\Models\InfoCliente;
use App\Models\VendedorCliente;
use Illuminate\Support\Facades\Session;


class VendedorClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($vendedor)
    {
        $itens = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
        $count_item = $itens;

        $clientes = VendedorCliente::with('infoCliente')->where('user_id', auth()->user()->id)->get();
        //  $info_cliente = InfoCliente::);
        //  dd($clientes->all('id'));
        return view('cliente.index', compact('count_item', 'clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $itens = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
        // $count_item = $itens;

        // return view('cliente._form', compact('count_item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        date_default_timezone_set('America/Sao_Paulo');
        $date = date('Y-m-d H:i');

        $cliente = VendedorCliente::create([
            'nome' => $request->nome,
            'email' => $request->email ? $request->email : null,
            'telefone' => $request->telefone ? $request->telefone : null,
            'cidade' => $request->cidade ? $request->cidade : null,
            'rua' => $request->rua ? $request->rua : null,
            'numero_rua' => $request->n_rua ? $request->n_rua : null,
            'user_id' => auth()->user()->id,
        ]);

        if ($request->observacao) {

            InfoCliente::create([
                'observacao' => $request->observacao,
                'data' => $date,
                'vendedor_cliente_id' => $cliente->id,
            ]);
        }

        Session::flash('message', "Cliente Adicionado Com Sucesso!!");
        return redirect(route('vendedor.cliente.index', auth()->user()->id));
    }
    public function adiciona_obs($observacao)
    {
        dd($observacao);
    }

    public function deleta_obs($observacao)
    {
        dd($observacao);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd('1');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd('id');
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
        dd('id');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd('id');
    }
}
