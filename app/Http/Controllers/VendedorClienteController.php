<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrinho;
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

        $clientes = auth()->user()->vendedorCliente()->get();

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

        VendedorCliente::create([
            'nome' => $request->nome,
            'telefone' => $request->tel ? $request->tel : null,
            'observacao' => $request->observacao,
            'user_id' => auth()->user()->id,
            'updated_at' => $date,
        ]);
        Session::flash('message', "Cliente Adicionado Com Sucesso!!");
        return redirect(route('vendedor.cliente.index', auth()->user()->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
