<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrinho;
use App\Models\Cliente;
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
    public function index(Request $request, $vendedor)
    {

        $clientes = Cliente::with('infoCliente')->where('loja_id', auth()->user()->loja_id)->whereRaw("nome like '%{$request->nome}%'")->orderBy('nome')->paginate(30);

        return view('cliente.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $vendedor)
    {
        dd($vendedor);
        $cliente = Cliente::create([

            'loja_id' => auth()->user()->loja_id, 
            'alltech_id' => null, 
            'nome' => $request->nome, 
            'docto' => $request->docto,
            'tipo' => $request->tipo,
            'email' => $request->email, 
            'fone1' => $request->fone1, 
            'fone2' => $request->fone2,
            'celular' => $request->celular,
            //'cidade_ibge_id' $request,
            //'cep',
            'bairro' => $request->bairro,
            'rua' => $request->rua,
            'numero' => $request->numero,
            'compto' => $request->compto,



            // 'nome' => $request->nome,
            // 'email' => $request->email ? $request->email : null,
            // 'telefone' => $request->telefone ? $request->telefone : null,
            // 'cidade' => $request->cidade ? $request->cidade : null,
            // 'rua' => $request->rua ? $request->rua : null,
            // 'numero_rua' => $request->n_rua ? $request->n_rua : null,
            // 'user_id' => $vendedor,
        ]);
     
        Session::flash('message', "Cliente Adicionado Com Sucesso!!");
        return redirect(route('vendedor.cliente.index', auth()->user()->id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $cliente)
    {
        $data = date('d-m-Y', strtotime($request->data_obs));
        //dd($cliente);
        InfoCliente::create([
            'data' => $request->data_obs ?  $data : null,
            'observacao' => $request->observacao,
            'cliente_id' => $cliente,
        ]);
        Session::flash('clienteadd', "Adicionado Com Sucesso!!");
        return redirect()->back();
    }

    public function deleta_obs_ajax($observacao)
    {
        $response = InfoCliente::find($observacao)->delete();
        if ($response) {
            $dado['success'] = true;
        } else {
            $dado['success'] = false;
        }
        return json_encode($dado);
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
    public function update(Request $request, $vendedor, $cliente)
    {
        VendedorCliente::find($cliente)->update($request->all());

        Session::flash('updateCliente', "Cliente Adicionado Com Sucesso!!");
        return redirect(route('vendedor.cliente.index', $vendedor));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($vendedor, $cliente)
    {
        VendedorCliente::find($cliente)->delete();
        Session::flash('deleta_cliente');
        return redirect(route('vendedor.cliente.index', $vendedor));
    }

    public function venda_salva()
    {

        $carrinhos_Salvos = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Salvo')->get();
        foreach ($carrinhos_Salvos as $key => $carrinho) {
            $carrinho['somaItens'] = $carrinho->carItem()->selectRaw("sum(preco * quantidade) total")->get();
        }
        $cliente_carrinho = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

        $clientes_user = Cliente::where('loja_id', auth()->user()->loja_id)->orderBy('nome')->take(100)->get();

        Session::flash('itens_salvo');

        return view('cliente.vendaSalva', compact('carrinhos_Salvos', 'cliente_carrinho', 'clientes_user'));
    }

    public function substitui_carrinho(Request $request, $carrinho)
    {
        $carrinho_substituido = Carrinho::find($carrinho);
        // dd($request->all());
        if ($request->deleteCarrinho) {
            $carrinho_substituido->delete();
            Session::flash('deleta_carrinho');

            return redirect(route('venda_salva'));
        } elseif ($request->substituir) {
            $cliente_carrinho = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
            // dd($cliente_carrinho);
            if ($cliente_carrinho->cliente) {
                $cliente_carrinho->update(['status' => "Salvo"]);

                $carrinho_substituido->update(['status' => "Aberto"]);
            } else {
                $cliente_carrinho->delete();

                Carrinho::find($carrinho)->update(['status' => "Aberto"]);
            }
        } else {
            //  dd(Carrinho::find($carrinho)->first());
            Carrinho::find($carrinho)->update(['status' => "Aberto"]);
        }
        //  dd('a');
        Session::flash('substituicao');

        return redirect(route('itens_carrinho', auth()->user()->id));
    }
}
